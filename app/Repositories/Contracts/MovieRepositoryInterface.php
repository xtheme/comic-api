<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface MovieRepositoryInterface extends RepositoryInterface
{
    public function format(Model $video): array;

    public function collectFormat(Collection $collect): array;
}
