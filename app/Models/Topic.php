<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Topic extends BaseModel
{
    protected $unlimited = false;

    protected $fillable = [
        'title',
        'sort',
        'spotlight',
        'row',
        'causer',
        'properties',
        'status',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function setUnlimited()
    {
        $this->unlimited = true;

        return $this;
    }

    public function getQueryUrlAttribute()
    {
        switch ($this->causer) {
            case 'video':
                $route = route('backend.video.index');
                break;
            case 'book':
                $route = route('backend.book.index');
                break;
            default:
                $route = '';
                break;
        }

        return urldecode($route . '?' . http_build_query($this->properties));
    }

    public function buildQuery(): Builder
    {
        $causer = sprintf('App\Models\%s', Str::ucfirst($this->causer));

        $model = new $causer;

        $query = $model::query();

        switch ($this->causer) {
            case 'video':
                $query->withCount(['visit_histories' , 'play_histories'])->where('status', 1);
                break;
            case 'book':
                $query->withCount(['visit_histories', 'favorite_histories'])->where('status', 1);
                break;
        }

        foreach ($this->properties as $key => $value) {

            if (!$value) {
                continue;
            }

            switch ($key) {
                case 'tag':
                    $query->withAllTags($value);
                    break;
                case 'limit':
                    if (!$this->unlimited) {
                        $query->limit($value);
                    }
                    break;
                case 'order':
                    // $query->orderByDesc($value);
                    $query->latest();
                    break;
                case 'author':
                    $query->where('author', $value);
                    break;
                case 'ribbon':
                    if ($this->causer == 'video') {
                        $query->where('ribbon', $value);
                    }
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
