<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ImageService;
use CURLFile;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Response;

/**
 * Class UploadController
 *
 * @package App\Http\Controllers\Admin
 */
class UploadController extends Controller
{
    private $imageService;

    /**
     * @param  ImageService  $imageService
     */
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
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

    /**
     * 获取存储路径
     *
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
     * 存储文件
     *
     * @param $file
     * @param $path
     *
     * @return
     */
    private function storeFile(UploadedFile $file, $path)
    {
        // 获取后缀名
        $extension = $file->extension();

        // 生成文件路径
        $filename = uniqid() . '.' . $extension;

        return '/storage/' . $file->storeAs($path, $filename);
    }

    /**
     * 后台图片上传
     *
     * @param Request $request
     * @param string|null $dir
     * @param string|null $id
     *
     * @return Response
     */
    public function upload(Request $request, string $dir = null, string $id = null)
    {
        $file = $request->file('image');

        $result = $this->imageService->uploadFile($file, $dir, $id);

        $result = explode('|', $result);

        $status = ($result[0] == 'error') ? 500 : 200;

        $message = $result[1];

        if ($status === 200) {
            return Response::jsonSuccess('上传成功', [
                'filename' => $message ,
                'filename_thumb' => getConfig('api_url') . $message
            ]);
        }

        return Response::jsonError($message, $status);
    }

    /**
     * 富文本上传图片
     *
     * @param Request $request
     * @param string|null $dir
     * @param string|null $id
     *
     * @return string
     */
    public function editorUpload(Request $request, string $dir = null, string $id = null)
    {
        $file = $request->file('upload');

        $message = $this->checkFile($file);

        if ($message) {
            $result = [
                'error' => [
                    'message' => $message
                ]
            ];
            return json_encode($result);
        }

        $path = $this->getFilePath($dir, $id);

        $absolute_path = $this->storeFile($file, $path);

        $result = [
            'url' => $absolute_path
        ];

        return json_encode($result);
    }
}
