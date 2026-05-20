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

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Client::query();
            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $viewUrl = route('app.clients.show', $row->id);
                    $editUrl = route('app.clients.edit', $row->id);
                    return '
                        <div class="flex items-center space-x-2">
                            <a href="'.$viewUrl.'" class="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors"><i class="fas fa-eye"></i></a>
                            <a href="'.$editUrl.'" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"><i class="fas fa-edit"></i></a>
                        </div>';
                })
                ->editColumn('status', function($row){
                    $class = $row->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800';
                    $dotClass = $row->status === 'active' ? 'bg-emerald-500' : 'bg-gray-400';
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '.$class.'">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 '.$dotClass.'"></span>
                                '.ucfirst($row->status).'
                            </span>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('app.clients.index');
    }

    public function store(StoreClientRequest $request)
    {
        $client = $this->clientService->createClient($request->validated());

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'client' => $client,
                'message' => 'Client created successfully.'
            ]);
        }

        return redirect()->route('app.clients.index')->with('success', 'Client created successfully.');
    }

    public function show(Client $client): View
    {
        // Eager load relationships for the profile view
        $client->load(['subscriptions.service']);
        
        return view('app.clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        return view('app.clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $this->clientService->updateClient($client, $request->validated());

        return redirect()->route('app.clients.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('app.clients.index')->with('success', 'Client deleted successfully.');
    }
}
