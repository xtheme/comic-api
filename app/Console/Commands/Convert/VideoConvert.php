<?php

namespace App\Console\Commands\Convert;

ini_set('memory_limit', '-1');

use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VideoConvert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:videos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '轉換視頻數據!';

    // 国家
    public static array $countries = [
        '0' => '日本',
        '1' => '韩国',
        '2' => '印度',
        '5' => '中国大陆',
        '6' => '中国台湾',
        '7' => '俄罗斯',
        '8' => '欧美',
        '9' => '其他地区',
    ];

    // 標籤類型
    public static array $tag_countries = [
        '1' => 'video.subject', // 主题
        '2' => 'video.place', // 场景
        '3' => 'video.role', // 角色
        '4' => 'video.costume', // 服装
        '5' => 'video.body', // 体型
        '6' => 'video.behavior', // 行为
        '7' => 'video.play', // 玩法
        '8' => 'video.genre', // 类别
        '10' => 'video.attribute', // 属性
    ];

    private string $source_platform = 'qqc';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // 每次轉詞數據量
        $batch_num = 30000;

        // 每多少筆切割一次操作
        $chunk_num = 1000;

        // 動畫源最新視頻 id
        $last_source_id = DB::table('source_movies')->where('status', 1)->orderByDesc('id')->first()->id;

        // 本地最新視頻 id
        $last_record = DB::table('videos')->where('source_platform', $this->source_platform)->orderByDesc('source_id')->first();
        $last_target_id = $last_record ? $last_record->source_id : 0;

        if ($last_source_id > $last_target_id) {
            // 分割集合
            $data = DB::table('source_movies')->where('status', 1)->where('id', '>', $last_target_id)->limit($batch_num)->get()->chunk($chunk_num);

            $this->line(sprintf('為了避免腳本超時，本次操作將轉移 %s 筆數據，共拆分為 %s 批数据進行迁移！', $batch_num, ceil($batch_num / $chunk_num)));

            $data->each(function ($items, $key) {
                $insert = $items->map(function ($item) {
                    // actor
                    $actor_list = $this->getActorList();
                    $actor_ids = explode(',', $item->actor);
                    $tmp_actors = [];
                    foreach ($actor_ids as $id) {
                        if (isset($actor_list[$id])) {
                            $names = explode('/', $actor_list[$id]);
                            foreach ($names as $name) {
                                $tmp_actors[] = $name;
                            }
                        }
                    }
                    // tags
                    $tag_list = $this->getTagList();
                    $tag_ids = explode(',', $item->tags);
                    $tmp_tags = [];
                    foreach ($tag_ids as $id) {
                        if (isset($tag_list[$id])) {
                            $tmp_tags[] = $tag_list[$id]['name'];
                        }
                    }

                    return [
                        'title' => $item->video_name,
                        'cover' => $item->preview_pics,
                        'hls' => $item->url,
                        'description' => $item->description,
                        'status' => $item->status,
                        'length' => $item->movie_length,
                        'country' => $item->country,
                        'mosaic' => $item->video_type === 0 ? 1 : 0,
                        'style' => $item->shooting_type,
                        'subtitle' => $item->subtitle_type,
                        'view_counts' => $item->views,
                        'number' => strtoupper($item->number),
                        'producer' => $item->producer,
                        'release_date' => $item->publish_time,
                        'source_platform' => $this->source_platform,
                        'source_id' => $item->id,
                        'created_at' => $item->created_at,
                        'updated_at' => $item->updated_at,
                        'actor' => join(',', $tmp_actors),
                        'keywords' => join(',', $tmp_tags),
                    ];
                })->toArray();

                DB::table('videos')->insert($insert);

                $this->line('第 ' . ($key + 1) . ' 批数据迁移完成...');
            });

            $this->line('本次數據已全數遷移！');

            $latest_target_id = DB::table('videos')->where('source_platform', $this->source_platform)->orderByDesc('source_id')->first()->source_id;

            $pending_num = DB::table('source_movies')->where('id', '>', $latest_target_id)->where('status', 1)->count();

            if ($this->confirm('尚有' . $pending_num . ' 筆數據等待遷移，是否繼續執行此腳本？')) {
                $this->call('convert:videos');
            } else {
                $this->line('操作已結束');
            }
        } else {
            $this->line('目前沒有等待遷移的數據');

            if ($this->confirm('是否要进行女優標籤轉換?')) {
                $this->taggingActor();
            }

            if ($this->confirm('是否要进行視頻標籤轉換?')) {
                $this->taggingTags();
            }

            $this->line('操作已結束');
        }
    }

    // 獲取女優清單
    private function getActorList()
    {
        return Cache::remember('actor', 3600, function () {
            $girls = DB::table('source_girls')->get(['id', 'chinese_name'])->toArray();

            return collect($girls)->mapWithKeys(function ($item) {
                return [$item->id => $item->chinese_name];
            })->toArray();
        });
    }

    // 標記女優標籤
    private function taggingActor()
    {
        $this->line('女優標籤轉換中...');

        $list = $this->getActorList();

        $videos = Video::orderBy('id')->get(['id', 'source_id']);

        $videos->each(function (Video $video) use ($list) {
            $source = DB::table('source_movies')->where('id', $video->source_id)->first();
            $actor = explode(',', $source->actor);
            $tags = [];
            foreach ($actor as $actor_id) {
                $names = $list[$actor_id] ?? null;
                if ($names) {
                    $names = explode('/', $names);
                    foreach ($names as $name) {
                        $tags[] = $name;
                    }
                }
            }
            $video->attachTags($tags, 'video.actor');
            $this->line('#' . $video->id . ' 已添加女優標籤！');
        });

        $this->line('已添加女優標籤！');
    }

    // 獲取標籤清單
    private function getTagList()
    {
        return Cache::remember('tags', 3600, function () {
            $tags = DB::table('source_tags')->where('status', '1')->get(['id', 'name', 'cate_id'])->toArray();

            return collect($tags)->mapWithKeys(function ($item) {
                return [
                    $item->id => [
                        'cate' => self::$tag_countries[$item->cate_id],
                        'name' => $item->name,
                    ]
                ];
            })->toArray();
        });
    }

    // 標記視頻標籤
    private function taggingTags()
    {
        $this->line('視頻標籤轉換中...');

        $list = $this->getTagList();

        $videos = Video::orderBy('source_id')->get(['id', 'source_id']);

        $videos->each(function (Video $video) use ($list) {
            $source = DB::table('source_movies')->where('id', $video->source_id)->first();
            $tag_ids = explode(',', $source->tags);
            $tags = [];
            foreach ($tag_ids as $id) {
                $tag = $list[$id] ?? null;
                if ($tag) {
                    $tags[$tag['cate']][] = $tag['name'];
                }
            }

            foreach ($tags as $cate => $tag) {
                $video->attachTags($tag, $cate);
            }
            $this->line('#' . $video->id . ' 已添加視頻標籤！');
        });

        $this->line('已添加視頻標籤！');
    }
}
