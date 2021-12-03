<?php

namespace Database\Factories;

use App\Jobs\RechargeJob;
use App\Models\Order;
use App\Models\Pricing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $count = Order::whereDate('created_at', date('Y-m-d'))->count();

        $order_no = date('ymd') . str_pad((string) ($count + 1), 5, '0', STR_PAD_LEFT) . rand(10, 99);

        $user_id = User::where('is_active', 1)->inRandomOrder()->first()->id;

        $pricing = Pricing::where('status', 1)->inRandomOrder()->first();

        $plan_options = [
            'coin' => $pricing->coin,
            'gift_coin' => $pricing->gift_coin,
            'days' => $pricing->days,
            'gift_days' => $pricing->gift_days,
        ];

        return [
            'type' => $pricing->type,
            'order_no' => $order_no,
            'user_id' => $user_id,
            'app_id' => 0,
            'channel_id' => 1,
            'amount' => $pricing->price,
            'plan_options' => $plan_options,
            'payment_id' => 1,
            'transaction_id' => $order_no,
            'transaction_at' => now(),
            'status' => 1,
            'ip' => $this->faker->ipv4,
            'platform' => 'wap',
            'first' => $this->faker->randomElement([0, 1]),
            'created_at' => now(),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Order $order) {
            //
        })->afterCreating(function (Order $order) {
            RechargeJob::dispatch($order);
        });
    }
}
