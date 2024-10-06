<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::whereHas('invoices')->inRandomOrder()->first(),
            'reference_no' => $this->faker->uuid(),
            'paid_at' => $this->faker->dateTime(),
            'amount' => $this->faker->numberBetween(1000, 10000),
        ];
    }
}
