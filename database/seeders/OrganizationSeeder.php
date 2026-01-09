<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // 1. Центр "Речевичок"
        Organization::create([
            'name' => 'Логопедический центр "Речевичок"',
            'type' => 'center',
            'email' => 'info@rechevichok.ru',
            'phone' => '+7 (495) 123-45-67',
            'address' => 'г. Москва, ул. Примерная, д. 10',
            'description' => 'Профессиональный логопедический центр с опытными специалистами',
            'website' => 'https://rechevichok.ru',
            'is_active' => true,
        ]);

        // 2. Частный специалист "Анна Логопед"
        Organization::create([
            'name' => 'Анна Петрова - Частный логопед',
            'type' => 'individual',
            'email' => 'anna@logoped.ru',
            'phone' => '+7 (495) 987-65-43',
            'address' => 'г. Москва, ул. Ленина, д. 5',
            'description' => 'Частный логопед с 10-летним опытом работы',
            'website' => null,
            'is_active' => true,
        ]);

        // 3. Центр "Говорун"
        Organization::create([
            'name' => 'Детский центр "Говорун"',
            'type' => 'center',
            'email' => 'contact@govorun.ru',
            'phone' => '+7 (495) 555-12-34',
            'address' => 'г. Москва, пр-т Мира, д. 25',
            'description' => 'Современный центр развития речи для детей',
            'website' => 'https://govorun.ru',
            'is_active' => true,
        ]);
    }
}
