<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Requisition
 *
 * @property int $id
 * @property string $delivery_location 寄貨地點
 * @property string $shipping_method 運輸方式
 * @property string $shipper_name 寄件人姓名
 * @property string $shipper_phone 寄件人電話
 * @property string $receiver_name 收件人姓名
 * @property string $receiver_phone 收件人電話
 * @property string $receiver_address 收件人地址
 * @property string $shipping_list 寄貨清單
 * @property string $total_value 總價值
 * @property string $weight 重量(g)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Requisition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Requisition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Requisition query()
 * @method static \Illuminate\Database\Eloquent\Builder|Requisition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requisition whereDeliveryLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requisition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requisition whereReceiverAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requisition whereReceiverName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requisition whereReceiverPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requisition whereShipperName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requisition whereShipperPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requisition whereShippingList($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requisition whereShippingMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requisition whereTotalValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requisition whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requisition whereWeight($value)
 * @mixin \Eloquent
 */
class Requisition extends Model
{
    use HasFactory;
}
