<?php

namespace App\Repositories\Contracts;

use App\Models\Content;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface ContentRepositoryInterface
{
    public function find(int $id): ?Model;

    public function filter(Request $request): Builder;

    public function troubleshoot(int $id): array;

    public function getCheckList(Content $article): array;

    public function getCheckResult(array $list): array;

    public function isExists(string $file, int $timeout): int;
}
