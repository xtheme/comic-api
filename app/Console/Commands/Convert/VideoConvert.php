<?php

namespace App\Console\Commands\Convert;

ini_set('memory_limit', '-1');

use App\Models\Book;
use App\Models\Video;
use App\Models\Video\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
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
    public static $countries = [
        '0' => '日本',
        '1' => '韩国',
        '2' => '印度',
        '5' => '中国大陆',
        '6' => '中国台湾',
        '7' => '俄罗斯',
        '8' => '欧美',
        '9' => '其他地区',
    ];

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
     * @return int
     */
    public function handle()
    {
        // 每次轉詞數據量
        $batch_num = 1000;

        // 每多少筆切割一次操作
        $chunk_num = 500;

        // 動畫源最新視頻 id
        $old_primary_id = DB::table('source_movies')->where('status', 1)->orderByDesc('id')->first()->id;

        // 本地最新視頻 id
        $new_primary_id = DB::table('videos')->where('source_platform', 'nasu')->orderByDesc('source_id')->first()->source_id ?? 0;

        if ($old_primary_id > $new_primary_id) {
            // 分割集合
            $data = DB::table('source_movies')->where('id', '>', $new_primary_id)->limit($batch_num)->get()->chunk($chunk_num);

            $this->line(sprintf('為了避免腳本超時，本次操作將轉移 %s 筆數據，共拆分為 %s 批数据進行迁移！', $batch_num, ceil($batch_num / $chunk_num)));

            $data->each(function ($items, $key) {
                $insert = $items->map(function ($item) {
                    // 標籤 tags
                    // 女優 actor
                    return [
                        'title' => $item->video_name,
                        'cover' => $item->preview_pics,
                        'storage_path' => $item->url,
                        'description' => $item->description,
                        'ribbon' => 0,
                        'status' => $item->status,
                        'length' => $item->movie_length,
                        'country' => self::$countries[$item->country],
                        'mosaic' => $item->video_type,
                        'style' => $item->shooting_type,
                        'subtitle' => $item->subtitle_type,
                        'views' => $item->views,
                        'number' => strtoupper($item->number),
                        'producer' => $item->producer,
                        'actor' => $item->actor,
                        'published_at' => $item->publish_time,
                        'created_at' => $item->created_at,
                        'source_platform' => 'nasu',
                        'source_id' => $item->id,
                    ];
                })->toArray();

                DB::table('videos')->insert($insert);

                $this->line('第 ' . ($key + 1) . ' 批数据迁移完成...');
            });

            $this->line('本次數據已全數遷移！');

            $latest_primary_id = DB::table('videos')->where('source_platform', 'nasu')->orderByDesc('source_id')->first()->source_id;

            $pending_num = DB::table('source_movies')->where('id', '>', $latest_primary_id)->where('status', 1)->count();

            if ($this->confirm('尚有' . $pending_num . '筆數據等待遷移，是否繼續執行此腳本？')) {
                $this->call('convert:videos');
            } else {
                $this->line('操作已結束');
            }
        } else {
            $this->line('目前沒有等待遷移的數據，操作已結束');
        }

        return 0;
    }
}
