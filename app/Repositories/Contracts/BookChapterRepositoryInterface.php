<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface BookChapterRepositoryInterface
{
    public function find(int $id): ?Model;

    public function filter(Request $request): Builder;

    public function editable($id, $field, $value);
}
