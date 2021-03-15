<?php

namespace App\Services;

use App\Models\AdminConfig;
use App\Models\BootstrapAds;
use App\Models\ClientUser;
use App\Models\Content;
use App\Models\ContentSeries;
use App\Models\PopupAds;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Handle all content logic
 */
class ImageService
{
    /**
     * @param UploadedFile $file
     * @param string|null  $dir
     * @param string|null  $id
     *
     * @return string
     */
    public function uploadFile(UploadedFile $file, string $dir = null, string $id = null)
    {
        if ($file->isValid()) {

            // 检查文件大小
            $size = $file->getSize();
            if ($size > config('custom.upload.image.size')) {
                return sprintf('error|文件不能大于 %s M！', ceil(config('custom.upload.image.size') / (1024 * 1024)));
            }

            // 检查 mime type
            $mimeType = $file->getMimeType();
            $allowMimeType = config('custom.upload.image.mime_type');
            if (!in_array($mimeType, $allowMimeType)) {
                return 'error|文件类型不支持！';
            }

            // 16进制文件检查，防止图片恶意代码
            if (!checkHex($file)) {
                return 'error|你所上传的图片可能藏有恶意代码, 请通报资安人员处理！';
            }

            // 临时文件的绝对路径
            $realPath = $file->getRealPath();

            // 获取后缀名
            $ext = $file->getClientOriginalExtension();

            // 生成文件路径
            $filename = '/uploads/' . $this->getFilePath($dir, $id) . '/' . uniqid() . '.' . $ext;

            // 上传文件
            $success = $this->storagePut($filename, file_get_contents($realPath));

            if ($success) {
                return 'success|' . $filename;
            }
        }

        return 'error|' . $file->getErrorMessage();
    }

    /**
     * @param string|null $dir
     * @param string|null $id
     * @return string
     */
    private function getFilePath(string $dir = null, string $id = null)
    {
        $path = !is_null($dir) ? trim($dir) : date('Ymd');

        if (!$id) {
            // 新增时尚无 id, 找出最后一笔 id +1
            switch ($dir) {
                case 'article':
                case 'thumb':
                    $id = Content::withTrashed()->latest()->first()->id + 1;
                    break;
                case 'series':
                    $id = ContentSeries::latest()->first()->id + 1;
                    break;
                case 'config':
                    $id = AdminConfig::latest()->first()->id + 1;
                    break;
                case 'popup':
                    $id = PopupAds::latest()->first()->id + 1;
                    break;
                case 'bootstrap':
                    $id = BootstrapAds::latest()->first()->id + 1;
                    break;
                case 'avatar':
                    $id = ClientUser::latest()->first()->id + 1;
                    break;
            }
        }

        $path .= '/' . $id;

        return $path;
    }

    /**
     * Put content into storage, if given $path conflict, Storage facade will replace with new one
     *
     * @param $path
     * @param $content
     *
     * @return bool
     */
    public function storagePut($path, $content): bool
    {
        return Storage::put($path, $content);
    }
}
