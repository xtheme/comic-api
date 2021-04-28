<?php

namespace App\Http\Controllers;

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

        return Response::jsonSuccess(__('response.upload.success'), $response);
    }
}
