<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $customer = Customer::inRandomOrder()->first();

        return [
            'addressable_id' => $customer->id,
            'addressable_type' => 'App\Models\Customer',
            'type' => $this->faker->randomElement(['home', 'work', 'delivery']),
            'line_1' => $this->faker->streetAddress(),
            'line_2' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'postcode' => $this->faker->postcode(),
            'country' => $this->faker->country(),
        ];
    }
}
