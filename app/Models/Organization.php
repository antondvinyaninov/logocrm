<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'type',
        'email',
        'phone',
        'address',
        'description',
        'website',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Отношения
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function specialists(): HasMany
    {
        return $this->hasMany(SpecialistProfile::class);
    }

    public function parents(): HasMany
    {
        return $this->hasMany(ParentProfile::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Child::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(TherapySession::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function homeworks(): HasMany
    {
        return $this->hasMany(Homework::class);
    }

    public function sessionReports(): HasMany
    {
        return $this->hasMany(SessionReport::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Скоупы
    public function scopeCenters($query)
    {
        return $query->where('type', 'center');
    }

    public function scopeIndividuals($query)
    {
        return $query->where('type', 'individual');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Вспомогательные методы
    public function isCenter(): bool
    {
        return $this->type === 'center';
    }

    public function isIndividual(): bool
    {
        return $this->type === 'individual';
    }
}
