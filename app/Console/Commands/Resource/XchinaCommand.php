<?php

namespace App\Console\Commands\Resource;

use App\Jobs\AlbumCrawlJob;
use App\Jobs\AlbumDownloadJob;
use App\Models\ResourceAlbum;
use Illuminate\Console\Command;
use QL\QueryList;

/**
 * 採集步驟流程
 * 1. 列表業採集漫畫清單 php artisan comic:xchina update
 * 2. 依據清單採集圖片網址 php artisan comic:xchina crawler
 * 3. 將圖片下載回本地並重新編號排序 php artisan comic:xchina download
 */
class XchinaCommand extends Command
{
    protected $signature = 'comic:xchina {process? : 流程} {--take=20 : 批次數量}';

    protected $description = '小黃書';

    protected string $source_platform = 'xchina'; // 小黃書
    protected string $source_domain = 'https://tw.xchina.co';
    protected int $category = 1; // 類別: 1=性感寫真, 2=人體攝影

    public function handle()
    {
        $process = $this->argument('process');

        switch ($process) {
            case 'update':
                if ($this->confirm('開始爬取寫真清單?')) {
                    $this->updateListing();
                }
                break;
            case 'crawler':
                if ($this->confirm('開始爬取寫真原圖路徑?')) {
                    $this->crawlRawImages();
                }
                break;
            case 'download':
                if ($this->confirm('將圖片下載回本地並重新編號排序?')) {
                    $this->downloadImages();
                }
                break;
            default:
                $this->error('請選擇流程');
                break;
        }
    }

    // https://tw.xchina.co/photos/kind-1.html
    private function getLastPage()
    {
        $url = sprintf('%s/photos/kind-%s.html', $this->source_domain, $this->category);

        $ql = QueryList::get($url);

        $paginator = $ql->find('.pager:gt(0) a')->texts()->toArray();

        $paginator = array_reverse($paginator);

        $last_page = $paginator[1];

        $ql->destruct();

        return $last_page;
    }

    /**
     * 爬取基礎漫畫清單
     * @return void
     */
    private function updateListing()
    {
        $last_page = $this->getLastPage();

        $rules = [
            'title' => ['div:nth-child(3) > a', 'text'],
            'link' => ['div:nth-child(3) > a', 'href'],
            'cover' => ['a > img', 'src'],
            'category' => ['div:nth-child(2) > div:nth-child(1) > a', 'text'],
            'model' => ['div:nth-child(2) > div:nth-child(2) > a', 'text'],
        ];

        for ($i = $last_page; $i >= 1; $i--) {
            $url = sprintf('%s/photos/kind-%s/%s.html', $this->source_domain, $this->category, $i);

            $ql = QueryList::get($url);

            $data = $ql->rules($rules)
                ->range('.list .item')
                ->queryData();

            $insert = [];
            foreach ($data as $row) {
                if (!$row['title']) {
                    continue;
                }

                $source_id = preg_replace('/^(\/photo\/id-)+(\"?[\w]*\"?).+(html)$/', '$2', $row['link']);

                $insert[] = [
                    'source_platform' => $this->source_platform,
                    'source_id' => $source_id,
                    'title' => $row['title'],
                    'model' => $row['model'],
                    'category' => str_replace(' ', '', trim($row['category'])),
                    'raw_cover' => $row['cover'],
                    'process' => 1,
                ];
            }

            // $this->info(json_encode($insert, JSON_UNESCAPED_UNICODE));

            $insert = array_reverse($insert);

            ResourceAlbum::upsert($insert, ['source_platform', 'source_id']);

            $this->info('第 ' . $i . ' 頁');

            $ql->destruct();
        }

        $this->info('採集完成');
    }

    /**
     * Make process 1 to 2
     *
     * @return void
     */
    private function crawlRawImages()
    {
        $take = $this->option('take');

        $pending_data = ResourceAlbum::where('process', 1)->latest()->take($take)->get();

        $pending_data->each(function (ResourceAlbum $comic) {
            $this->info('#' . $comic->id . ' 開始採集縮圖路徑!');
            AlbumCrawlJob::dispatch($comic)->onQueue('long-running-queue');
        });
    }

    /**
     * Make process 2 to 3/4, 4 was download failed
     *
     * @return void
     */
    private function downloadImages()
    {
        $take = $this->option('take');

        $pending_data = ResourceAlbum::where('process', 2)->latest()->take($take)->get();

        $pending_data->each(function (ResourceAlbum $comic) {
            $this->info('#' . $comic->id . ' 準備下載 ' . $comic->images_count . ' 張圖片!');
            AlbumDownloadJob::dispatch($comic)->onQueue('long-running-queue');
        });
    }
}
