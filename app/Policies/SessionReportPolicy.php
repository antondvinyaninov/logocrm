<?php

namespace App\Policies;

use App\Models\SessionReport;
use App\Models\User;

class SessionReportPolicy
{
    /**
     * Просмотр отчёта
     */
    public function view(User $user, SessionReport $report): bool
    {
        // Admin видит всё
        if ($user->isAdmin()) {
            return true;
        }

        // Specialist видит отчёты по своим занятиям
        if ($user->isSpecialist()) {
            return $report->session->specialist_id === $user->specialistProfile->id;
        }

        // Parent видит отчёты по занятиям своих детей
        if ($user->isParent()) {
            return $report->session->child->parent_id === $user->parentProfile->id;
        }

        return false;
    }

    /**
     * Создание отчёта
     */
    public function create(User $user): bool
    {
        // Только Specialist и Admin могут создавать отчёты
        return $user->isSpecialist() || $user->isAdmin();
    }

    /**
     * Редактирование отчёта
     */
    public function update(User $user, SessionReport $report): bool
    {
        // Admin может редактировать всё
        if ($user->isAdmin()) {
            return true;
        }

        // Specialist может редактировать только свои отчёты
        if ($user->isSpecialist()) {
            return $report->session->specialist_id === $user->specialistProfile->id;
        }

        return false;
    }

    /**
     * Удаление отчёта
     */
    public function delete(User $user, SessionReport $report): bool
    {
        // Только Admin может удалять отчёты
        return $user->isAdmin();
    }
}
