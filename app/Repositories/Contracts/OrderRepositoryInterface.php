<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface OrderRepositoryInterface extends RepositoryInterface
{
    public function orders_count(Request $request): int;

    // public function success_orders_count(Request $request): int;

    public function orders_amount(Request $request): string;

    public function renew_orders_count(Request $request): int;

    public function renew_orders_amount(Request $request): string;
}
