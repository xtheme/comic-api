<?php

namespace App\Console\Commands\Patch;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class XadToAdspace extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patch:xad_id_to_adspace';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '寫入設定的xad_id到廣告位';

    private $xad;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->xad = [
            'uat' => [
                1 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => null
                ],
                2 => [
                    'xad_ios_id'        => 369 , 
                    'xad_android_id'    => 277
                ],
                3 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => null
                ],
                4 => [
                    'xad_ios_id'        => 385 , 
                    'xad_android_id'    => 261
                ],
                5 => [
                    'xad_ios_id'        => 373 , 
                    'xad_android_id'    => 273
                ],
                6 => [
                    'xad_ios_id'        => 383 , 
                    'xad_android_id'    => 263
                ],
                7 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => null
                ],
                8 => [
                    'xad_ios_id'        => 387 , 
                    'xad_android_id'    => 259
                ],
                9 => [
                    'xad_ios_id'        => 377 , 
                    'xad_android_id'    => 269
                ],
                10 => [
                    'xad_ios_id'        => 381 , 
                    'xad_android_id'    => 265
                ],
                11 => [
                    'xad_ios_id'        => 375 , 
                    'xad_android_id'    => 271
                ],
                12 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => null
                ],
                13 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => null
                ],
                14 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => 275
                ],
                15 => [
                    'xad_ios_id'        => 389 , 
                    'xad_android_id'    => 121
                ],
                16 => [
                    'xad_ios_id'        => 391 , 
                    'xad_android_id'    => 119
                ],
                17 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => 267
                ],
                18 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => null
                ],
                19 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => null
                ]
            ],
            'prod'=> [
                1 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => null
                ],
                2 => [
                    'xad_ios_id'        => 600727 , 
                    'xad_android_id'    => 600021
                ],
                3 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => null
                ],
                4 => [
                    'xad_ios_id'        => 600733 , 
                    'xad_android_id'    => 600025
                ],
                5 => [
                    'xad_ios_id'        => 600745 , 
                    'xad_android_id'    => 600041
                ],
                6 => [
                    'xad_ios_id'        => 600735 , 
                    'xad_android_id'    => 600027
                ],
                7 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => null
                ],
                8 => [
                    'xad_ios_id'        => 600731 , 
                    'xad_android_id'    => 600023
                ],
                9 => [
                    'xad_ios_id'        => 600741 , 
                    'xad_android_id'    => 600033
                ],
                10 => [
                    'xad_ios_id'        => 600737 , 
                    'xad_android_id'    => 600029
                ],
                11 => [
                    'xad_ios_id'        => 600743 , 
                    'xad_android_id'    => 600039
                ],
                12 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => null
                ],
                13 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => null
                ],
                14 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => 600043
                ],
                15 => [
                    'xad_ios_id'        => 600749 , 
                    'xad_android_id'    => 600297
                ],
                16 => [
                    'xad_ios_id'        => 600751 , 
                    'xad_android_id'    => 600299
                ],
                17 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => 600031
                ],
                18 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => null
                ],
                19 => [
                    'xad_ios_id'        => null , 
                    'xad_android_id'    => null
                ]
            ]
        ];

    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $data = $this->xad[config('app.env')];
        
        foreach ($data as $key => $val) {

            DB::table('ad_spaces')->where('id' , $key)->update($val);

            $this->line('id : ' . $key . ' --- xad_id更新完成');
        }

        $this->line('xad_id 更新完成');

    }
}
