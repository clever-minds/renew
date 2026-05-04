<?php

declare(strict_types=1);

namespace App\Services\Notifications;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\Client;

class WhatsAppService
{
    private string $apiUrl = 'https://graph.facebook.com/v17.0/';
    private string $phoneNumberId;
    private string $accessToken;

    public function __construct(int $tenantId)
    {
        $settings = DB::table('settings')
            ->where('tenant_id', $tenantId)
            ->where('key', 'whatsapp_settings')
            ->first();

        if (!$settings) {
            throw new \Exception("WhatsApp credentials not configured for tenant.");
        }

        $config = json_decode($settings->value, true);
        $this->phoneNumberId = $config['phone_number_id'] ?? '';
        $this->accessToken = $config['access_token'] ?? '';
    }

    public function sendTemplateMessage(Client $client, string $templateName, array $variables): bool
    {
        if (empty($client->phone) || empty($this->accessToken)) {
            return false;
        }

        $components = [];
        if (!empty($variables)) {
            $parameters = [];
            foreach ($variables as $val) {
                $parameters[] = ['type' => 'text', 'text' => (string) $val];
            }
            $components[] = ['type' => 'body', 'parameters' => $parameters];
        }

        $response = Http::withToken($this->accessToken)->post(
            $this->apiUrl . $this->phoneNumberId . '/messages',
            [
                'messaging_product' => 'whatsapp',
                'to' => $client->phone,
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => ['code' => 'en'],
                    'components' => $components
                ]
            ]
        );

        return $response->successful();
    }
}
