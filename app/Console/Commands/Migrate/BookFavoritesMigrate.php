<?php

namespace App\Console\Commands\Migrate;

ini_set('memory_limit', '-1');

use App\Models\UserFavoriteBook;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BookFavoritesMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:book_favorites';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將数据表 guanzhu 数据将迁移至新表 book_favorites!';

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
        if ($this->confirm('请确认是否执行数据迁移？')) {
            // 分割集合
            $data = DB::table('guanzhu')->get()->chunk(1000);

            $this->line('共有' . count($data) . '批数据等待迁移!');

            $data->each(function($logs, $key) {
                $logs->each(function($log) {

                    $where = [
                        'book_id' => $log->toid,
                        'chapter_id' => $log->charter_id,
                        'user_id' => $log->fromid,
                    ];
                    $exists = UserFavoriteBook::where($where)->exists();

                    if ($exists) return;

                    $insert = [
                        'book_id' => $log->toid,
                        'chapter_id' => $log->charter_id,
                        'user_id' => $log->fromid,
                        'created_at' => date('Y-m-d H:i:S', $log->gz_time),
                    ];

                    UserFavoriteBook::insert($insert);
                });

                $this->line('第' . ($key + 1) . '批数据迁移完成');
            });

            $this->line('数据迁移完成');
        }

        return 0;
    }
}
