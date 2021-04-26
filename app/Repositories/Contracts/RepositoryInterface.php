<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function find($id): ?Model;

    public function create(array $input): ?Model;

    public function update($id, array $input): bool;

    public function editable($id, $field, $value);
}
