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
        Schema::table('specialist_profiles', function (Blueprint $table) {
            $table->text('about')->nullable()->after('specialization');
            $table->text('education')->nullable()->after('about');
            $table->integer('experience_years')->nullable()->after('education');
            $table->json('certificates')->nullable()->after('experience_years');
            $table->string('photo')->nullable()->after('certificates');
            $table->decimal('rating', 3, 2)->default(0)->after('photo');
            $table->integer('reviews_count')->default(0)->after('rating');
            $table->decimal('price_per_session', 10, 2)->nullable()->after('reviews_count');
            $table->boolean('available_online')->default(true)->after('price_per_session');
            $table->boolean('available_offline')->default(true)->after('available_online');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('specialist_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'about',
                'education',
                'experience_years',
                'certificates',
                'photo',
                'rating',
                'reviews_count',
                'price_per_session',
                'available_online',
                'available_offline',
            ]);
        });
    }
};
