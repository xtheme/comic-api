<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface TagRepositoryInterface extends RepositoryInterface
{
    public function all(): ?Collection;

    public function update($id, array $input): bool;
}
