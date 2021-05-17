<?php

namespace App\Console\Commands\Refactor;

ini_set('memory_limit', '-1');

use App\Models\BookChapter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BookChaptersRefactor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refactor:book_chapter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重构 chapterlist 数据表, 数据将迁移至 book_chapters, 执行且请先备份数据表!';

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
        // if ($this->confirm('请确认是否运行重构脚本? 执行且请先备份数据表!')) {

            // 分割集合
            $data = DB::table('chapterlist')->get()->chunk(50);
            // $data = DB::table('chapterlist')->where('id', 27793)->get();

            $this->line('共有' . count($data) . '批数据等待迁移!');

            $data->each(function($chapters, $key) {
                $insert = $chapters->map(function($chapter) {
                    return [
                        'id' => $chapter->id,
                        'book_id' => $chapter->book_id,
                        'episode' => $chapter->idx,
                        'title' => $chapter->title,
                        'content' => $chapter->content,
                        'json_images' => $chapter->json_images ?? '',
                        'status' => $chapter->status ? 1 : -1,
                        'charge' => $chapter->isvip ? 1 : -1,
                        'review' => $chapter->check_status,
                        'operating' => $chapter->operating,
                        'created_at' => date('Y-m-d H:i:S', $chapter->addtime),
                        'updated_at' => date('Y-m-d H:i:S', $chapter->updatetime),
                    ];
                })->toArray();

                BookChapter::insert($insert);

                $this->line('第' . ($key + 1) . '批数据迁移完成');
            });

            $this->line('数据迁移完成');
        // }

        return 0;
    }
}
