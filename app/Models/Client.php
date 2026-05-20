<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasTenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Client extends Model
{
    use HasFactory, SoftDeletes, HasTenantScope, Notifiable;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'company',
        'gst_number',
        'billing_address',
        'notes',
    ];

    protected $casts = [
        'billing_address' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(ClientSubscription::class);
    }
}
