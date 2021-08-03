<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewsRanking extends Model
{
    use HasFactory;

    public function book()
    {
        return $this->hasOne('App\Models\Book', 'id', 'book_id');
    }

}
