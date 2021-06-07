<?php

namespace App\Http\Controllers\Backend;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Backend\TagRequest;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\TagRepositoryInterface;

class TagController extends Controller
{
    private $repository;

    const STATUS_OPTIONS = [1 => '显示', -1 => '隐藏'];

    public function __construct(TagRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $data = [
            'status_options' => self::STATUS_OPTIONS,
            'tags' => $this->repository->filter($request)->paginate(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.tag.index')->with($data);
    }

    public function create()
    {
        $data = [
            'status_options' => self::STATUS_OPTIONS
        ];

        return view('backend.tag.create')->with($data);
    }

    public function store(TagRequest $request)
    {

        $request->validated();

        if (Tag::where('name' , $request->post('name'))->exists()){
            return Response::jsonError('已有相同标签');
        }
        
        $request->merge([
            'slug' => $request->post('name'),
            'queries' => 0
        ]);

        $this->repository->create($request->post());


        return Response::jsonSuccess(__('response.create.success'));
    }



    public function editable(Request $request, $field)
    {
        $data = [
            'pk' => $request->post('pk'),
            'value' => $request->post('value'),
        ];

        $validator = Validator::make($data, [
            'pk' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return Response::jsonError($validator->errors()->first(), 500);
        }

        switch ($field) {
            case 'name':
                $data = [
                    'slug' => mb_strtolower($request->post('value'), 'UTF-8'),
                    'name' => $request->post('value')
                ];

                $this->repository->update($request->post('pk') , $data);
                break;
            default:
                $this->repository->editable($request->post('pk'), $field, $request->post('value'));
        }

        return Response::jsonSuccess('数据已更新成功');
    }

    public function batch(Request $request, $action)
    {
        $ids = explode(',', $request->input('ids'));

        switch ($action) {
            case 'dismiss_book':
                $text = '解除关联的漫画';
                $tags = Tag::whereIn('id', $ids)->get();
                foreach ($tags as $tag) {
                    $tag->tagged_book()->delete();
                }
                break;
            case 'dismiss_video':
                $text = '解除关联的动画';
                $tags = Tag::whereIn('id', $ids)->get();
                foreach ($tags as $tag) {
                    $tag->tagged_video()->delete();
                }
                break;
            case 'disable':
                $text = '在前端隐藏';
                $tags = Tag::whereIn('id', $ids)->get();
                foreach ($tags as $tag) {
                    $tag->suggest = false;
                    $tag->save();
                }
                break;
            case 'enable':
                $text = '在前端显示';
                $tags = Tag::whereIn('id', $ids)->get();
                foreach ($tags as $tag) {
                    $tag->suggest = true;
                    $tag->save();
                }
                break;
            default:
                return Response::jsonError(__('response.error.unknown'));
        }

        return Response::jsonSuccess(__('response.success.complete', ['action' => $text]));
    }

    public function destroy($id)
    {
        $this->repository->destroy($id);

        return Response::jsonSuccess('删除成功！');

    }
}
