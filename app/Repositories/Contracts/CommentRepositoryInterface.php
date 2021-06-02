<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface CommentRepositoryInterface extends RepositoryInterface
{
    public function list($chapter_id , $order): Builder;
}
