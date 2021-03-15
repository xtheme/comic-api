<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Http\Request;
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
                'filename' => $message
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

        $result = $this->imageService->uploadFile($file, $dir, $id);

        $arr = explode('|', $result);

        if ($arr[0] === 'success') {
            $result = [
                'url' => $arr[1]
            ];
        } else {
            $result = [
                'error' => [
                    'message' => $arr[1]
                ]
            ];
        }

        return json_encode($result);
    }
}
