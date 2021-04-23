<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface VideoSeriesRepositoryInterface
{
    public function find(int $id): ?Model;

    public function filter(Request $request): Builder;

    public function editable($id, $field, $value);
}
