<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ConfigureTenantMail
{
    /**
     * Handle an incoming request.
     * Dynamically sets SMTP credentials based on tenant settings.
     */
    public function handle(Request $request, Closure $next)
    {
        $tenantId = session('tenant_id');

        if ($tenantId) {
            // Load from settings JSON cache
            $settingsRecord = DB::table('settings')
                ->where('tenant_id', $tenantId)
                ->where('key', 'smtp_settings')
                ->first();

            if ($settingsRecord) {
                $smtp = json_decode($settingsRecord->value, true);

                if (!empty($smtp['host']) && !empty($smtp['username'])) {
                    Config::set('mail.mailers.smtp.host', $smtp['host']);
                    Config::set('mail.mailers.smtp.port', $smtp['port']);
                    Config::set('mail.mailers.smtp.username', $smtp['username']);
                    Config::set('mail.mailers.smtp.password', $smtp['password']);
                    Config::set('mail.mailers.smtp.encryption', $smtp['encryption']);
                    Config::set('mail.from.address', $smtp['from_address']);
                    Config::set('mail.from.name', $smtp['from_name']);
                }
            }
        }

        return $next($request);
    }
}
