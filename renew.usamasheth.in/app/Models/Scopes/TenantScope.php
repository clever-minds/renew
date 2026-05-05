<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Check if there is an authenticated user and a tenant_id in the session.
        // We also want to ensure Super Admins bypass this scope if needed.
        if (Auth::check() && Session::has('tenant_id')) {
            $builder->where($model->getTable() . '.tenant_id', Session::get('tenant_id'));
        }
    }
}
