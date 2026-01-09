<?php

namespace App\Policies;

use App\Models\Child;
use App\Models\User;

class ChildPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'specialist', 'parent']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Child $child): bool
    {
        if ($user->isSuperAdmin() || $user->isOrganization()) {
            return true;
        }

        if ($user->role === 'specialist' && $user->specialistProfile) {
            return $child->specialist_id === $user->specialistProfile->id;
        }

        if ($user->role === 'parent' && $user->parentProfile) {
            return $child->parent_id === $user->parentProfile->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'specialist', 'parent']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Child $child): bool
    {
        if ($user->isSuperAdmin() || $user->isOrganization()) {
            return true;
        }

        if ($user->role === 'specialist' && $user->specialistProfile) {
            return $child->specialist_id === $user->specialistProfile->id;
        }

        if ($user->role === 'parent' && $user->parentProfile) {
            // Родитель может редактировать своих детей (включая выбор специалиста)
            return $child->parent_id === $user->parentProfile->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Child $child): bool
    {
        // Только superadmin и organization могут удалять детей
        return $user->isSuperAdmin() || $user->isOrganization();
    }
}
