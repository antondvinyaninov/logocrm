<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('child_specialist_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->foreignId('specialist_id')->constrained('specialist_profiles')->onDelete('cascade');
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->date('started_at'); // Дата начала работы
            $table->date('ended_at')->nullable(); // Дата окончания (null = текущий)
            $table->text('notes')->nullable(); // Примечания о причине смены
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('child_specialist_history');
    }
};
