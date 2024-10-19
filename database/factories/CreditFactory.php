<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Utils\Helper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Credit>
 */
class CreditFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = $this->faker->numberBetween(1000, 10000);

        return [
            'customer_id' => Customer::inRandomOrder()->first(),
            'reference_no' => Helper::referenceNoConvention('CRE', mt_rand(1, 9999), now()),
            'amount' => $amount,
            'unresolved_amount' => $amount,
        ];
    }
}
