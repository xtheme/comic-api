<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class Release
 * @package App\Console\Commands
 */
class Release extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'release {--f|force : 跳过询问强制最佳化}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '口口有泥，誰給你一袋米呦，辛辣天森！';

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
     */
    public function handle()
    {
        $force = $this->option('force');

        if ($force) {
            $this->optimize();
        } else {
            if ($this->confirm('是否要进行上线前置作业?')) {
                $this->line('运行脚本...');
                $this->optimize();
                $this->info('');
                $this->info('已完成上线前置作业, 准备好发布');

            } else {
                $this->line('有内鬼终止交易');
            }
        }
    }

    private function optimize()
    {
        $this->call('migrate'); // 数据表结构更新
        $this->call('db:seed', ['--class' => 'UpgradeSeeder']); // 新增本次升级数据
        $this->call('optimize');

        $this->info('npm run prod');
        system('npm run prod');

        $this->info('重启 Horizon 进程');
        $this->call('horizon:terminate');

        $this->info('移除过旧的活动日志');
        $this->call('activitylog:clean');
    }
}
