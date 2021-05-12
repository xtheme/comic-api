<?php

namespace App\Console\Commands\Refactor;

use App\Models\Book;
use App\Models\Video;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecomIcon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:RecomIcon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重新处理漫画推荐分类 转换成 tag標籤';

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

        $recom_icon_list = DB::table('recom_icon')->where('display', 1)->get();

        $recom_icon_list->map(function ($recom_icon_item) {

            $title = $recom_icon_item->title;

            if (strpos($recom_icon_item->title,"~")){
                $title = substr($recom_icon_item->title , 0 , strpos($recom_icon_item->title,"~"));
            }

            $recom_list = DB::table('recomlist')->where('type' , $recom_icon_item->id)->pluck('book_id');

            $books = Book::whereIn('id' , $recom_list)->get();

            foreach ($books as $book){
                $book->tag($title);
            }

            $this->line($title . " 新增完成");

        });

    }
}
