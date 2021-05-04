<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface BlockRepositoryInterface extends RepositoryInterface
{
    public function getTags(): ?Collection;

}
