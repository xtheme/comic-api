<?php

namespace App\Console\Commands\Refactor;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Signs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refactor:signs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '把原先的签到写到新表记录';

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

        $signs = DB::table('sign')->orderByDesc('addtime')->get();


        DB::table('signs')->truncate();
        $signs->each(function ($sign) {
            for ($i = 0 ; $i < $sign->days ; $i++) {
                $date = date('Y-m-d H:i:s',strtotime("-".$i." day", $sign->addtime));

                DB::table('signs')->insert(
                    [
                        'user_id' => $sign->uid,
                        'created_at' => $date
                    ]
                );
            }

            DB::table('users')->where('id' , $sign->uid)->update([
                'sign_days' => $sign->days
            ]);

            $this->line('用戶id: ' . $sign->uid . '，複寫天數: ' . $sign->days);
        });

        $this->line('数据转移signs完成');
    }
}
