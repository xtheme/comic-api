<?php

namespace App\Traits;

use App\Models\UserPurchaseLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait CanPurchase
{
    // 用戶模型應增加錢包欄位
    protected $userWalletField = 'wallet';
    // 商品模型應增加售價欄位
    protected $itemIdField = 'id';
    protected $itemPriceField = 'price';

    public function hasBought()
    {
        return UserPurchaseLog::where('user_id', Auth::user()->id)->where('item_model', static::class)->where('item_id', $this->{$this->itemIdField})->exists();
    }

    /**
     * 建立用戶消費(購買)紀錄
     */
    public function purchase()
    {
        if (!Auth::check()) {
            throw new \Exception(__('api.unauthenticated'));
        }

        if ($this->hasBought()) {
            throw new \Exception('您已购买过此项目！');
        }

        $class = static::class;
        $type = Str::snake(Str::singular(class_basename($class)));
        $item_id = $this->{$this->itemIdField};
        $item_price = $this->{$this->itemPriceField};
        $user = Auth::user();

        $wallet = $user->{$this->userWalletField};

        if ($wallet < $item_price) {
            throw new \Exception('钱包余额不足！');
        }

        // 建立用戶消費(購買)紀錄
        $log = new UserPurchaseLog;
        $log->user_id = $user->id;
        $log->type = $type;
        $log->item_model = $class;
        $log->item_id = $item_id;
        $log->item_price = $item_price;
        $log->save();

        // 錢包扣款
        $user->decrement($this->userWalletField, $item_price);

        return $this;
    }
}
