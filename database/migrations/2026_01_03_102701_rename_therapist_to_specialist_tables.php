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
        // Переименовываем таблицу therapist_profiles в specialist_profiles
        Schema::rename('therapist_profiles', 'specialist_profiles');
        
        // Переименовываем колонку therapist_id в specialist_id в таблице children
        Schema::table('children', function (Blueprint $table) {
            $table->renameColumn('therapist_id', 'specialist_id');
        });
        
        // Переименовываем колонку therapist_id в specialist_id в таблице therapy_sessions
        Schema::table('therapy_sessions', function (Blueprint $table) {
            $table->renameColumn('therapist_id', 'specialist_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Откатываем изменения в обратном порядке
        Schema::table('therapy_sessions', function (Blueprint $table) {
            $table->renameColumn('specialist_id', 'therapist_id');
        });
        
        Schema::table('children', function (Blueprint $table) {
            $table->renameColumn('specialist_id', 'therapist_id');
        });
        
        Schema::rename('specialist_profiles', 'therapist_profiles');
    }
};
