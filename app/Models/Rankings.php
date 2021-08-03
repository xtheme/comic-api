<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rankings extends Model
{
    use HasFactory;
    
    public function book()
    {
        return $this->hasOne('App\Models\Book', 'id', 'book_id');
    }

    public function last_chapters()
    {
        return $this->hasOne('App\Models\ViewsLatestChapter' , 'book_id', 'book_id');
    }
}
