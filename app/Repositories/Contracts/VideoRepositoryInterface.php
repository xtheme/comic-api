<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface VideoRepositoryInterface extends RepositoryInterface
{
    public function random(int $limit): Collection;
}
