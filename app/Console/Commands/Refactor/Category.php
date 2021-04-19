<?php

namespace App\Console\Commands\Refactor;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\User;
use Conner\Tagging\Model\Tag;
use Conner\Tagging\Model\TagGroup;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Category extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refactor:categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重構 category 資料表, 將數據轉換到 tagging_tags, 漫畫類別改用關聯方式查詢, 执行且请先备份数据表!';

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
        if ($this->confirm('请确认是否运行重构脚本? 执行且请先备份数据表!')) {
            // 標籤分組
            // $groups = ['category', 'section'];
            //
            // foreach ($groups as $group) {
            //     TagGroup::create(['name' => $group]);
            // }

            // $categories = BookCategory::all();
            //
            // $bulk = $categories->reject(function ($item) {
            //     return $item->name === '';
            // })->map(function ($item) {
            //     return [
            //         // 'slug' => mb_strtolower(pinyin_permalink($item->name), 'UTF-8'),
            //         'slug' =>  mb_strtolower($item->name, 'UTF-8'),
            //         'name' => $item->name,
            //         'suggest' => $item->status,
            //         'count' => 0,
            //         'tag_group_id' => 1,
            //         'description' => $item->desc,
            //         'queries' => 0,
            //         'priority' => $item->orders,
            //     ];
            // })->toArray();
            //
            // // 转移类别数据到 tagging_tags
            // DB::table('tagging_tags')->insert($bulk);
            //
            // $this->line('category 數據已轉移到 tagging_tags');

            // $books = Book::all();
            $books = Book::where('id', '>', 17183)->get();

            $books->each(function ($book) {
                $ids = explode(',', $book->cate_id);
                $categories = BookCategory::where('status', 1)->whereIn('id', $ids)->get();
                $categories = $categories->reject(function ($category) {
                    return Tag::where('name', $category->name)->exists() === false;
                })->map(function ($category) {
                    return $category->name;
                })->toArray();

                $book->tag($categories);
            });

            $this->line('漫畫類別已改用標籤關聯');
        }

        return 0;
    }
}
