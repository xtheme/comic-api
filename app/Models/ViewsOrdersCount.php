<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ViewsOrdersCount
 *
 * @property int $user_id 用户ID
 * @property int $count
 * @method static \Illuminate\Database\Eloquent\Builder|ViewsOrdersCount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ViewsOrdersCount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ViewsOrdersCount query()
 * @method static \Illuminate\Database\Eloquent\Builder|ViewsOrdersCount whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ViewsOrdersCount whereUserId($value)
 * @mixin \Eloquent
 */
class ViewsOrdersCount extends Model
{
    use HasFactory;
}
