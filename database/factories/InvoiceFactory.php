<?php

namespace Database\Factories;

use App\Models\Order;
use App\Utils\Helper;
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
        $order = Order::inRandomOrder()->first();
        $charge_amount = $this->faker->numberBetween(100, 1000);

        return [
            'customer_id' => $order->customer_id,
            'order_id' => $order->id,
            'reference_no' => Helper::referenceNoConvention('INV', mt_rand(1, 9999), now()),
            'issue_at' => $this->faker->date(),
            'due_at' => $this->faker->date(),
            'subscription_amount' => $order->subscription_amount,
            'charge_amount' => $charge_amount,
            'unresolved_amount' => $order->subscription_amount + $charge_amount,
        ];
    }
}
