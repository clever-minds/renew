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

    public function tenants(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('tenants')
                ->join('saas_plans', 'tenants.saas_plan_id', '=', 'saas_plans.id')
                ->select('tenants.*', 'saas_plans.name as plan_name');

            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $csrfToken = csrf_token();
                    if($row->status === 'active' || $row->status === 'trial') {
                        $btn = '<form action="'.route('admin.tenants.suspend', $row->id).'" method="POST" style="display:inline">
                            <input type="hidden" name="_token" value="'.$csrfToken.'">
                            <button type="submit" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Suspend"><i class="fas fa-pause"></i></button>
                        </form>';
                    } else {
                        $btn = '<form action="'.route('admin.tenants.activate', $row->id).'" method="POST" style="display:inline">
                            <input type="hidden" name="_token" value="'.$csrfToken.'">
                            <button type="submit" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Activate"><i class="fas fa-play"></i></button>
                        </form>';
                    }
                    return '<div class="flex items-center space-x-2">'.$btn.'</div>';
                })
                ->editColumn('status', function($row){
                    $class = $row->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800';
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '.$class.'">'.ucfirst($row->status).'</span>';
                })
                ->editColumn('created_at', function($row){
                    return \Carbon\Carbon::parse($row->created_at)->format('M d, Y');
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('admin.tenants.index');
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
