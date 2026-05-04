<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\CRM\ClientService;
use App\Http\Requests\Tenant\StoreClientRequest;
use App\Http\Requests\Tenant\UpdateClientRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(private ClientService $clientService)
    {
    }

    public function index(Request $request): View
    {
        $query = Client::query();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $clients = $query->latest()->paginate(15)->withQueryString();

        return view('app.clients.index', compact('clients'));
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $this->clientService->createClient($request->validated());

        return redirect()->route('app.clients.index')->with('success', 'Client created successfully.');
    }

    public function show(Client $client): View
    {
        // Eager load relationships for the profile view
        $client->load(['subscriptions.service']);
        
        return view('app.clients.show', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $this->clientService->updateClient($client, $request->validated());

        return back()->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('app.clients.index')->with('success', 'Client deleted successfully.');
    }
}
