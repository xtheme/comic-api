<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Develop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '一袋米要抗幾樓，一袋米要抗二樓，一袋米要我給多嘞，一袋米要有洗尼！';

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
        $this->info('composer dump-autoload');
        system('composer dump-autoload -o');

        $this->call('optimize:clear');
        $this->call('ide-helper:generate');
        $this->call('ide-helper:meta');
        $this->info('');
        $this->info('目前处于开发模式下');
    }
}
