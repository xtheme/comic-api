<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Activity
{
    /**
     * 检查该记录是否包含数据异动
     * @return bool
     */
    public function isChanged() {
        $props = $this->changes();

        if ($props == '[]') {
            return false;
        }

        if (!isset($props['old'])) {
            return false;
        }

        return true;
    }

    /**
     * 获取异动的数据
     * @return array
     */
    public function getChanged() {
        $props = $this->changes();

        $arr = [];
        foreach ($props['attributes'] as $k => $v) {
            if ($v != $props['old'][$k]) {
                $arr[] = [
                    'field' => $k,
                    'new' => is_array($v) ? join(',', $v) : $v,
                    'old' => is_array($props['old'][$k]) ? join(',', $props['old'][$k]) : $props['old'][$k],
                ];
            }
        }

        return $arr;
    }
}
