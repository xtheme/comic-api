<?php

namespace App\Console\Commands\Migrate;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ConfigMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:configs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重构 config 数据表结构, 执行复制到新表, 請先確定configs已經存在old_code 對應舊表!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->group = [
            'app' => 'web_config',
            'comment' => 'comments_config',
            'payment' => 'web_config',
            'service' => 'web_config',
            'share' => 'web_config',
            'ad' => 'web_config',
        ];

    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //舊配置
        $old_configs = DB::table('config')->get()->keyBy('config_type');

        //預設先寫入sql資料
        $path = public_path('sql/configs.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);

        $configs = DB::table('configs')->get();

        $configs->each(function ($config) use($old_configs) {
            //取出舊配置 json解析結果
            $old_config_content = json_decode($old_configs[$this->group[$config->group]]->config_content);
            $old_code = $config->old_code;

            //舊配置存在的情況才進行更新新配置
            if (isset($old_config_content->$old_code)){

                $content = $old_config_content->$old_code;
                //more_domain 额外处理写入格式
                if ($old_code == 'more_domain'){
                    $content = str_replace(",","\r\n",$old_config_content->$old_code);
                }

                DB::table('configs')->where('id' , $config->id)->update(
                    [
                        'content' => $content
                    ]
                );

                $this->line('新配置: ' . $config->code . '，複寫詳細: ' . $content);
            }
            
        });

        $this->line('数据转移configs完成');
    }
}
