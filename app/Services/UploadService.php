<?php

namespace App\Services;

use CURLFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    protected $path = null; // 上傳路徑
    protected $rule = 'image'; // checkFile() 使用的規則

    /**
     * 检查上传文件
     *
     * @param  UploadedFile  $file
     *
     * @return string
     */
    private function checkFile(UploadedFile $file)
    {
        if ($file->isValid()) {
            // 检查文件大小
            $size = $file->getSize();
            if ($size > config('custom.upload.image.size')) {
                $limit_size = ceil(config('custom.upload.image.size') / 1024);
                return __('response.upload.fail.too_big', ['size' => $limit_size]);
            }

            // 检查 mime type
            $mimeType = $file->getMimeType();
            $allowMimeType = config('custom.upload.image.mime_type');
            if (!in_array($mimeType, $allowMimeType)) {
                return __('response.upload.fail.mime_type');
            }

            // 16进制文件检查，防止图片恶意代码
            if (!checkHex($file)) {
                return __('response.upload.fail.script_find');

            }

            return '';
        }

        return $file->getErrorMessage();
    }

    private function buildPath(string $dir = null, $id = null)
    {
        $path = !is_null($dir) ? trim($dir) : date('Ymd');

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

    public function to($path, $id = null)
    {
        $this->path = $this->buildPath($path, $id);

        return $this;
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
            $result = [
                'success' => false,
                'message' => $message,
            ];
            return $result;
        }

        $path = Storage::put($this->path, $file);

        // $path = Storage::url($path);

        // 不同步
        $result = [
            'success' => true,
            'message' => __('response.upload.success'),
            'path' => $path,
            'domain' => '',
        ];

        return $result;
    }
}
