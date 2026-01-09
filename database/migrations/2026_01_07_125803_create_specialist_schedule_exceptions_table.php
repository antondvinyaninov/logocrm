<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specialist_schedule_exceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialist_id')->constrained('specialist_profiles')->onDelete('cascade');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->date('exception_date'); // Конкретная дата исключения
            $table->enum('type', ['day_off', 'custom_hours'])->default('day_off'); // Выходной или особые часы
            $table->time('start_time')->nullable(); // Для custom_hours
            $table->time('end_time')->nullable(); // Для custom_hours
            $table->string('note')->nullable(); // Примечание (например, "Отпуск", "Больничный")
            $table->timestamps();
            
            $table->index(['specialist_id', 'exception_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specialist_schedule_exceptions');
    }
};
