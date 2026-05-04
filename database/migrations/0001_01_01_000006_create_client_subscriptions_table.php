<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('client_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->restrictOnDelete();
            $table->decimal('price', 10, 2)->comment('Overrides service base price');
            $table->date('start_date');
            $table->date('next_due_date')->nullable();
            $table->string('status')->default('active'); // active, suspended, cancelled, expired
            $table->boolean('auto_invoice')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // CRITICAL index for the nightly cron job to evaluate renewals
            $table->index(['tenant_id', 'status', 'next_due_date']);
            $table->index(['tenant_id', 'client_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_subscriptions');
    }
};
