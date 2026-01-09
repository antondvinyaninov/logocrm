<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Обновляем существующих админов на роль organization
        DB::table('users')
            ->where('role', 'admin')
            ->update(['role' => 'organization']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Возвращаем обратно
        DB::table('users')
            ->where('role', 'organization')
            ->update(['role' => 'admin']);
    }
};
