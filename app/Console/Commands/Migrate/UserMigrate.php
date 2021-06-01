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
        $batch_num = 20000;

        // 每多少筆切割一次操作
        $chunk_num = 500;

        // 目前用戶表最後 uid
        $current_uid = DB::table('users')->orderByDesc('id')->first()->id;

        // 新表最後 uid
        $refactor_uid = DB::table('users_new')->orderByDesc('id')->first()->id;

        if ($current_uid > $refactor_uid) {
            // 分割集合
            $data = DB::table('users')->where('id', '>', $refactor_uid)->limit($batch_num)->get()->chunk($chunk_num);

            $this->line(sprintf('為了避免腳本超時，本次操作將轉移 %s 筆數據，共拆分為 %s 批数据進行迁移！', $batch_num, ceil($batch_num / $chunk_num)));

            $data->each(function($users, $key) {
                $insert = $users->map(function($user) {
                    return [
                        'id' => $user->id,
                        'username' => $user->username,
                        'device_id' => $user->device_id,
                        'area' => $user->area,
                        'mobile' => $user->mobile,
                        'avatar' => $user->userface,
                        'sex' => $user->sex,
                        'score' => $user->score,
                        'sign' => $user->sign,
                        'status' => $user->status,
                        'platform' => $user->platform,
                        'version' => $user->version,
                        'subscribed_at' => $user->subscribed_at,
                        'sign_days' => $user->sign_days,
                        'register_ip' => $user->signup_ip,
                        'last_login_ip' => $user->last_login_ip,
                        'last_login_at' => $user->last_login_time ? Carbon::createFromTimestamp($user->last_login_time) : null,
                        'created_at' => $user->create_time ? Carbon::createFromTimestamp($user->create_time) : null,
                        'updated_at' => $user->update_time ? Carbon::createFromTimestamp($user->update_time) : null,
                    ];
                })->toArray();

                DB::table('users_new')->insert($insert);

                $this->line('第 ' . ($key + 1) . ' 批数据迁移完成...');
            });

            $this->line('本次數據已全數遷移！');

            $latest_uid = DB::table('users_new')->orderByDesc('id')->first()->id;

            $pending_num = DB::table('users')->where('id', '>', $latest_uid)->count();

            if ($this->confirm('尚有' . $pending_num . '筆數據等待遷移，是否繼續執行此腳本？')) {
                $this->call('refactor:users');
            } else {
                $this->line('操作已結束');
            }
        } else {
            if ($this->confirm('目前沒有等待遷移的數據，是否切換新舊數據表名稱？')) {
                // 舊表變更名稱
                Schema::rename('users', 'users_backup');
                // 新表變更名稱
                Schema::rename('users_new', 'users');
            }

            $this->line('操作已結束');
        }

        return 0;
    }
}
