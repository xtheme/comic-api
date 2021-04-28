<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface VideoRepositoryInterface extends RepositoryInterface
{
    public function getTags(): ?Collection;

    public function getDomains(): ?Collection;
}
