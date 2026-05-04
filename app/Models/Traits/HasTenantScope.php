<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\Scopes\TenantScope;
use Illuminate\Support\Facades\Session;

trait HasTenantScope
{
    /**
     * Boot the trait and apply the global scope.
     */
    protected static function bootHasTenantScope(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model) {
            if (Session::has('tenant_id') && empty($model->tenant_id)) {
                $model->tenant_id = Session::get('tenant_id');
            }
        });
    }
}
