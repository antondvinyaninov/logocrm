<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'organization_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function specialistProfile()
    {
        return $this->hasOne(SpecialistProfile::class);
    }

    public function parentProfile()
    {
        return $this->hasOne(ParentProfile::class);
    }

    // Отношение к организации
    public function organization()
    {
        return $this->belongsTo(\App\Models\Organization::class);
    }

    // Проверка ролей (обновлено для мультитенантности)
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isOrganization(): bool
    {
        return $this->role === 'organization';
    }

    public function isSpecialist(): bool
    {
        return $this->role === 'specialist';
    }

    public function isParent(): bool
    {
        return $this->role === 'parent';
    }

    // Для обратной совместимости (временно)
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'organization' || $this->role === 'superadmin';
    }

    // Проверка, есть ли у пользователя учетная запись (пароль)
    public function hasAccount(): bool
    {
        return !is_null($this->password);
    }
}
