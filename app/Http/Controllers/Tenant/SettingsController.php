<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\Settings\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(private SettingsService $settingsService)
    {
    }

    public function index(): View
    {
        $tenantId = session('tenant_id');
        
        $company = $this->settingsService->get($tenantId, 'company_settings', []);
        $smtp = $this->settingsService->get($tenantId, 'smtp_settings', []);
        $whatsapp = $this->settingsService->get($tenantId, 'whatsapp_settings', []); // Would need decryption if stored encrypted

        return view('app.settings.index', compact('company', 'smtp', 'whatsapp'));
    }

    public function updateCompany(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'support_email' => ['required', 'email', 'max:255'],
            'timezone' => ['required', 'timezone'],
            'currency' => ['required', 'string', 'size:3'],
        ]);

        $this->settingsService->set(session('tenant_id'), 'company_settings', $validated);

        return back()->with('success', 'Company settings updated.');
    }

    public function updateSmtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'host' => ['required', 'string'],
            'port' => ['required', 'integer'],
            'username' => ['required', 'string'],
            'password' => ['required', 'string'], // Should be encrypted in real app
            'encryption' => ['required', 'in:tls,ssl'],
            'from_address' => ['required', 'email'],
            'from_name' => ['required', 'string'],
        ]);

        $this->settingsService->set(session('tenant_id'), 'smtp_settings', $validated);

        return back()->with('success', 'SMTP settings updated.');
    }
}
