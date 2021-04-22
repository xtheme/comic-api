<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Upload;

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

        $response = Upload::to($dir, $id)->store($file);

        if (!$response['success']) {
            return Response::jsonError($response['message'], 500);
        }

        return Response::jsonSuccess('上传成功', [
            'filename' => $response['path']
        ]);
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
    /*public function editorUpload(Request $request, string $dir = null, string $id = null)
    {
        $file = $request->file('upload');

        $message = $this->imageService->checkFile($file);

        if ($message) {
            $result = [
                'error' => [
                    'message' => $message
                ]
            ];
            return json_encode($result);
        }

        $path = $this->imageService->buildPath($dir, $id);

        $absolute_path = $this->imageService->storeFile($file, $path);

        $result = [
            'url' => '/storage/' . $absolute_path
        ];

        return json_encode($result);
    }*/
}
