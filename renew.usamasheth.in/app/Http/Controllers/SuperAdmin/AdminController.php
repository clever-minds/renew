<?php

declare(strict_types=1);

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Tenant;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        // Global SaaS stats across all tenants
        $totalTenants = DB::table('tenants')->count();
        $activeTenants = DB::table('tenants')->where('status', 'active')->count();
        
        $totalSaaSRevenue = DB::table('tenants')
            ->join('saas_plans', 'tenants.saas_plan_id', '=', 'saas_plans.id')
            ->where('tenants.status', 'active')
            ->sum('saas_plans.price');

        $recentTenants = DB::table('tenants')
            ->join('saas_plans', 'tenants.saas_plan_id', '=', 'saas_plans.id')
            ->select('tenants.*', 'saas_plans.name as plan_name')
            ->orderBy('tenants.created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('totalTenants', 'activeTenants', 'totalSaaSRevenue', 'recentTenants'));
    }

    public function tenants(Request $request): View
    {
        $query = DB::table('tenants')
            ->join('saas_plans', 'tenants.saas_plan_id', '=', 'saas_plans.id')
            ->select('tenants.*', 'saas_plans.name as plan_name');

        if ($request->filled('search')) {
            $query->where('tenants.name', 'like', '%' . $request->search . '%')
                  ->orWhere('tenants.email', 'like', '%' . $request->search . '%');
        }

        $tenants = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.tenants.index', compact('tenants'));
    }

    public function suspendTenant(int $id): RedirectResponse
    {
        // No TenantScope here because this is the Super Admin controller
        DB::table('tenants')->where('id', $id)->update(['status' => 'suspended']);
        return back()->with('success', 'Tenant suspended.');
    }

    public function activateTenant(int $id): RedirectResponse
    {
        DB::table('tenants')->where('id', $id)->update(['status' => 'active']);
        return back()->with('success', 'Tenant activated.');
    }
}
