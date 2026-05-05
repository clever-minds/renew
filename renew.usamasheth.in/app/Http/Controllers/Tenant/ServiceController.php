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
    public function index(Request $request): View
    {
        $query = Service::query();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $services = $query->latest()->paginate(15)->withQueryString();

        return view('app.services.index', compact('services'));
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

        return back()->with('success', 'Service updated successfully.');
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
