<?php

namespace App\Console\Commands\Refactor;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VideoRefactor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refactor:video';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重構 video 資料表!';

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
        Schema::table('video', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('video_series', function (Blueprint $table) {
            $table->index('video_id');
            $table->index('status');
            $table->index('charge');
            $table->index('video_domain_id');
        });

        $this->line('資料表已更新');

        return 0;
    }
}
