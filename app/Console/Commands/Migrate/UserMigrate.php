<?php

namespace App\Console\Commands\Migrate;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '將数据表 tp_users 数据将迁移至新表 tp_users_new!';

    protected $table = 'users';

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
        $old_table = 'users';
        $new_table = 'users_new';

        // 目前用戶表最後 uid
        $old_primary_id = DB::table($old_table)->orderByDesc('id')->first()->id;

        // 新表最後 uid
        $new_primary_id = DB::table($new_table)->orderByDesc('id')->first()->id;

        if ($old_primary_id > $new_primary_id) {
            // 分割集合
            $data = DB::table($old_table)->where('id', '>', $new_primary_id)->limit($batch_num)->get()->chunk($chunk_num);

            $this->line(sprintf('為了避免腳本超時，本次操作將轉移 %s 筆數據，共拆分為 %s 批数据進行迁移！', $batch_num, ceil($batch_num / $chunk_num)));

            $data->each(function($items, $key) use ($new_table) {
                $insert = $items->map(function($item) use ($new_table) {
                    return [
                        'id' => $item->id,
                        'username' => $item->username,
                        'device_id' => $item->device_id,
                        'area' => $item->area,
                        'mobile' => $item->mobile,
                        'avatar' => $item->userface,
                        'sex' => $item->sex,
                        'score' => $item->score,
                        'sign' => $item->sign,
                        'status' => $item->status,
                        'platform' => $item->platform,
                        'version' => $item->version,
                        'subscribed_at' => $item->subscribed_at,
                        'sign_days' => $item->sign_days,
                        'signup_ip' => $item->signup_ip,
                        'last_login_ip' => $item->last_login_ip,
                        'last_login_at' => $item->last_login_time ? Carbon::createFromTimestamp($item->last_login_time) : null,
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
                $this->call('migrate:users');
            } else {
                $this->line('操作已結束');
            }
        } else {
            if ($this->confirm('目前沒有等待遷移的數據，是否切換新舊數據表名稱？')) {
                // 舊表變更名稱
                Schema::rename($old_table, $old_table . '_backup');
                // 新表變更名稱
                Schema::rename($new_table, $old_table);
            }

            $this->line('操作已結束');
        }

        return 0;
    }
}
