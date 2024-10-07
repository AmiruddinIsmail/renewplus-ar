<?php

namespace Database\Factories;

use App\Models\Charge;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Charge>
 */
class ChargeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customer = Customer::with('invoices:id')->inRandomOrder()->first();

        return [
            'customer_id' => $customer->id,
            'reference_no' => $this->faker->uuid(),
            'type' => $this->faker->randomElement([Charge::TYPE_LATE, Charge::TYPE_PENALTY]),
            'invoice_id' => $this->faker->randomElement($customer->invoices->pluck('id')->toArray()),
            'amount' => $this->faker->numberBetween(100, 1000),
            'charged_at' => $this->faker->date(),
        ];
    }
}
