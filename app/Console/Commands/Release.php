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
            if ($this->confirm('是否要进行代码发布?')) {
                $this->line('开始进行代码优化...');
                $this->optimize();
                $this->info('');
                $this->info('代码优化完成, 准备好发布');
            } else {
                $this->line('有内鬼终止交易');
            }
        }
    }

    private function optimize()
    {
        $this->call('optimize');        // config:cache / route:cache
        $this->call('view:clear');      // 清除视图缓存
        $this->call('view:cache');      // 重建视图缓存
        $this->call('clear-compiled');  // Remove the compiled class file
        $this->call('version:absorb');  // 发布版本号

        $this->info('目前处于上线模式下, 所有的配置已缓存, 并发布新的版本号');
    }
}
