<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface VideoSeriesRepositoryInterface extends RepositoryInterface
{
    public function getDomains(): ?Collection;
}
