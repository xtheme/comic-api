<?php

namespace App\Services;

use App\Models\User;
use CURLFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageService
{

    /**
     * 圖片上傳url
     */
    private $img_upload_url;


    /**
     * 图片原始路径预设
     */
    private $storage;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->img_upload_url = env('URL_IMG_UPLOAD');
        $this->storage = base_path('storage/app/public') ;
    }

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
            $local_filename = '/uploads/' . uniqid() . '.' . $ext;

            // 上传文件
            $success = $this->storagePut($local_filename, file_get_contents($realPath));

            if (!$success){
                return 'error|' . $file->getErrorMessage();
            }

            //传送到图片服务器
            $filename = $this->curlImageUpload($this->storage.  $local_filename);

            if (!$filename) {
                return 'error|curlImageUpload error';
            }

            //图片传送完成 删除本地图片
            $this->storageDelete($local_filename);

            return 'success|' . $filename;


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
                case 'avatar':
                    $id = User::latest()->first()->id + 1;
                    break;
            }
        }

        $path .= '/' . $id;

        return $path;
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

                return sprintf('文件不能大于 %s kb！', $limit_size);
            }

            // 检查 mime type
            $mimeType = $file->getMimeType();
            $allowMimeType = config('custom.upload.image.mime_type');
            if (!in_array($mimeType, $allowMimeType)) {
                return '文件类型不支持！';
            }

            // 16进制文件检查，防止图片恶意代码
            if (!checkHex($file)) {
                return '你所上传的图片可能藏有恶意代码，请通报资安人员处理！';
            }

            return '';
        }

        return $file->getErrorMessage();
    }

    function curlImageUpload($file)
    {
        $url = $this->img_upload_url . "/upload";

        $post_data = [
            "image" => new CURLFile($file),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($output, true);
        if ($result['code'] == 200) {
            return $result['data']['image_path'];
        } else {
            Log::error('curlImageUpload error : ' . json_encode($result));
            return false;
        }
    }

    /**
     * 放入本地圖片
     *
     * @param $path
     * @param $content
     *
     * @return bool
     */
    public function storagePut($path, $content)
    {
        return Storage::put($path, $content);
    }

    /**
     * 刪除本地圖片
     *
     * @param $path
     * @param $content
     *
     * @return bool
     */
    public function storageDelete($local_file)
    {
        if (Storage::exists($local_file)) {
            Storage::delete($local_file);
        }
    }
}
