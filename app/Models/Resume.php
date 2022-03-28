<?php

namespace App\Models;

use App\Traits\HasRanking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\Tags\HasTags;

/**
 * @method static active() get active records
 */
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
        'province_id',
        'city_id',
        'area_id',
    ];

    protected $appends = [
        'age'
    ];

    protected $casts = [
        'body_shape' => 'array',
        'service' => 'array',
        'contact' => 'array',
        'album' => 'array',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

    public function province(): HasOne
    {
        return $this->hasOne('App\Models\ChinaProvince', 'province_id', 'province_id');
    }

    public function city(): HasOne
    {
        return $this->hasOne('App\Models\ChinaCity', 'city_id', 'city_id');
    }

    public function area(): HasOne
    {
        return $this->hasOne('App\Models\ChinaArea', 'area_id', 'area_id');
    }

    public function getCoverAttribute($value): string
    {
        if (!$value) {
            return '';
        }

        return Storage::url($value);
    }

    public function getAlbumAttribute($value): array
    {
        if (!$value) {
            return [];
        }

        $pictures = json_decode($value);

        return collect($pictures)->map(function ($img_path) {
            return Storage::url($img_path);
        })->toArray();
    }

    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_year) {
            return null;
        }

        return Carbon::createFromFormat('Y', $this->birth_year)->age;
    }
}
