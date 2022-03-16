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

        // if ($this->confirm('開始爬取漫畫詳情?')) {
        //     $this->crawlComicDetail();
        // }

        if ($this->confirm('開始爬取漫畫原圖路徑?')) {
            $this->crawlRawImages();
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

            $this->info('第 '.$i.' 頁');

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

    private function getComicTotalPage(ComicResource $comic)
    {
        $url = sprintf('%s/photos-index-aid-%s.html', $this->source_domain, $comic->source_id);

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
        $pending_data = ComicResource::where('crawl_detail', 0)->get()->chunk(200);

        $pending_data->each(function ($items) {
            $items->each(function (ComicResource $item) {
                $source_id = $item->source_id;

                $url = sprintf('%s/photos-index-aid-%s.html', $this->source_domain, $source_id);

                $ql = QueryList::get($url);
                $range = $ql->find('#bodywrap.userwrap');
                $description = $range->find('.uwconn>p')->text();
                $keywords = $range->find('.uwconn>.addtags>a.tagshow')->texts()->toArray();

                $item->description = Str::replaceFirst('簡介：', '', $description);
                $item->keywords = join(',', $keywords);
                $item->crawl_detail = 1;
                $item->save();

                $ql->destruct();
            });
        });
    }

    private function crawlRawImages()
    {
        $pending_data = ComicResource::where('crawl_image', 0)->take(20s0)->get();

        $pending_data->each(function (ComicResource $comic) {
            $pages = $this->getComicTotalPage($comic);
            $raw_images = [];

            $this->info('#'.$comic->id.' crawling from '.$pages.' pages!');

            $bar = $this->output->createProgressBar($pages);

            $bar->start();

            for ($i = 1; $i <= $pages; $i++) {
                $url = sprintf('%s/photos-index-page-%s-aid-%s.html', $this->source_domain, $i, $comic->source_id);

                $ql = QueryList::get($url);

                $images = $ql->find('li.gallary_item img')->attrs('src')->toArray();

                foreach ($images as $image) {
                    $raw_images[] = Str::start($image, 'http:');
                }

                $ql->destruct();

                $bar->advance();
            }

            $bar->finish();

            // Update Comic
            $comic->crawl_image = 1;
            $comic->raw_images = $raw_images;
            $comic->save();

            $this->line('');
            $this->info('#'.$comic->id.' crawling success!');
            $this->line('');
        });
    }
}
