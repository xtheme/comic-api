<?php

namespace App\Repositories;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

abstract class Repository implements RepositoryInterface
{
    protected $cache_ttl;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @return string
     */
    abstract public function model(): string;

    /**
     * Instantiate a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = app($this->model());
    }

    /**
     * @param  int  $id
     *
     * @return Model
     */
    public function find(int $id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Redis key
     *
     * @return string
     */
    public function cacheKey($str)
    {
        return sprintf('%s:%s', $this->model->getTable(), $str);
    }

    /**
     * 查询缓存
     *
     * @param  Request  $request
     *
     * @return LengthAwarePaginator
     */
    public function cacheFilter(Request $request): LengthAwarePaginator
    {
        $cache_key = $this->cacheKey(md5($request->getQueryString()));

        return Cache::remember($cache_key, $this->cache_ttl, function () use ($request){
            return $this->filter($request)->paginate(config('custom.perpage'));
        });
    }
}
