<?php

namespace App\Repositories;

use App\Models\VideoDomain;
use App\Repositories\Contracts\VideoDomainRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class VideoDomainRepository extends Repository implements VideoDomainRepositoryInterface
{
    /**
     * @return string
     */
    public function model(): string
    {
        return VideoDomain::class;
    }

    /**
     * 查询DB
     *
     * @param  Request  $request
     *
     * @return Builder
     */
    public function filter(Request $request): Builder
    {
        $domain = $request->get('domain') ?? '';
        $status = $request->get('status') ?? '';

        $order = $request->get('order') ?? 'created_at';
        $sort = $request->get('sort') ?? 'DESC';

        return $this->model::when($domain, function (Builder $query, $domain) {
            return $query->where('domain', 'like', '%'.$domain.'%')
                ->orWhere('encrypt_domain', 'like', '%'.$domain.'%');
        })->when($status, function (Builder $query, $status) {
            return $query->where('status', $status);
        })->when($sort, function (Builder $query, $sort) use ($order) {
            if ($sort == 'desc') {
                return $query->orderByDesc($order);
            } else {
                return $query->orderBy($order);
            }
        });
    }
}
