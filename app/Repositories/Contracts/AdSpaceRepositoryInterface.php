<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface AdSpaceRepositoryInterface extends RepositoryInterface
{
    public function ads(Request $request, $id): Builder;
}
