<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class UploadService
 *
 * @package App\Services
 *
 * Upload::to('book')->store($request->file('thumb'));
 * Upload::unsync()->to('book')->store($request->file('thumb')); // 本地保存
 */
class UploadService
{
    protected string $path = ''; // 上傳路徑
    protected string $rule = 'image'; // checkFile() 使用的規則

    public function to($path, $id = null): UploadService
    {
        $this->path = $this->buildPath($path, $id);

        return $this;
    }

    private function buildPath(string $dir = null, $id = null): string
    {
        $path = !empty($dir) ? trim($dir) : date('Ymd');

        if (!$id) {
            // 新增时尚无 id, 找出最后一笔 id +1
            switch ($dir) {
                case 'navigation':
                    $latest_id = DB::table($dir)->latest()->first()->id ?? 0;
                    break;
                default:
                    $latest_id = DB::table(Str::plural($dir))->latest()->first()->id ?? 0;
                    break;
            }

            $id = $latest_id + 1;
        }

        $path .= '/' . $id;

        return $path;
    }

    /**
     * @param  $file
     *
     * @return array
     */
    public function store($file): array
    {
        // 检查文件规范
        $message = $this->checkFile($file);

        if ($message) {
            return [
                'success' => false,
                'message' => $message,
            ];
        }

        $path = Storage::put($this->path, $file, 'public');

        try {
            $url = Storage::temporaryUrl($path, now()->addMinutes(10));
        } catch (\Exception $e) {
            $url = Storage::url($path);
        }

        // 不同步
        return [
            'success' => true,
            'message' => __('response.upload.success'),
            'path' => $path,
            'url' => $url,
        ];
    }

    /**
     * 检查上传文件
     *
     * @param  UploadedFile  $file
     *
     * @return string
     */
    private function checkFile(UploadedFile $file): string
    {
        if ($file->isValid()) {
            // 检查文件大小
            $size = $file->getSize();
            if ($size > config('backend.upload.image.size')) {
                $limit_size = ceil(config('backend.upload.image.size') / 1024);
                return __('response.upload.fail.too_big', ['size' => $limit_size]);
            }

            // 检查 mime type
            $mimeType = $file->getMimeType();
            $allowMimeType = config('backend.upload.image.mime_type');
            if (!in_array($mimeType, $allowMimeType)) {
                return __('response.upload.fail.mime_type');
            }

            return '';
        }

        return $file->getErrorMessage();
    }
}
