<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface RepositoryInterface
{
    public function find($id);

    public function create(array $input);

    public function update($id, array $input): bool;

    public function filter(Request $request): Builder;

    public function editable($id, $field, $value);
}
