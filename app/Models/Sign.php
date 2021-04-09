<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sign extends Model
{

    protected $table = 'sign';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'days',
        'addtime'
    ];


    public function user()
    {
        return $this->hasOne('App\Models\User' , 'id', 'uid');
    }


}
