<?php

namespace App\Console\Commands\Migrate;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:admins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將数据表 admin 数据将迁移至新表 admins!';

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
        $batch_num = 10000;

        // 每多少筆切割一次操作
        $chunk_num = 500;

        // 新舊數據表名稱
        $old_table = 'admin';
        $new_table = 'admins';

        // 目前用戶表最後 uid
        $old_primary_id = DB::table($old_table)->orderByDesc('id')->first()->id;

        // 新表最後 uid
        $new_primary_id = DB::table($new_table)->orderByDesc('id')->first()->id ?? 0;

        if ($old_primary_id > $new_primary_id) {
            // 分割集合
            $data = DB::table($old_table)->where('id', '>', $new_primary_id)->limit($batch_num)->get()->chunk($chunk_num);

            $this->line(sprintf('為了避免腳本超時，本次操作將轉移 %s 筆數據，共拆分為 %s 批数据進行迁移！', $batch_num, ceil($batch_num / $chunk_num)));

            $data->each(function($items, $key) use ($new_table) {
                $insert = $items->map(function($item) use ($new_table) {
                    return [
                        'id' => $item->id,
                        'nickname' => $item->nickname,
                        'username' => $item->username,
                        'password' => $item->password,
                        'avatar' => $item->image,
                        'status' => $item->status,
                        'login_ip' => $item->loginip,
                        'login_at' => $item->logintime ? Carbon::createFromTimestamp($item->logintime) : null,
                        'created_at' => $item->create_time ? Carbon::createFromTimestamp($item->create_time) : null,
                        'updated_at' => $item->update_time ? Carbon::createFromTimestamp($item->update_time) : null,
                    ];
                })->toArray();

                DB::table($new_table)->insert($insert);

                $this->line('第 ' . ($key + 1) . ' 批数据迁移完成...');
            });

            $this->line('本次數據已全數遷移！');

            $latest_primary_id = DB::table($new_table)->orderByDesc('id')->first()->id;

            $pending_num = DB::table($old_table)->where('id', '>', $latest_primary_id)->count();

            if ($this->confirm('尚有' . $pending_num . '筆數據等待遷移，是否繼續執行此腳本？')) {
                $this->call('migrate:admins');
            } else {
                $this->line('操作已結束');
            }
        } else {
            $this->line('目前沒有等待遷移的數據，操作已結束');
        }

        return 0;
    }
}
