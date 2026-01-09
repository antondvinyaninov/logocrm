<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organization;
use App\Models\SpecialistProfile;
use App\Models\ParentProfile;
use App\Models\Child;
use App\Models\TherapySession;
use App\Models\Lead;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Создаём организации
        $this->call(OrganizationSeeder::class);
        
        $org1 = Organization::where('type', 'center')->first(); // Речевичок
        $org2 = Organization::where('type', 'individual')->first(); // Анна
        $org3 = Organization::where('type', 'center')->skip(1)->first(); // Говорун

        // 2. Создаём SuperAdmin (не привязан к организации)
        $superadmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@logoped.test',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'organization_id' => null,
        ]);

        // 3. Организация 1: Центр "Речевичок"
        // Владелец центра
        $owner1 = User::create([
            'name' => 'Владелец Речевичок',
            'email' => 'owner@rechevichok.ru',
            'password' => Hash::make('password'),
            'role' => 'organization',
            'organization_id' => $org1->id,
        ]);

        // Специалисты центра
        $spec1User = User::create([
            'name' => 'Иванова М.П.',
            'email' => 'specialist@logoped.test',
            'password' => Hash::make('password'),
            'role' => 'specialist',
            'organization_id' => $org1->id,
        ]);

        $spec1 = SpecialistProfile::create([
            'user_id' => $spec1User->id,
            'full_name' => 'Иванова Мария Петровна',
            'specialization' => 'Логопед-дефектолог',
            'about' => 'Опытный логопед-дефектолог с 10-летним стажем работы.',
            'education' => 'МПГУ, факультет специальной педагогики (2013)',
            'experience_years' => 10,
            'rating' => 4.8,
            'reviews_count' => 15,
            'price_per_session' => 2500,
            'available_online' => true,
            'available_offline' => true,
            'organization_id' => $org1->id,
        ]);

        $spec2User = User::create([
            'name' => 'Смирнова Е.В.',
            'email' => 'specialist2@logoped.test',
            'password' => Hash::make('password'),
            'role' => 'specialist',
            'organization_id' => $org1->id,
        ]);

        $spec2 = SpecialistProfile::create([
            'user_id' => $spec2User->id,
            'full_name' => 'Смирнова Елена Викторовна',
            'specialization' => 'Логопед, специалист по дислалии',
            'about' => 'Работаю с детьми дошкольного возраста.',
            'education' => 'СПбГУ, дефектологический факультет (2015)',
            'experience_years' => 8,
            'rating' => 4.5,
            'reviews_count' => 8,
            'price_per_session' => 2000,
            'available_online' => true,
            'available_offline' => false,
            'organization_id' => $org1->id,
        ]);

        // Родитель и ребёнок в центре 1
        $parent1User = User::create([
            'name' => 'Петров И.С.',
            'email' => 'parent@logoped.test',
            'password' => Hash::make('password'),
            'role' => 'parent',
            'organization_id' => $org1->id,
        ]);

        $parent1 = ParentProfile::create([
            'user_id' => $parent1User->id,
            'full_name' => 'Петров Иван Сергеевич',
            'phone' => '+7 (999) 123-45-67',
            'organization_id' => $org1->id,
        ]);

        $child1 = Child::create([
            'full_name' => 'Петров Алексей Иванович',
            'birth_date' => '2018-05-15',
            'parent_id' => $parent1->id,
            'specialist_id' => $spec1->id,
            'anamnesis' => 'Задержка речевого развития',
            'goals' => ['Постановка звука Р', 'Расширение словарного запаса'],
            'tags' => ['ЗРР', 'дислалия'],
            'organization_id' => $org1->id,
        ]);

        // Занятие
        TherapySession::create([
            'child_id' => $child1->id,
            'specialist_id' => $spec1->id,
            'start_time' => now()->addDays(1)->setTime(10, 0),
            'duration_minutes' => 45,
            'type' => 'individual',
            'format' => 'offline',
            'status' => 'planned',
            'organization_id' => $org1->id,
        ]);

        // Заявка
        Lead::create([
            'name' => 'Сидорова Анна',
            'contact' => '+7 (999) 888-77-66',
            'message' => 'Интересует консультация логопеда',
            'status' => 'new',
            'organization_id' => $org1->id,
        ]);

        // 4. Организация 2: Частный специалист "Анна"
        $annaUser = User::create([
            'name' => 'Анна Петрова',
            'email' => 'anna@logoped.ru',
            'password' => Hash::make('password'),
            'role' => 'specialist', // Частный специалист = specialist
            'organization_id' => $org2->id,
        ]);

        $anna = SpecialistProfile::create([
            'user_id' => $annaUser->id,
            'full_name' => 'Петрова Анна Сергеевна',
            'specialization' => 'Логопед-дефектолог',
            'about' => 'Частный логопед с 10-летним опытом.',
            'education' => 'МГППУ (2012)',
            'experience_years' => 10,
            'rating' => 4.9,
            'reviews_count' => 12,
            'price_per_session' => 3000,
            'available_online' => true,
            'available_offline' => true,
            'organization_id' => $org2->id,
        ]);

        // Клиент Анны
        $parent2User = User::create([
            'name' => 'Иванова М.А.',
            'email' => 'parent2@logoped.test',
            'password' => Hash::make('password'),
            'role' => 'parent',
            'organization_id' => $org2->id,
        ]);

        $parent2 = ParentProfile::create([
            'user_id' => $parent2User->id,
            'full_name' => 'Иванова Мария Александровна',
            'phone' => '+7 (999) 555-44-33',
            'organization_id' => $org2->id,
        ]);

        $child2 = Child::create([
            'full_name' => 'Иванов Дмитрий Петрович',
            'birth_date' => '2019-03-20',
            'parent_id' => $parent2->id,
            'specialist_id' => $anna->id,
            'anamnesis' => 'Дислалия',
            'goals' => ['Постановка звука Л'],
            'tags' => ['дислалия'],
            'organization_id' => $org2->id,
        ]);

        // 5. Организация 3: Центр "Говорун"
        $owner3 = User::create([
            'name' => 'Владелец Говорун',
            'email' => 'owner@govorun.ru',
            'password' => Hash::make('password'),
            'role' => 'organization',
            'organization_id' => $org3->id,
        ]);

        $spec3User = User::create([
            'name' => 'Кузнецова О.А.',
            'email' => 'specialist3@logoped.test',
            'password' => Hash::make('password'),
            'role' => 'specialist',
            'organization_id' => $org3->id,
        ]);

        $spec3 = SpecialistProfile::create([
            'user_id' => $spec3User->id,
            'full_name' => 'Кузнецова Ольга Александровна',
            'specialization' => 'Логопед-дефектолог, нейропсихолог',
            'about' => 'Комплексный подход к коррекции речевых нарушений.',
            'education' => 'МГППУ (2012)',
            'experience_years' => 12,
            'rating' => 5.0,
            'reviews_count' => 22,
            'price_per_session' => 3500,
            'available_online' => false,
            'available_offline' => true,
            'organization_id' => $org3->id,
        ]);

        // Отзывы
        Review::create([
            'user_id' => $parent1User->id,
            'specialist_id' => $spec1->id,
            'rating' => 5,
            'comment' => 'Отличный специалист!',
            'organization_id' => $org1->id,
        ]);

        Review::create([
            'user_id' => $parent2User->id,
            'specialist_id' => $anna->id,
            'rating' => 5,
            'comment' => 'Очень довольны результатом!',
            'organization_id' => $org2->id,
        ]);
    }
}
