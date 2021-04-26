<?php

namespace App\Repositories\Contracts;

interface OrderRepositoryInterface extends RepositoryInterface
{
    public function orders_count(): int;

    public function success_orders_count(): int;

    public function orders_amount(): string;

    public function renew_orders_count(): int;

    public function renew_orders_amount(): string;
}
