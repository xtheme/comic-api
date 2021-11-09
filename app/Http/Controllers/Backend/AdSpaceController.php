<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Options;
use App\Http\Controllers\Controller;
use App\Models\AdSpace;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdSpaceController extends Controller
{
    const CLASS_TYPE = [
        'video' => '动画',
        'book' => '漫画',
        'other' => '其他',
    ];

    const DISPLAY_TYPE = [
        1 => '单图',
        2 => '轮播',
        3 => '跑马灯',
    ];

    private function filter(Request $request): Builder
    {
        $id = $request->get('id') ?? '';
        $name = $request->get('name') ?? '';
        $class = $request->get('class') ?? '';

        $order = $request->get('order') ?? 'id';
        $sort = $request->get('sort') ?? 'DESC';

        return AdSpace::when($id, function (Builder $query, $id) {
            return $query->where('id', $id);
        })->when($name, function (Builder $query, $name) {
            return $query->where('name', $name);
        })->when($class, function (Builder $query, $class) {
            return $query->where('class', $class);
        })->when($sort, function (Builder $query, $sort) use ($order) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        });
    }

    public function index(Request $request)
    {
        $list = $this->filter($request)->paginate();

        $data = [
            'list' => $list,
            'class_type' => self::CLASS_TYPE,
            'display_type' => self::DISPLAY_TYPE,
            'status_options' => Options::STATUS_OPTIONS,
        ];

        return view('backend.ad_space.index')->with($data);
    }

    public function edit($id)
    {
        $data = [
            'space' => AdSpace::findOrFail($id),
            'class_type' => self::CLASS_TYPE,
            'display_type' => self::DISPLAY_TYPE,
            'status_options' => Options::STATUS_OPTIONS,
        ];

        return view('backend.ad_space.edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $post = $request->post();

        $data = AdSpace::findOrFail($id);

        $data->fill($post)->save();

        return Response::jsonSuccess(__('response.update.success'));
    }
}
