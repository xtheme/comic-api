<?php

namespace App\Console\Commands\Convert;

use App\Models\Book;
use Conner\Tagging\Model\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BookTagsConvert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:books';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '轉換漫畫數據!';

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
        if ($this->confirm('请确认是否执行数据迁移？')) {

            $tagged = DB::table('tp_tagging_tagged')->where('taggable_type', 'App\Models\Book')->get();

            $bulk = $tagged->reject(function ($item) {
                return $item->name === '';
            })->map(function ($item) {
                return [
                    'slug' =>  mb_strtolower($item->name, 'UTF-8'),
                    'name' => $item->name,
                    'suggest' => $item->status,
                    'count' => 0,
                    'tag_group_id' => null,
                    'description' => $item->desc,
                    'queries' => 0,
                    'priority' => $item->orders,
                ];
            })->toArray();

            // 转移类别数据到 tagging_tags
            DB::table('tagging_tags')->insert($bulk);

            $this->line('category 數據已轉移到 tagging_tags');

            $books = Book::all();
            // $books = Book::where('id', '>', 17183)->get();

            $books->each(function ($book) {
                $ids = explode(',', $book->cate_id);
                $ids = array_diff($ids, [1405]); // 拿掉 "最新"
                $categories = DB::table('category')->where('status', 1)->whereIn('id', $ids)->get();
                $categories = $categories->reject(function ($category) {
                    return Tag::where('name', $category->name)->exists() === false;
                })->map(function ($category) {
                    return $category->name;
                })->toArray();

                $book->tag($categories);

                $this->line('#' . $book->id . ' 已改用標籤關聯');
            });

            $this->line('漫畫類別已改用標籤關聯');
        }

        return 0;
    }
}