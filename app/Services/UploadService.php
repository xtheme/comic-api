<?php

namespace App\Services;

use App\Models\Book;
use App\Models\User;
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
    protected $sync = true; // 同步到第三方服務器
    protected $rule = 'image'; // checkFile() 使用的規則

    public function __construct()
    {
        if (App::environment('local')) {
            $this->sync = false;
        }
    }

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
                case 'avatar':
                    $id = User::latest()->first()->id + 1;
                    break;
                case 'book':
                    $id = Book::latest()->first()->id + 1;
                    break;
                default:
                    $latest_id = DB::table(Str::plural($dir))->latest()->first()->id ?? 0;
                    $id = $latest_id + 1;
                    break;
            }
        }

        $path .= '/' . $id;

        return $path;
    }

    /**
     * 指定一个文件名, 存储文件
     *
     * @param $file
     * @param $path
     *
     * @return mixed
     */
    private function storeToLocal($file, $path)
    {
        // 获取后缀名
        $extension = $file->extension();

        // 生成文件路径
        $filename = uniqid() . '.' . $extension;

        return $file->storeAs($path, $filename);
    }

    /**
     * 同步本地文件到文件服务器
     *
     * @param $file
     *
     * @return array
     */
    private function syncToFileServer($file): array
    {
        $url = config('api.third.upload_url') . '/upload';

        $post_data = [
            'image' => new CURLFile($file),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($output, true);

        if ($result['code'] != 200) {
            Log::error('syncToFileServer error : ' . $output);
            return [];
        }

        Log::debug('syncToFileServer output : ' . $output);
        return $result['data'];
    }

    public function unsync()
    {
        $this->sync = false;

        return $this;
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

        // 上传文件到本地
        $path = $this->storeToLocal($file, $this->path);

        if (!$this->sync) {
            // 不同步
            $result = [
                'success' => true,
                'message' => __('response.upload.success'),
                'path' => '/storage/' . $path,
                'domain' => '',
            ];
            return $result;
        }

        // 本地真实路径
        $local_path = Storage::path($path);

        // 同步本地文件到文件服务器
        $response = $this->syncToFileServer($local_path);

        if (!$response) {
            $result = [
                'success' => false,
                'message' => __('response.upload.fail.sync'),
            ];
            return $result;
        }

        // 删除本地文件
        // Storage::delete($path);
        Storage::deleteDirectory($this->path);

        $api_url = getOldConfig('web_config', 'api_url');

        if (Str::endsWith($api_url, '/')) {
            $api_url = substr($api_url, 0, -1);
        }

        $result = [
            'success' => true,
            'message' => __('response.upload.success'),
            'path' => $response['image_path'],
            'domain' => $api_url, // @todo 切换配置, CDN 域名 https://uatoriginalmanhuapic.ngxs9.app/
        ];
        return $result;
    }
}
