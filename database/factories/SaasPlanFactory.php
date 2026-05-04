<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SaasPlan;

class SaasPlanFactory extends Factory
{
    protected $model = SaasPlan::class;

    public function definition(): array
    {
        return [
            'name' => 'Pro Plan',
            'slug' => 'pro-plan',
            'price' => 49.99,
            'billing_cycle' => 'monthly',
            'features' => json_encode(['clients' => 100, 'invoices' => 'unlimited']),
            'is_active' => true,
        ];
    }
}
