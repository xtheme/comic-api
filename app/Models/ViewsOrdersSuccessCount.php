<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ViewsOrdersSuccessCount
 *
 * @property int $user_id 用户ID
 * @property int $count
 * @method static \Illuminate\Database\Eloquent\Builder|ViewsOrdersSuccessCount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ViewsOrdersSuccessCount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ViewsOrdersSuccessCount query()
 * @method static \Illuminate\Database\Eloquent\Builder|ViewsOrdersSuccessCount whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ViewsOrdersSuccessCount whereUserId($value)
 * @mixin \Eloquent
 */
class ViewsOrdersSuccessCount extends Model
{
    use HasFactory;
}
