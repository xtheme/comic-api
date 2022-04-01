<?php

namespace App\Console\Commands\Resource;

use App\Jobs\ComicCrawlJob;
use App\Jobs\ComicDownloadJob;
use App\Models\ComicResource;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use QL\QueryList;

/**
 * 各位紳士您好, 請先建立好 comic_resources 資料表
 * 遵循以下採集步驟, 你就可以在家快樂嚕管惹
 * 1. 採集漫畫清單 php artisan comic:wnacg update
 * 2. 依據現有清單採集圖片網址 php artisan comic:wnacg crawler
 * 3. 將圖片下載回本地並重新編號排序 php artisan comic:wnacg download
 */
class WnacgCommand extends Command
{
    protected $signature = 'comic:wnacg {process? : 流程} {--take=20 : 批次數量}';

    protected $description = '紳士們';

    protected string $source_platform = 'wnacg'; // 類別: 漢化單行本
    protected string $source_domain = 'https://www.wnacg.org'; // 類別: 漢化單行本
    protected int $category = 9; // 類別: 漢化單行本
    protected string $type = 'jp'; // 日漫
    protected int $translate = 1; // 中文翻译

    public function handle()
    {
        $process = $this->argument('process');

        $this->info($process);

        switch ($process) {
            case 'update':
                if ($this->confirm('開始採集漫畫清單?')) {
                    $this->updateComicList();
                }
                break;
            case 'crawler':
                if ($this->confirm('依據現有清單採集圖片網址?')) {
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
    private function updateComicList()
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
                $cover = Str::start($row['cover'], 'https:');
                $insert[] = [
                    'source_platform' => $this->source_platform,
                    'source_id' => $source_id,
                    'type' => $this->type,
                    'translate' => $this->translate,
                    'title' => $row['text'],
                    'raw_cover' => $cover,
                    'process' => 1,
                ];
            }

            $insert = array_reverse($insert);

            ComicResource::upsert($insert, ['source_platform', 'source_id']);

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

        $pending_data = ComicResource::where('process', 1)->latest()->take($take)->get();

        $pending_data->each(function (ComicResource $comic) {
            $this->info('#' . $comic->id . ' 開始採集縮圖路徑!');
            ComicCrawlJob::dispatch($comic)->onQueue('long-running-queue');
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

        $pending_data = ComicResource::where('process', 2)->latest()->take($take)->get();

        $pending_data->each(function (ComicResource $comic) {
            $this->info('#' . $comic->id . ' 準備下載 ' . $comic->images_count . ' 張圖片!');
            ComicDownloadJob::dispatch($comic)->onQueue('long-running-queue');
        });
    }
}
