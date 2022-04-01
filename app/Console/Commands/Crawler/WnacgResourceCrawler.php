<?php

namespace App\Console\Commands\Crawler;

use App\Models\ComicResource;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use QL\QueryList;
use ZipArchive;

/**
 * 各位紳士您好, 請先建立好 comic_resources 資料表
 * 遵循以下採集步驟, 你就可以在家快樂嚕管惹
 * 1. 採集漫畫清單 php artisan wnacg:crawler 1
 * 2. 依據現有清單採集圖片網址 php artisan wnacg:crawler 2
 * 3. 將圖片下載回本地並重新編號排序 php artisan wnacg:crawler 3
 */
class WnacgResourceCrawler extends Command
{
    protected $signature = 'wnacg:crawler {process}';

    protected $description = 'Command description';

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
            case '1':
                if ($this->confirm('開始採集漫畫清單?')) {
                    $this->crawlComicList();
                }
                break;
            case '2':
                if ($this->confirm('依據現有清單採集圖片網址?')) {
                    $this->crawlRawImages();
                }
                break;
            case '3':
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
     * Process 2
     *
     * @param  ComicResource  $comic
     * @return false|mixed
     */
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

    private function crawlRawImages()
    {
        $pending_data = ComicResource::where('process', 1)->latest()->take(1)->get();

        $pending_data->each(function (ComicResource $comic) {
            $pages = $this->getComicTotalPage($comic);
            $raw_images = [];

            $this->info('#' . $comic->id . ' 開始採集縮圖路徑, 共 ' . $pages . ' 頁!');

            $bar = $this->output->createProgressBar($pages);

            $bar->start();

            for ($i = 1; $i <= $pages; $i++) {
                $url = sprintf('%s/photos-index-page-%s-aid-%s.html', $this->source_domain, $i, $comic->source_id);

                $ql = QueryList::get($url);

                $images = $ql->find('li.gallary_item img')->attrs('src')->toArray();

                foreach ($images as $image) {
                    $raw_images[] = Str::start($image, 'https:');
                }

                $ql->destruct();

                $bar->advance();
            }

            $bar->finish();

            // Update Comic
            $update = [
                'process' => 2,
                'images_count' => count($raw_images),
                'raw_images' => $raw_images,
            ];

            $comic->update($update);

            $this->line('');
            $this->info('#' . $comic->id . ' 採集完成!');
            $this->line('');
        });
    }

    private function downloadImages()
    {
        $pending_data = ComicResource::where('process', 2)->latest()->take(1)->get();

        $pending_data->each(function (ComicResource $comic) {
            $id = $comic->id;
            $count = $comic->images_count;

            $this->info('#' . $comic->id . ' 準備下載 ' . $count . ' 張圖片!');

            // A.下載封面
            $new_cover = $this->downloadCover($comic);

            // B.下載圖片
            $new_images = $this->downloadImage($comic);

            // C.建立漫畫壓縮檔
            // $this->zipFiles($id);

            // 更新漫畫資訊
            $update = [
                'process' => 3,
                'new_cover' => $new_cover,
                'new_images' => $new_images,
            ];

            $comic->update($update);

            $this->line('');
            $this->info('#' . $id . ' 下載完成!');
            $this->line('');
        });
    }

    private function downloadCover(ComicResource $comic): string
    {
        $url = $comic->raw_cover;

        $url = preg_replace('/^(http|https):\/\/+(t)/', '$1://img', $url);
        $url = preg_replace('/\/t/', '', $url);
        $pic_url = $url;
        $this->error($pic_url);

        $extension = pathinfo($pic_url, PATHINFO_EXTENSION);
        $file_name = 'cover.' . $extension;
        $file = file_get_contents($pic_url);
        $path = sprintf('/download/%s/%s', $comic->id, $file_name);
        Storage::put($path, $file);
        return $path;
    }

    private function downloadImage(ComicResource $comic): array
    {
        $id = $comic->id;
        $list = $comic->raw_images;
        $count = $comic->images_count;

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $new_images = [];

        foreach ($list as $key => $url) {

            $url = preg_replace('/^(http|https):\/\/+(t)/', '$1://img', $url);
            $url = preg_replace('/\/t/', '', $url);
            $pic_url = $url;

            $extension = pathinfo($pic_url, PATHINFO_EXTENSION);

            $file_name = Str::padLeft($key + 1, 3, '0');
            $file_name .= '.' . $extension;

            $path = sprintf('/download/%s/%s', $id, $file_name);
            $file = file_get_contents($pic_url);
            Storage::put($path, $file);

            $new_images[] = $path;

            $bar->advance();
        }

        $bar->finish();

        return $new_images;
    }

    private function zipFiles($id)
    {
        $zip = new ZipArchive;

        $directory = sprintf('download/%s', $id);
        $fileName = sprintf('download/comic-%s.zip', $id);

        if ($zip->open(Storage::path($fileName), ZipArchive::CREATE) === true) {
            $files = Storage::files($directory);
            foreach ($files as $path) {
                $path = Storage::path($path);
                $relativeNameInZipFile = basename($path);
                $zip->addFile($path, $relativeNameInZipFile);
            }
            $zip->close();
            $this->info('建立壓縮檔: ' . Storage::path($fileName));
        } else {
            $this->error('建立壓縮檔失敗!');
        }
    }
}
