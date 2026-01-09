<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Главная страница workspace (для Organization/Specialist/Parent)
Route::get('/lk', [DashboardController::class, 'home'])
    ->middleware(['auth', 'verified'])
    ->name('home');

// Старый маршрут для обратной совместимости
Route::get('/home', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes (только для SuperAdmin)
Route::middleware(['auth', 'superadmin'])->prefix('admin')->name('admin.')->group(function () {
    // Управление всеми организациями (только SuperAdmin)
    Route::resource('organizations', \App\Http\Controllers\Admin\OrganizationController::class);
    Route::patch('organizations/{organization}/toggle-active', [\App\Http\Controllers\Admin\OrganizationController::class, 'toggleActive'])
        ->name('organizations.toggle-active');
    
    // Управление всеми пользователями платформы (только SuperAdmin)
    Route::resource('users', UserController::class);
    
    // Управление всеми платежами платформы (только SuperAdmin)
    Route::resource('payments', \App\Http\Controllers\Admin\PaymentController::class);
    
    // Настройки платформы (только SuperAdmin)
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
});

// Workspace routes (Organization/Specialist/Parent) - все под префиксом /lk
Route::middleware(['auth'])->prefix('lk')->group(function () {
    // Аналитика
    Route::get('analytics', [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
    
    // Управление командой (только Organization)
    Route::resource('team', \App\Http\Controllers\TeamController::class);
    
    // Финансы организации (Organization и Specialist)
    Route::resource('payments', \App\Http\Controllers\PaymentController::class);
    
    // Отзывы (Organization и Specialist)
    Route::get('reviews', [\App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
    Route::put('reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Настройки организации (только Organization)
    Route::get('settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
    
    // Клиенты - Родители
    Route::resource('parents', \App\Http\Controllers\ParentController::class);
    
    // Клиенты - Дети
    Route::resource('children', \App\Http\Controllers\ChildController::class);
    Route::post('children/{child}/assign-specialist', [\App\Http\Controllers\ChildController::class, 'assignSpecialist'])
        ->name('children.assign-specialist');
    Route::patch('children/{child}/update-specialists', [\App\Http\Controllers\ChildController::class, 'updateSpecialists'])
        ->name('children.update-specialists');
    Route::patch('children/{child}/update-conclusion', [\App\Http\Controllers\ChildController::class, 'updateConclusion'])
        ->name('children.update-conclusion');
    Route::post('children/{child}/conclusions', [\App\Http\Controllers\ChildController::class, 'storeConclusion'])
        ->name('children.conclusions.store');
    Route::delete('children/{child}/conclusions/{conclusion}', [\App\Http\Controllers\ChildController::class, 'destroyConclusion'])
        ->name('children.conclusions.destroy');
    
    // Занятия
    Route::resource('sessions', \App\Http\Controllers\SessionController::class);
    Route::post('sessions/{session}/move', [\App\Http\Controllers\SessionController::class, 'move'])->name('sessions.move');
    Route::patch('sessions/{session}/update-status', [\App\Http\Controllers\SessionController::class, 'updateStatus'])->name('sessions.update-status');
    Route::patch('sessions/{session}/update-notes', [\App\Http\Controllers\SessionController::class, 'updateNotes'])->name('sessions.update-notes');
    Route::patch('sessions/{session}/update-games', [\App\Http\Controllers\SessionController::class, 'updateGames'])->name('sessions.update-games');
    
    // Календарь занятий
    Route::get('calendar', [\App\Http\Controllers\CalendarController::class, 'day'])->name('calendar.index');
    Route::get('calendar/week', [\App\Http\Controllers\CalendarController::class, 'day'])->name('calendar.day');
    Route::get('calendar/day', [\App\Http\Controllers\CalendarController::class, 'singleDay'])->name('calendar.single-day');
    
    // Отчеты по занятиям
    Route::get('sessions/{session}/reports/create', [\App\Http\Controllers\SessionReportController::class, 'create'])
        ->name('session-reports.create');
    Route::post('sessions/{session}/reports', [\App\Http\Controllers\SessionReportController::class, 'store'])
        ->name('session-reports.store');
    Route::get('session-reports/{sessionReport}/edit', [\App\Http\Controllers\SessionReportController::class, 'edit'])
        ->name('session-reports.edit');
    Route::put('session-reports/{sessionReport}', [\App\Http\Controllers\SessionReportController::class, 'update'])
        ->name('session-reports.update');
    Route::delete('session-reports/{sessionReport}', [\App\Http\Controllers\SessionReportController::class, 'destroy'])
        ->name('session-reports.destroy');
    
    // Домашние задания
    Route::resource('homeworks', \App\Http\Controllers\HomeworkController::class);
    
    // Заявки
    Route::get('leads', [\App\Http\Controllers\LeadController::class, 'index'])->name('leads.index');
    Route::get('leads/{lead}', [\App\Http\Controllers\LeadController::class, 'show'])->name('leads.show');
    Route::put('leads/{lead}', [\App\Http\Controllers\LeadController::class, 'update'])->name('leads.update');
});

// Просмотр профилей пользователей (для всех авторизованных)
Route::middleware(['auth'])->group(function () {
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
});

// Specialist profile routes (публичные)
// Каталог специалистов (доступен всем, включая неавторизованных)
Route::get('/specialists', [\App\Http\Controllers\SpecialistProfileController::class, 'catalog'])
    ->name('specialists.catalog');

// Публичный профиль специалиста (доступен всем)
Route::get('/specialists/{id}', [\App\Http\Controllers\SpecialistProfileController::class, 'show'])
    ->name('specialists.show');

// Публичный профиль организации (доступен всем)
Route::get('/organizations/{id}', [\App\Http\Controllers\OrganizationPublicController::class, 'show'])
    ->name('organizations.public.show');

// Specialist profile routes (требуют авторизации)
Route::middleware(['auth'])->group(function () {
    // Редактирование своего профиля (только для специалистов)
    Route::get('/my-profile/edit', [\App\Http\Controllers\SpecialistProfileController::class, 'edit'])
        ->name('specialists.edit');
    Route::put('/my-profile', [\App\Http\Controllers\SpecialistProfileController::class, 'update'])
        ->name('specialists.update');
    Route::post('/my-profile/schedule', [\App\Http\Controllers\SpecialistProfileController::class, 'saveSchedule'])
        ->name('specialists.schedule.save');
    Route::post('/my-profile/calendar', [\App\Http\Controllers\SpecialistProfileController::class, 'saveCalendar'])
        ->name('specialists.calendar.save');
    
    // Редактирование своей организации (только для владельцев организаций)
    Route::get('/my-organization/edit', [\App\Http\Controllers\OrganizationProfileController::class, 'edit'])
        ->name('organizations.edit-own');
    Route::put('/my-organization', [\App\Http\Controllers\OrganizationProfileController::class, 'update'])
        ->name('organizations.update-own');
    
    // Добавление отзыва (только для родителей)
    Route::post('/specialists/{id}/reviews', [\App\Http\Controllers\SpecialistProfileController::class, 'storeReview'])
        ->name('specialists.reviews.store');
    
    // Услуги (только для организаций и специалистов)
    Route::resource('services', \App\Http\Controllers\ServiceController::class);
});

require __DIR__.'/auth.php';
