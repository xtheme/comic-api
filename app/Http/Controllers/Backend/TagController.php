<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryInterface;
use Conner\Tagging\Model\Tagged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class TagController extends Controller
{
    private $repository;

    const STATUS_OPTIONS = [1 => '推荐', -1 => '隐藏'];

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

        $this->repository->editable($request->post('pk'), $field, $request->post('value'));

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
            default:
                return Response::jsonError(__('response.error.unknown'));
        }

        return Response::jsonSuccess(__('response.success.complete', ['action' => $text]));
    }
}
