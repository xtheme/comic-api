<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Upload;

/**
 * Class UploadController
 *
 * @package App\Http\Controllers\Admin
 */
class UploadController extends Controller
{
    /**
     * 上傳文件
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

    /**
     * 刪除文件
     */
    public function unlink(Request $request)
    {
        $path = $request->input('path');

        Storage::delete($path);

        return Response::jsonSuccess(__('response.upload.success'), ['path' => $path]);
    }
}
