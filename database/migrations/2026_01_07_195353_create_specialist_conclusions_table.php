<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specialist_conclusions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained('children')->onDelete('cascade');
            $table->foreignId('specialist_id')->constrained('specialist_profiles')->onDelete('cascade');
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->text('content');
            $table->json('attachments')->nullable(); // Массив путей к файлам
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specialist_conclusions');
    }
};
