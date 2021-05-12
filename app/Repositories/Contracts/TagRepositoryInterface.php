<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface TagRepositoryInterface extends RepositoryInterface
{
    public function all(): ?Collection;
}
