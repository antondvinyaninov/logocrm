<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('external_specialist_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Кто создал направление
            
            // Информация о направлении
            $table->string('specialist_type'); // Тип специалиста: невролог, психолог, офтальмолог и т.д.
            $table->text('reason')->nullable(); // Причина направления
            $table->date('referral_date'); // Дата выдачи направления
            $table->date('appointment_date')->nullable(); // Планируемая дата приема
            
            // Статус направления
            $table->enum('status', ['pending', 'scheduled', 'completed', 'cancelled'])->default('pending');
            // pending - ожидает записи
            // scheduled - запись назначена
            // completed - посещение состоялось
            // cancelled - отменено
            
            // Результаты посещения
            $table->date('visit_date')->nullable(); // Фактическая дата посещения
            $table->text('results')->nullable(); // Результаты обследования/консультации
            $table->text('recommendations')->nullable(); // Рекомендации специалиста
            $table->json('attachments')->nullable(); // Прикрепленные файлы (заключения, результаты анализов)
            
            $table->timestamps();
            
            // Индексы
            $table->index('child_id');
            $table->index('organization_id');
            $table->index('status');
            $table->index('referral_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_specialist_referrals');
    }
};
