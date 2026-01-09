<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory, BelongsToOrganization;

    protected $fillable = [
        'full_name',
        'birth_date',
        'parent_id',
        'specialist_id',
        'anamnesis',
        'goals',
        'tags',
        'organization_id',
        'other_specialists',
        'specialist_conclusion',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'goals' => 'array',
        'tags' => 'array',
        'other_specialists' => 'array',
    ];

    protected $appends = ['name', 'age', 'diagnosis', 'therapy_goals'];

    // Accessor для name (алиас для full_name)
    public function getNameAttribute()
    {
        return $this->full_name;
    }

    // Accessor для age
    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }
        return $this->birth_date->age;
    }

    // Accessor для diagnosis (из anamnesis)
    public function getDiagnosisAttribute()
    {
        return $this->anamnesis;
    }

    // Accessor для therapy_goals (из goals)
    public function getTherapyGoalsAttribute()
    {
        if (is_array($this->goals)) {
            return implode(', ', $this->goals);
        }
        return $this->goals;
    }

    public function parent()
    {
        return $this->belongsTo(ParentProfile::class, 'parent_id');
    }

    public function specialist()
    {
        return $this->belongsTo(SpecialistProfile::class, 'specialist_id');
    }

    public function therapySessions()
    {
        return $this->hasMany(TherapySession::class);
    }

    public function homeworks()
    {
        return $this->hasMany(Homework::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function conclusions()
    {
        return $this->hasMany(SpecialistConclusion::class)->orderBy('created_at', 'desc');
    }

    public function specialistHistory()
    {
        return $this->hasMany(ChildSpecialistHistory::class)->orderBy('started_at', 'desc');
    }
}
