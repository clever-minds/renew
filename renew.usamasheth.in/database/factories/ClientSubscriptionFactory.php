<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ClientSubscription;
use App\Models\Tenant;
use App\Models\Client;
use App\Models\Service;

class ClientSubscriptionFactory extends Factory
{
    protected $model = ClientSubscription::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'client_id' => Client::factory(),
            'service_id' => Service::factory(),
            'price' => fake()->randomFloat(2, 10, 500),
            'start_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'next_due_date' => fake()->dateTimeBetween('now', '+1 year'),
            'status' => 'active',
            'auto_invoice' => true,
        ];
    }
}
