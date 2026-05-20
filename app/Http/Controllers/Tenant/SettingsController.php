<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\Settings\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Tenant;

class SettingsController extends Controller
{
    public function __construct(private SettingsService $settingsService)
    {
    }

    public function index(): View
    {
        $tenantId = session('tenant_id');
        $tenant = Tenant::findOrFail($tenantId);
        
        $company = $this->settingsService->get((int)$tenantId, 'company_settings', []);
        
        // Fallback to Tenant model values if settings haven't been saved yet
        if (empty($company)) {
            $company = [
                'company_name' => $tenant->name,
                'support_email' => $tenant->email,
                'currency' => 'INR',
            ];
        }

        $smtp = $this->settingsService->get((int)$tenantId, 'smtp_settings', []);
        $whatsapp = $this->settingsService->get((int)$tenantId, 'whatsapp_settings', []);

        return view('app.settings.index', compact('company', 'smtp', 'whatsapp'));
    }

    public function updateCompany(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name'      => ['required', 'string', 'max:255'],
            'support_email'     => ['required', 'email', 'max:255'],
            'additional_email'  => ['nullable', 'email', 'max:255'],
            'currency'          => ['required', 'string', 'size:3'],
            'support_phone'     => ['nullable', 'string', 'max:50'],
            'additional_phone'  => ['nullable', 'string', 'max:50'],
            'address_line1'     => ['nullable', 'string', 'max:255'],
            'address_city'      => ['nullable', 'string', 'max:100'],
            'address_state'     => ['nullable', 'string', 'max:100'],
            'bank_name'         => ['nullable', 'string', 'max:255'],
            'bank_account'      => ['nullable', 'string', 'max:100'],
            'bank_ifsc'         => ['nullable', 'string', 'max:100'],
            'bank_address'      => ['nullable', 'string', 'max:255'],
            'bank_routing'      => ['nullable', 'string', 'max:100'],
            'terms_conditions'  => ['nullable', 'string'],
            'logo'              => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:2048'],
        ]);

        $tenantId = session('tenant_id');

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store("logos/tenant_{$tenantId}", 'public');
            $validated['logo_url'] = $logoPath;
        } else {
            // Keep existing logo if no new file uploaded
            $existing = $this->settingsService->get((int)$tenantId, 'company_settings', []);
            if (!empty($existing['logo_url'])) {
                $validated['logo_url'] = $existing['logo_url'];
            }
        }

        unset($validated['logo']); // Don't store the file object itself
        $this->settingsService->set((int)$tenantId, 'company_settings', $validated);

        // Sync with Tenant model
        Tenant::where('id', $tenantId)->update([
            'name'  => $validated['company_name'],
            'email' => $validated['support_email']
        ]);

        return back()->with('success', 'Company settings updated successfully.');
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
