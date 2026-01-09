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
        Schema::create('therapy_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->foreignId('therapist_id')->constrained('therapist_profiles')->onDelete('cascade');
            $table->timestamp('start_time');
            $table->integer('duration_minutes');
            $table->enum('type', ['individual', 'group'])->default('individual');
            $table->enum('format', ['offline', 'online'])->default('offline');
            $table->enum('status', ['planned', 'done', 'cancelled'])->default('planned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('therapy_sessions');
    }
};
