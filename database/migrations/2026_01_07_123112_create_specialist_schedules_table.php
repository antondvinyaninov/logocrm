<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specialist_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialist_id')->constrained('specialist_profiles')->onDelete('cascade');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->integer('day_of_week'); // 0 = Понедельник, 6 = Воскресенье
            $table->time('start_time');
            $table->time('end_time');
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->enum('repeat_type', ['weekly', 'biweekly', 'monthly'])->default('weekly');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['specialist_id', 'day_of_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specialist_schedules');
    }
};
