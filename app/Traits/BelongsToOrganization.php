<?php

namespace App\Traits;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToOrganization
{
    /**
     * Boot the trait
     */
    protected static function bootBelongsToOrganization(): void
    {
        // Автоматически добавляем organization_id при создании записи
        static::creating(function ($model) {
            if (auth()->check() && !$model->organization_id) {
                $user = auth()->user();
                
                // Если у пользователя есть organization_id, используем его
                if ($user->organization_id) {
                    $model->organization_id = $user->organization_id;
                }
            }
        });

        // Глобальный скоуп для автоматической фильтрации по organization_id
        // (не применяется для superadmin и неавторизованных пользователей)
        static::addGlobalScope('organization', function (Builder $query) {
            // Проверяем, что пользователь авторизован
            if (!auth()->check()) {
                return;
            }
            
            try {
                $user = auth()->user();
                
                // Проверяем, что у пользователя есть роль
                if (!$user || !isset($user->role)) {
                    return;
                }
                
                // SuperAdmin видит все данные
                if ($user->role === 'superadmin') {
                    return;
                }
                
                // Остальные видят только данные своей организации
                if ($user->organization_id) {
                    $query->where($query->getModel()->getTable() . '.organization_id', $user->organization_id);
                }
            } catch (\Exception $e) {
                // Если произошла ошибка при получении пользователя, просто пропускаем фильтрацию
                return;
            }
        });
    }

    /**
     * Отношение к организации
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
