<?php

namespace App\Jobs;

use App\Models\ResourceAlbum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class AlbumDownloadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ResourceAlbum $album;

    public function __construct(ResourceAlbum $album)
    {
        $this->album = $album;
    }

    public function handle()
    {
        try {
            // A.下載封面
            $new_cover = $this->downloadCover($this->album);

            // B.下載圖片
            $new_images = $this->downloadImage($this->album);

            // C.建立漫畫壓縮檔
            // $this->zipFiles($this->album->id);

            // 更新漫畫資訊
            $update = [
                'process' => 3,
                'new_cover' => $new_cover,
                'new_images' => $new_images,
            ];

            $this->album->update($update);
        } catch (\Exception $exception) {
            // 更新漫畫資訊
            $update = [
                'process' => 4,
            ];

            $this->album->update($update);
        }
    }

    /**
     * 將縮圖網址替換為原圖網址
     *
     * @param $url
     * @return string
     */
    private function replaceImageUrl($url): string
    {
        $url = preg_replace('/^(http|https):\/\/+(t)/', '$1://img', $url);
        return preg_replace('/\/t/', '', $url);
    }

    /**
     * 下載封面圖
     *
     * @param  ResourceAlbum  $album
     * @return string
     */
    private function downloadCover(ResourceAlbum $album): string
    {
        $url = $album->raw_cover;
        $pic_url = $this->replaceImageUrl($url);

        $extension = pathinfo($pic_url, PATHINFO_EXTENSION);
        $file_name = 'cover.' . $extension;
        $path = sprintf('/download/%s/%s', $album->id, $file_name);

        if (!Storage::exists($path)) {
            $file = file_get_contents($pic_url);
            Storage::put($path, $file);
        }

        return $path;
    }

    /**
     * 下載整本漫畫
     *
     * @param  ResourceAlbum  $album
     * @return array
     */
    private function downloadImage(ResourceAlbum $album): array
    {
        $id = $album->id;
        $list = $album->raw_images;

        $new_images = [];

        foreach ($list as $key => $url) {
            // $url = $this->replaceImageUrl($url);

            $extension = pathinfo($url, PATHINFO_EXTENSION);

            $file_name = Str::padLeft($key + 1, 3, '0');
            $file_name .= '.' . $extension;

            $path = sprintf('/download/%s/%s', $id, $file_name);

            if (!Storage::exists($path)) {
                $file = file_get_contents($url);
                Storage::put($path, $file);
            }

            $new_images[] = $path;
        }

        return $new_images;
    }

    /**
     * 打包漫畫壓縮檔
     *
     * @param $id
     * @return void
     */
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

            Log::error(sprintf('#%s 打包壓縮檔成功: %s!', $id, Storage::path($fileName)));
        } else {
            Log::error(sprintf('#%s 打包壓縮檔失敗!', $id));
        }
    }
}
