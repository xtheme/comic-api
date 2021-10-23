<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Filter extends BaseModel
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'params' => 'array',
    ];

    public function buildQuery(): Builder
    {
        $class = sprintf('App\Models\%s', Str::ucfirst($this->type));

        $model = new $class;

        $query = $model::query()->where('status', 1);

        // 標籤條件
        foreach ($this->tags as $type => $tags) {
            $query->withAllTags($tags, $type);
        }

        // 查詢條件
        foreach ($this->params as $field => $value) {
            if (!$value) continue;

            switch ($field) {
                case 'title':
                    $query->whereLike('author', $value);
                    break;
                case 'author':
                    $query->whereLike('author', $value);
                    break;
                case 'end':
                    // 漫畫才有此欄位
                    $query->where('end', 1);
                    break;
                case 'order_by':
                    $query->latest($value);
                    break;
                case 'date_between':
                    $date = explode(' - ', $value);
                    $start_date = $date[0] . ' 00:00:00';
                    $end_date = $date[1] . ' 23:59:59';
                    $query->whereBetween('created_at', [
                        $start_date,
                        $end_date,
                    ]);
                    break;
            }
        }
        // Log::debug($query->toSql());
        return $query;
    }

    public function getQueryCountAttribute(): int
    {
        return $this->buildQuery()->count();
    }
}
