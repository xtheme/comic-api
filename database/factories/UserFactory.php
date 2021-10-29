<?php

namespace Database\Factories;

use App\Jobs\RegisterJob;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'app_id' => 0,
            'channel_id' => 1,
            'password' => Hash::make('password'),
            'wallet' => getConfig('app', 'register_coin'),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (User $user) {
            //
        })->afterCreating(function (User $user) {
            RegisterJob::dispatch($user, $this->faker->randomElement(['wap', 'app']));
        });
    }
}
