<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tenure = [12, 24, 36];
        $contractDate = Carbon::parse('2021-01-01');

        return [
            'uuid' => $this->faker->uuid(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'tenure' => $tenure[mt_rand(0, count($tenure) - 1)],
            'subscription_fee' => $this->faker->numberBetween(1000, 10000),
            'contract_at' => $contractDate->addDays($this->faker->numberBetween(0, 365)),
            'payment_gateway' => $this->faker->randomElement(['stripe', 'paypal']),
            'payment_reference' => $this->faker->uuid(),
        ];
    }
}
