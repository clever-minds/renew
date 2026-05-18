<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Dynamically configure mail based on the user's tenant settings
        $user = User::where('email', $request->email)->first();
        if ($user && $user->tenant_id) {
            $settingsRecord = DB::table('settings')
                ->where('tenant_id', $user->tenant_id)
                ->where('key', 'smtp_settings')
                ->first();

            if ($settingsRecord) {
                $smtp = json_decode($settingsRecord->value, true);
                if (!empty($smtp['host']) && !empty($smtp['username'])) {
                    Config::set('mail.default', 'smtp');
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

        // We're using the standard Laravel Password Broker
        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}
