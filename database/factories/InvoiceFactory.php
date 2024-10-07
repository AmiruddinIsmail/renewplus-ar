<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customer = Customer::inRandomOrder()->first();
        $charge_fee = $this->faker->numberBetween(100, 1000);

        return [
            'customer_id' => $customer->id,
            'reference_no' => $this->faker->uuid(),
            'issue_at' => $this->faker->date(),
            'due_at' => $this->faker->date(),
            'subscription_fee' => $customer->subscription_fee,
            'charge_fee' => $charge_fee,
            'unresolved_amount' => $customer->subscription_fee + $charge_fee,
        ];
    }
}
