<?php

namespace App\Jobs;

use App\Models\ResourceAlbum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use QL\QueryList;

class AlbumCrawlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ResourceAlbum $comic;

    public function __construct(ResourceAlbum $comic)
    {
        $this->comic = $comic;
    }

    /**
     * 依據漫畫縮圖列表爬取路徑
     *
     * @return void
     */
    public function handle()
    {
        try {
            $pages = $this->getTotalPage($this->comic->source_id);

            $raw_images = $raw_thumbs = [];

            for ($i = 1; $i <= $pages; $i++) {
                $url = sprintf('https://www.wnacg.org/photos-index-page-%s-aid-%s.html', $i, $this->comic->source_id);

                $ql = QueryList::get($url);

                // 大圖
                $links = $ql->find('li.gallary_item a')->attrs('href')->toArray();
                foreach ($links as $link) {
                    $link = Str::start($link, 'https://www.wnacg.org');
                    $raw_images[] = $this->getFullImageLink($link);
                }

                // 縮圖
                $images = $ql->find('li.gallary_item img')->attrs('src')->toArray();
                foreach ($images as $image) {
                    $raw_images[] = Str::start($image, 'https:');
                }

                $ql->destruct();
            }

            // Update Comic
            $update = [
                'process' => 2,
                'images_count' => count($raw_images),
                'raw_thumbs' => $raw_thumbs,
                'raw_images' => $raw_images,
            ];

            $this->comic->update($update);
        } catch (\Exception $exception) {
            // 更新漫畫資訊
            $update = [
                'process' => 4,
            ];

            $this->comic->update($update);
        }
    }

    /**
     * 漫畫縮圖列表總頁數
     *
     * @param  int  $source_id
     * @return false|mixed
     */
    private function getTotalPage(int $source_id)
    {
        $url = sprintf('https://www.wnacg.org/photos-index-aid-%s.html', $source_id);

        $ql = QueryList::get($url);

        $paginator = $ql->find('.paginator>a')->texts()->toArray();

        $last_page = end($paginator);

        $ql->destruct();

        return $last_page;
    }

    private function getFullImageLink(string $url): string
    {
        // https://www.wnacg.org/photos-view-id-xxx.html
        $ql = QueryList::get($url);

        $link = $ql->find('#imgarea #picarea')->attr('src');

        $link = Str::start($link, 'https://www.wnacg.org');

        $ql->destruct();

        return $link;
    }
}