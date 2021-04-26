<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface OrderRepositoryInterface
{
    public function find(int $id): ?Model;

    public function create(array $input): ?Model;

    public function update($id, array $input): bool;

    public function filter(Request $request): Builder;

    public function orders_count(): int;

    public function success_orders_count(): int;

    public function orders_amount(): string;

    public function renew_orders_count(): int;

    public function renew_orders_amount(): string;
}
