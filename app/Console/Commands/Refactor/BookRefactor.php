<?php

namespace App\Console\Commands\Refactor;

ini_set('memory_limit', '-1');

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BookRefactor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refactor:book';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重构 book 数据表, 数据将迁移至 books, 执行且请先备份数据表!';

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
        if ($this->confirm('请确认是否运行重构脚本? 执行且请先备份数据表!')) {

            // 分割集合
            $data = DB::table('book')->get()->chunk(1000);

            $this->line('共有' . count($data) . '批数据等待迁移!');

            $data->each(function($books, $key) {
                $insert = $books->map(function($book) {
                    return [
                        'id' => $book->id,
                        'title' => $book->book_name,
                        'author' => $book->pen_name,
                        'description' => $book->book_desc,
                        'end' => $book->book_isend == 1 ? 1 : -1,
                        'vertical_cover' => $book->book_thumb,
                        'horizontal_cover' => $book->book_thumb2,
                        'type' => $book->cartoon_type,
                        'status' => $book->book_status ? 1 : -1,
                        // 'charge' => $book->book_vip ? 1 : -1,
                        'review' => $book->check_status + 1,
                        'operating' => $book->operating,
                        'created_at' => date('Y-m-d H:i:S', $book->book_addtime),
                        'updated_at' => date('Y-m-d H:i:S', $book->book_updatetime),
                    ];
                })->toArray();

                Book::insert($insert);

                $this->line('第' . ($key + 1) . '批数据迁移完成');
            });

            $this->line('数据迁移完成');
        }

        return 0;
    }
}
