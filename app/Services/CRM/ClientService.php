<?php

declare(strict_types=1);

namespace App\Services\CRM;

use App\Models\Client;
use App\Enums\ClientStatus;
use Illuminate\Support\Facades\DB;

class ClientService
{
    public function createClient(array $data): Client
    {
        return DB::transaction(function () use ($data) {
            $client = Client::create([
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'company' => $data['company'] ?? null,
                'gst_number' => $data['gst_number'] ?? null,
                'billing_address' => $data['billing_address'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => ClientStatus::ACTIVE->value,
            ]);

            return $client;
        });
    }

    public function updateClient(Client $client, array $data): Client
    {
        $client->update($data);
        return $client;
    }

    public function blockClient(Client $client): void
    {
        $client->update(['status' => ClientStatus::BLOCKED->value]);
    }
}
