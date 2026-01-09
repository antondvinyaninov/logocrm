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
        Schema::table('payments', function (Blueprint $table) {
            // Переименовываем колонки
            $table->renameColumn('date', 'payment_date');
            $table->renameColumn('method', 'payment_method');
            $table->renameColumn('comment', 'notes');
            
            // Добавляем колонку status
            $table->string('status')->default('completed')->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Откатываем изменения
            $table->renameColumn('payment_date', 'date');
            $table->renameColumn('payment_method', 'method');
            $table->renameColumn('notes', 'comment');
            
            $table->dropColumn('status');
        });
    }
};
