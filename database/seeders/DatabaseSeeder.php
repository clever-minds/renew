<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Tenant;
use App\Models\SaasPlan;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default SaaS Plans
        $freePlan = SaasPlan::create([
            'name' => 'Free Trial',
            'slug' => 'free',
            'description' => '14-day free trial',
            'price' => 0.00,
            'billing_cycle' => 'monthly',
            'is_active' => true,
        ]);

        SaasPlan::create([
            'name' => 'Pro Agency',
            'slug' => 'pro',
            'description' => 'Unlimited clients and invoices',
            'price' => 49.00,
            'billing_cycle' => 'monthly',
            'is_active' => true,
        ]);

        // 2. Create the Super Admin (Bypasses Tenant isolation)
        // Ensure no tenant_id is assigned to the global admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@renewpilot.com',
            'password' => Hash::make('password'),
            'is_super_admin' => true,
            'is_active' => true,
            'tenant_id' => null,
            'email_verified_at' => Carbon::now(),
        ]);

        // 3. Create a Demo Tenant / Agency
        $demoTenant = Tenant::create([
            'name' => 'Demo Agency LLC',
            'email' => 'agency@demo.com',
            'status' => 'active',
            'saas_plan_id' => $freePlan->id,
            'trial_ends_at' => Carbon::now()->addDays(14),
        ]);

        // 4. Create the Tenant Owner User
        User::create([
            'name' => 'Demo Agency Owner',
            'email' => 'owner@demo.com',
            'password' => Hash::make('password'),
            'is_super_admin' => false,
            'is_active' => true,
            'tenant_id' => $demoTenant->id,
            'email_verified_at' => Carbon::now(),
        ]);
        
        $this->command->info('Database seeded successfully. Admin: admin@renewpilot.com | Owner: owner@demo.com | Password: password');
    }
}
