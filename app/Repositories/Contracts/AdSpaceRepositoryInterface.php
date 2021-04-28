<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface AdSpaceRepositoryInterface extends RepositoryInterface
{
    public function getAdList($name): Builder;
}
