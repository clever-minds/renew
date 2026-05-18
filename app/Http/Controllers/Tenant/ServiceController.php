<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Http\Requests\Tenant\StoreServiceRequest;
use App\Http\Requests\Tenant\UpdateServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Service::query();
            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $editUrl = route('app.services.edit', $row->id);
                    return '<a href="'.$editUrl.'" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"><i class="fas fa-edit"></i></a>';
                })
                ->editColumn('price', function($row){
                    return '₹' . number_format((float)$row->price, 2);
                })
                ->editColumn('billing_cycle', function($row){
                    return ucfirst($row->billing_cycle->value);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('app.services.index');
    }

    public function create(): View
    {
        return view('app.services.create');
    }

    public function show(Service $service): View
    {
        return view('app.services.show', compact('service'));
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        Service::create($request->validated());

        return redirect()->route('app.services.index')->with('success', 'Service catalog updated.');
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($request->validated());

        return redirect()->route('app.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        // Check if service is used in active subscriptions before hard deleting, otherwise soft delete
        $hasSubscriptions = $service->subscriptions()->exists();
        
        if ($hasSubscriptions) {
            $service->delete(); // Soft delete
            return back()->with('warning', 'Service is in use and has been archived instead of deleted.');
        }

        $service->forceDelete();
        return back()->with('success', 'Service deleted successfully.');
    }

    public function edit(Service $service): View
    {
        return view('app.services.edit', compact('service'));
    }
}
