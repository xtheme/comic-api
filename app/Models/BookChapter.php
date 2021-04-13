<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookChapter extends Model
{
    use HasFactory;

    protected $table = 'chapterlist';

    public function book()
    {
        return $this->hasOne('App\Models\Book', 'id', 'book_id');
    }


}
