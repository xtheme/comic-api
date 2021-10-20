<?php

namespace App\Traits;

use App\Models\UserPurchaseLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait CanPurchase
{
    // 用戶模型應增加錢包欄位
    protected $userWalletField = 'wallet';

    // 商品模型應增加售價欄位
    protected $itemPriceField = 'price';

    // 返回錢包
    public function getWallet()
    {
        return $this->{$this->userWalletField};
    }

    // 檢查是否曾經購買
    public function hasBought(Model $model)
    {
        return UserPurchaseLog::where('user_id', $this->id)
                              ->where('item_model', get_class($model))
                              ->where('item_id', $model->getKey())
                              ->exists();
    }

    // 建立用戶消費(購買)紀錄
    public function purchase(Model $model)
    {
        if ($this->hasBought($model)) {
            throw new \Exception('您已购买过此项目！');
        }

        $class = get_class($model);
        $type = Str::snake(Str::singular(class_basename($class)));
        $item_id = $model->getKey();
        $item_price = $model->{$this->itemPriceField};

        $wallet = $this->{$this->userWalletField};

        if ($wallet < $item_price) {
            throw new \Exception('钱包余额不足！');
        }

        // 建立用戶消費(購買)紀錄
        $log = UserPurchaseLog::firstOrCreate([
            'user_id' => $this->id,
            'app_id' => $this->app_id,
            'channel_id' => $this->channel_id,
            'type' => $type,
            'item_model' => $class,
            'item_id' => $item_id,
            'item_price' => $item_price,
        ]);

        // 如果是剛剛創建的則從錢包扣款
        if ($log->wasRecentlyCreated) {
            $this->decrement($this->userWalletField, $item_price);
        }

        return $this;
    }
}
