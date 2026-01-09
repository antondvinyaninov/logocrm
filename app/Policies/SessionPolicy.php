<?php

namespace App\Policies;

use App\Models\TherapySession;
use App\Models\User;

class SessionPolicy
{
    /**
     * Просмотр списка занятий
     */
    public function viewAny(User $user): bool
    {
        // Все авторизованные пользователи могут видеть занятия
        return true;
    }

    /**
     * Просмотр конкретного занятия
     */
    public function view(User $user, TherapySession $session): bool
    {
        // Admin видит всё
        if ($user->isAdmin()) {
            return true;
        }

        // Specialist видит свои занятия
        if ($user->isSpecialist()) {
            return $session->specialist_id === $user->specialistProfile->id;
        }

        // Parent видит занятия своих детей
        if ($user->isParent()) {
            return $session->child->parent_id === $user->parentProfile->id;
        }

        return false;
    }

    /**
     * Создание занятия
     */
    public function create(User $user): bool
    {
        // Только Admin и Specialist могут создавать занятия
        return $user->isAdmin() || $user->isSpecialist();
    }

    /**
     * Редактирование занятия
     */
    public function update(User $user, TherapySession $session): bool
    {
        // Admin может редактировать всё
        if ($user->isAdmin()) {
            return true;
        }

        // Specialist может редактировать только свои занятия
        if ($user->isSpecialist()) {
            return $session->specialist_id === $user->specialistProfile->id;
        }

        return false;
    }

    /**
     * Удаление занятия
     */
    public function delete(User $user, TherapySession $session): bool
    {
        // Admin может удалять всё
        if ($user->isAdmin()) {
            return true;
        }

        // Specialist может удалять только свои запланированные занятия
        if ($user->isSpecialist()) {
            return $session->specialist_id === $user->specialistProfile->id 
                && $session->status === 'planned';
        }

        return false;
    }
}
