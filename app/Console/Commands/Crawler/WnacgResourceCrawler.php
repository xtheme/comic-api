<?php

namespace App\Console\Commands\Crawler;

use App\Models\ComicResource;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use QL\QueryList;

class WnacgResourceCrawler extends Command
{
    protected $signature = 'wnacg:crawler';

    protected $description = 'Command description';

    protected string $source_platform = 'wnacg'; // 類別: 漢化單行本
    protected string $source_domain = 'https://www.wnacg.org'; // 類別: 漢化單行本
    protected int $category = 9; // 類別: 漢化單行本
    protected string $type = 'jp'; // 日漫
    protected int $translate = 1; // 漢化

    public function handle()
    {
        if ($this->confirm('開始爬取漫畫清單?')) {
            $this->crawlComicList();
        }

        if ($this->confirm('開始爬取漫畫詳情?')) {
            $this->crawlComicDetail();
        }
    }

    /**
     * 爬取基礎漫畫清單
     * @return void
     */
    private function crawlComicList()
    {
        $last_page = $this->getLastPage();

        $rules = [
            'text' => ['.info>.title>a', 'text'],
            'link' => ['.info>.title>a', 'href'],
            'cover' => ['.pic_box>a>img', 'src'],
        ];

        for ($i = $last_page; $i >= 1; $i--) {
            $url = sprintf('%s/albums-index-page-%s-cate-%s.html', $this->source_domain, $i, $this->category);

            $ql = QueryList::get($url);

            $data = $ql->rules($rules)
                ->range('li.gallary_item')
                ->queryData();

            $insert = [];
            foreach ($data as $row) {
                $source_id = preg_replace('/\D/', '', $row['link']);
                $cover = Str::start($row['cover'], 'http:');
                $insert[] = [
                    'source_platform' => $this->source_platform,
                    'source_id' => $source_id,
                    'type' => $this->type,
                    'translate' => $this->translate,
                    'title' => $row['text'],
                    'cover' => $cover,
                ];
            }

            $insert = array_reverse($insert);

            ComicResource::upsert($insert, ['source_platform', 'source_id']);

            $this->info('第 ' . $i . ' 頁');

            $ql->destruct();
        }

        $this->info('採集完成');
    }

    private function getLastPage()
    {
        $url = sprintf('%s/albums-index-cate-%s.html', $this->source_domain, $this->category);

        $ql = QueryList::get($url);

        $paginator = $ql->find('.paginator>a')->texts()->toArray();

        $last_page = end($paginator);

        $ql->destruct();

        return $last_page;
    }

    /**
     * 爬取基礎漫畫清單
     * @return void
     */
    private function crawlComicDetail()
    {
        $url = 'http://www.wnacg.org/photos-index-aid-141600.html';

        $ql = QueryList::get($url)->find('#bodywrap.userwrap');

        $data = [
            'desc' => $ql->find('.uwconn>p')->text(),
            'keywords' => $ql->find('.uwconn>.addtags>a.tagshow')->texts()->toArray(),
        ];


        print_r($data);

    }
}


// $data = collect($data)->map(function ($row) {
//     $id = preg_replace('/\D/', '', $row['link']);
//     $img = file_get_contents('http:' . $row['cover']);
//     // Storage::disk('s3')->put('comic/' . $id . '/cover.jpg', $img);
//     return [
//         'id' => $id,
//         'title' => $row['text'],
//         'link' => $row['link'],
//         'cover' => $row['cover'],
//     ];
// });
//
// dd($data);
