<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\SaasPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'company_name' => ['required', 'string', 'max:255'],
        ]);

        $user = DB::transaction(function () use ($request) {
            // Get default free plan
            $freePlan = SaasPlan::where('slug', 'free')->first();

            // Create Tenant (Agency)
            $tenant = Tenant::create([
                'name' => $request->company_name,
                'email' => $request->email,
                'status' => 'trial',
                'saas_plan_id' => $freePlan?->id,
                'trial_ends_at' => Carbon::now()->addDays(14),
            ]);

            // Create User linked to Tenant
            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_active' => true,
            ]);

            // Here we would assign the Spatie Role 'Tenant Owner' to the user
            // $user->assignRole('Tenant Owner');

            return $user;
        });

        Auth::login($user);

        // Set session tenant_id
        session(['tenant_id' => $user->tenant_id]);

        return redirect()->route('app.dashboard');
    }
}
