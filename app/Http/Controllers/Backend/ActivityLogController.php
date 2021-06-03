<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use App\Models\Admin;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        // 获取表单
        $id = $request->get('id') ?? '';
        $log_name = $request->get('log_name') ?? '';
        $causer_id = $request->get('causer_id') ?? '';
        $created_at = $request->get('created_at') ?? '';

        // 查询日志
        $logs = ActivityLog::when($id, function (Builder $query, $id) {
            return $query->where('id', $id)->orWhere('subject_id', $id);
        })->when($log_name, function (Builder $query, $log_name) {
            return $query->inLog($log_name);
        })->when($causer_id, function (Builder $query, $causer_id) {
            return $query->where('causer_id' , $causer_id);
        })->when($created_at, function (Builder $query, $created_at) {
            $date = explode(' - ', $created_at);
            $start_date = $date[0];
            $end_date = $date[1];
            return $query->whereBetween('created_at', [
                $start_date,
                $end_date
            ]);
        })->orderByDesc('id')->paginate(config('custom.perpage'));
        // dd($logs);
        // 列出所有的日志名称
        $name_options = ActivityLog::groupBy('log_name')->pluck('log_name');

        // 列出所有的操作者
        $causer = ActivityLog::whereNotNull('causer_id')->groupBy('causer_id')->pluck('causer_id');
        $admin_options = $causer->map(function ($causer_id) {
            return Admin::find($causer_id);
        });

        // dd($activity);

        return view('backend.activity.index', [
                'logs' => $logs,
                'name_options' => $name_options,
                'admin_options' => $admin_options,
                'pageConfigs' => ['hasSearchForm' => true],
            ]
        );
    }

    /**
     * 显示新旧数据差异
     *
     * @param $id
     *
     * @return View
     */
    public function diff($id) {
        $activity = ActivityLog::findOrFail($id);

        return view('backend.activity.show', [
                'changed' => $activity->getChanged(),
            ]
        );
    }

    /**
     * 还原旧数据
     *
     * @param $id
     *
     * @return Response
     */
    public function restore($id) {
        $activity = ActivityLog::findOrFail($id);

        $model = $activity->subject;
        $changed = $activity->getChanged();
        foreach ($changed as $row) {
            $model->{$row['field']} = $row['old'];
        }
        $model->save();

        return Response::jsonSuccess('数据恢复成功');
    }
}
