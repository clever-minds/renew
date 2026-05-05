<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tenant;
use App\Models\SaasPlan;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'domain_prefix' => fake()->unique()->domainWord(),
            'status' => 'active',
            'saas_plan_id' => SaasPlan::factory(),
            'trial_ends_at' => now()->addDays(14),
        ];
    }
}
