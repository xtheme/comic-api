<?php

namespace App\Repositories\Contracts;


interface HistoryRepositoryInterface extends RepositoryInterface
{
    public function log(array $input);
}
