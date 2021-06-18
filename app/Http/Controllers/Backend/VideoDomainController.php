<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\VideoDomainRequest;
use App\Models\VideoDomain;
use App\Models\VideoSeries;
use App\Repositories\Contracts\VideoDomainRepositoryInterface;
use Illuminate\Http\Request;
use Response;
use Validator;

class VideoDomainController extends Controller
{
    private $repository;

    const STATUS = [1 => '启用', -1 => '禁用'];

    public function __construct(VideoDomainRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $data = [
            'status_options' => self::STATUS,
            'domains' => $this->repository->filter($request)->paginate(),
            'pageConfigs' => ['hasSearchForm' => true],
        ];

        return view('backend.video_domain.index')->with($data);
    }

    public function create()
    {
        $data = [
            'status_options' => self::STATUS,
        ];

        return view('backend.video_domain.create')->with($data);
    }

    public function store(VideoDomainRequest $request)
    {
        $validated = $request->post();

        $this->repository->create($validated);

        return Response::jsonSuccess(__('response.create.success'));
    }

    public function edit($id)
    {
        $data = [
            'status_options' => self::STATUS,
            'domain' => $this->repository->find($id),
        ];

        return view('backend.video_domain.edit')->with($data);
    }

    public function update(VideoDomainRequest $request, $id)
    {
        // 域名不該有 "/" 結尾
        $request->merge([
            'domain' => rtrim($request->post('domain'), '/'),
            'encrypt_domain' => rtrim($request->post('encrypt_domain'), '/'),
        ]);

        if ($request->input('status') == -1) {
            $domain = $this->repository->find($id);
            if ($domain->series->count()) {
                return Response::jsonError('请先转移关联动画的域名再禁用！');
            }
        }

        $this->repository->update($id, $request->post());

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function series(Request $request, $id)
    {
        $domain = $this->repository->find($id);

        $data = [
            'id' => $id,
            'domains' => VideoDomain::where('status', 1)->where('id', '!=', $id)->get(),
            'list' => $domain->series()->paginate(),
        ];

        return view('backend.video_domain.series')->with($data);
    }

    /**
     * 批次更新
     */
    public function change_domain(Request $request)
    {
        $ids = explode(',', $request->post('ids'));

        VideoSeries::whereIn('id', $ids)->update(['video_domain_id' => $request->post('domain_id')]);

        return Response::jsonSuccess('选择的影集已变更域名！');
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

        return Response::jsonSuccess(__('response.update.success'));
    }

    public function destroy($id)
    {
        $this->repository->destroy($id);

        return Response::jsonSuccess(__('response.destroy.success'));
    }
}
