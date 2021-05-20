<?php

namespace App\Console\Commands\Refactor;

ini_set('memory_limit', '-1');

use App\Models\User;
use App\Models\History;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FavoriteHistoryRefactor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refactor:favorite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將 guanzhu 表数据迁移至 histories!';

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
        $logs = DB::table('guanzhu')->get();

            // $this->line('共有' . count($logs) . '批数据等待迁移!');

            // $data->each(function($logs, $key) {
                $logs->each(function($log, $key) {
                    $user = User::find($log->fromid);
                    if (!$user) return;

                    $where = [
                        'major_id' => $log->toid,
                        'minor_id' => $log->charter_id,
                        'user_vip' => $user->subscribed_status ? 1 : -1,
                        'user_id' => $log->fromid,
                        'type' => 'favorites',
                        'class' => 'book',
                    ];
                    $exists = History::where($where)->exists();

                    if ($exists) return;

                    $insert = [
                        'major_id' => $log->toid,
                        'minor_id' => $log->charter_id,
                        'user_vip' => $user->subscribed_status ? 1 : -1,
                        'user_id' => $log->fromid,
                        'type' => 'favorite',
                        'class' => 'book',
                        'created_at' => date('Y-m-d H:i:S', $log->gz_time),
                    ];

                    History::insert($insert);

                    $this->line('第' . ($key + 1) . '批数据迁移完成');
                });


            // });

            $this->line('数据迁移完成');
        // }

        return 0;
    }
}
