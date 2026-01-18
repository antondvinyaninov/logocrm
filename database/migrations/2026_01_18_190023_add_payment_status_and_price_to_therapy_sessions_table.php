<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('therapy_sessions', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->nullable()->after('duration_minutes');
            $table->enum('payment_status', ['unpaid', 'paid', 'partial'])->default('unpaid')->after('status');
            $table->date('paid_at')->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('therapy_sessions', function (Blueprint $table) {
            $table->dropColumn(['price', 'payment_status', 'paid_at']);
        });
    }
};
