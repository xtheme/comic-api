<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\Config
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $type
 * @property string|null $code
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @method static Builder|Config keyword(?string $keyword = null)
 * @method static Builder|Config newModelQuery()
 * @method static Builder|Config newQuery()
 * @method static Builder|Config query()
 * @method static Builder|Config whereCode($value)
 * @method static Builder|Config whereContent($value)
 * @method static Builder|Config whereCreatedAt($value)
 * @method static Builder|Config whereId($value)
 * @method static Builder|Config whereName($value)
 * @method static Builder|Config whereType($value)
 * @method static Builder|Config whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Config extends Model
{
    use HasFactory;
    use LogsActivity;

    /**
     * @param $query
     * @param  string|null  $keyword
     * @return mixed
     */
    public function scopeKeyword(Builder $query, string $keyword = null)
    {
        return $query->when($keyword, function (Builder $query, $keyword) {
            return $query->where('code', 'like', '%' . $keyword . '%')
                ->orWhere('name', 'like', '%' . $keyword . '%');
        });
    }
}
