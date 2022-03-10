<?php

namespace App\Models;

use App\Traits\HasRanking;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class Resume extends Model
{
    use HasFactory, HasTags, HasRanking;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'agent_type',
        'agent_id',
    ];

    protected $casts = [
        'body_shape' => 'array',
        'service' => 'array',
        'contact' => 'array',
        'album' => 'array',
    ];

    public function getAlbumAttribute($value)
    {
        if (!$value) {
            return [];
        }

        $pictures = json_decode($value);

        return collect($pictures)->map(function ($img_path) {
            return config('api.resume.img_domain') . $img_path;
        });
    }
}
