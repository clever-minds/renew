<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasTenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderLog extends Model
{
    use HasFactory, HasTenantScope;

    protected $fillable = [
        'tenant_id',
        'client_subscription_id',
        'reminder_id',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function subscription()
    {
        return $this->belongsTo(ClientSubscription::class, 'client_subscription_id');
    }

    public function reminder()
    {
        return $this->belongsTo(Reminder::class);
    }
}
