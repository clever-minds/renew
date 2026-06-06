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
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->string('hsn_code')->nullable()->after('description');
            $table->decimal('tax_rate', 5, 2)->default(0)->after('unit_price');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('tax_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_items', function (Blueprint $table) {
            $table->dropColumn(['hsn_code', 'tax_rate', 'tax_amount']);
        });
    }
};
