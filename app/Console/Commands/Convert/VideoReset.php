<?php

namespace App\Console\Commands\Convert;

ini_set('memory_limit', '-1');

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class VideoReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:videos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清除視頻數據!';

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
        if ($this->confirm('是否清空舊數據')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('videos')->truncate();
            DB::table('tags')->truncate();
            DB::table('taggables')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->line('數據已清空！');

        return 0;
    }
}
