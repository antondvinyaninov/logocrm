<?php

namespace App\Policies;

use App\Models\Homework;
use App\Models\User;

class HomeworkPolicy
{
    /**
     * Просмотр домашнего задания
     */
    public function view(User $user, Homework $homework): bool
    {
        // Admin видит всё
        if ($user->isAdmin()) {
            return true;
        }

        // Specialist видит ДЗ своих клиентов
        if ($user->isSpecialist()) {
            return $homework->child->specialist_id === $user->specialistProfile->id;
        }

        // Parent видит ДЗ своих детей
        if ($user->isParent()) {
            return $homework->child->parent_id === $user->parentProfile->id;
        }

        return false;
    }

    /**
     * Создание ДЗ
     */
    public function create(User $user): bool
    {
        // Только Specialist и Admin могут создавать ДЗ
        return $user->isSpecialist() || $user->isAdmin();
    }

    /**
     * Редактирование ДЗ
     */
    public function update(User $user, Homework $homework): bool
    {
        // Admin может редактировать всё
        if ($user->isAdmin()) {
            return true;
        }

        // Specialist может редактировать ДЗ своих клиентов
        if ($user->isSpecialist()) {
            return $homework->child->specialist_id === $user->specialistProfile->id;
        }

        // Parent может отмечать выполнение ДЗ своих детей
        if ($user->isParent()) {
            return $homework->child->parent_id === $user->parentProfile->id;
        }

        return false;
    }

    /**
     * Удаление ДЗ
     */
    public function delete(User $user, Homework $homework): bool
    {
        // Admin может удалять всё
        if ($user->isAdmin()) {
            return true;
        }

        // Specialist может удалять ДЗ своих клиентов
        if ($user->isSpecialist()) {
            return $homework->child->specialist_id === $user->specialistProfile->id;
        }

        return false;
    }
}
