<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Block extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'sort',
        'spotlight',
        'row',
        'causer',
        'properties',
        'status',
    ];


    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * 写入动画跟漫画的种类别
     */
    public function setCauserAttribute($value)
    {
        $this->attributes['causer'] = sprintf('App\Models\%s', Str::ucfirst($value));
    }

    /**
     * json_encode 后写入特性条件
     */
    public function setPropertiesAttribute($properties)
    {
        //tags特性額外處理空值
        if (!isset($properties['tag']['value'])){
            $properties['tag']['value'] = [];
        }

        $this->attributes['properties'] = json_encode($properties);
    }

    public function getQueryUrlAttribute()
    {
        switch ($this->causer) {
            case 'App\Models\Video':
                $route = route('backend.video.index');
                break;
            case 'App\Models\Book':
                $route = route('backend.book.index');
                break;
            default:
                $route = '';
                break;
        }

        $arr = [];

        foreach ($this->properties as $property => $data) {
            $arr[$property] = $data['value'];
        }

        return urldecode($route . '?' . http_build_query($arr));
    }

    public function buildQuery(): Builder
    {
        $model = new $this->causer;

        $query = $model::query();

        foreach ($this->properties as $key => $data) {
            $value = $data['value'] ?? null;

            if (!$value) {
                continue;
            }

            switch ($key) {
                case 'tag':
                    $query->withAllTags($value);
                    break;
                case 'limit':
                    $query->limit($value);
                    break;
                case 'order':
                    $query->orderByDesc($value);
                    break;
                case 'author':
                    $query->where('author', $value);
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

        return $query;
    }

    public function getQueryCountAttribute(): int
    {
        return $this->buildQuery()->count();
    }

    public function getQueryResultAttribute(): Collection
    {
        return $this->buildQuery()->get();
    }

    public function getStyleAliasAttribute()
    {
        if (!$this->spotlight) {
            return sprintf('一排%s个', $this->row);
        }

        return sprintf('%s大%s小', $this->spotlight, $this->row);
    }
}
