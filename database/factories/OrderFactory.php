<?php

namespace Database\Factories;

use App\Jobs\RechargeJob;
use App\Models\Order;
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

        $plan_options = [
            'coin' => 5000,
            'gift_coin' => 3000,
            'days' => 0,
            'gift_days' => 0,
        ];

        return [
            'type' => 'charge',
            'order_no' => $order_no,
            'user_id' => 1,
            'app_id' => 0,
            'channel_id' => 1,
            'amount' => 30.00,
            'plan_options' => $plan_options,
            'payment_id' => 1,
            'transaction_id' => $order_no,
            'transaction_at' => now(),
            'status' => 1,
            'ip' => $this->faker->ipv4,
            'platform' => 'wap',
            'first' => $this->faker->shuffle([0, 1]),
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
