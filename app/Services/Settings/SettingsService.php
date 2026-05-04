<?php

declare(strict_types=1);

namespace App\Services\Settings;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class SettingsService
{
    /**
     * Get a setting value, decoding JSON if necessary.
     */
    public function get(int $tenantId, string $key, mixed $default = null): mixed
    {
        $setting = DB::table('settings')
            ->where('tenant_id', $tenantId)
            ->where('key', $key)
            ->first();

        if (!$setting) {
            return $default;
        }

        $value = json_decode($setting->value, true);
        return (json_last_error() === JSON_ERROR_NONE) ? $value : $setting->value;
    }

    /**
     * Set a setting value. Automatically encodes arrays to JSON.
     * Encrypts the value if it's in the sensitive keys list.
     */
    public function set(int $tenantId, string $key, mixed $value, bool $encrypt = false): void
    {
        $encodedValue = is_array($value) ? json_encode($value) : (string) $value;

        if ($encrypt) {
            $encodedValue = Crypt::encryptString($encodedValue);
        }

        DB::table('settings')->updateOrInsert(
            ['tenant_id' => $tenantId, 'key' => $key],
            ['value' => $encodedValue]
        );
    }
}
