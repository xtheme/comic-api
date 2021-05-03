<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\AdSpaceRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AdController extends Controller
{

    private $repository;

    public function __construct(AdSpaceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAdList(Request $request)
    {

        $name = $request->post('name');
        // 验证规则
        $validator = Validator::make([
            'name' => $name,
        ], [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::jsonError($validator->errors()->first(), 500);
        }

        $data = $this->repository->getAdList($name)->get();


        return Response::jsonSuccess('返回成功', $data);

    }
}
