<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TherapySession extends Model
{
    use HasFactory, BelongsToOrganization;

    protected $fillable = [
        'child_id',
        'specialist_id',
        'start_time',
        'duration_minutes',
        'price',
        'type',
        'format',
        'status',
        'payment_status',
        'paid_at',
        'organization_id',
        'notes',
        'service_id',
        'specialist_notes',
        'games_used',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'paid_at' => 'date',
        'games_used' => 'array',
        'price' => 'decimal:2',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function specialist()
    {
        return $this->belongsTo(SpecialistProfile::class, 'specialist_id');
    }

    public function report()
    {
        return $this->hasOne(SessionReport::class, 'session_id');
    }

    public function homeworks()
    {
        return $this->hasMany(Homework::class, 'session_id');
    }

    // Scopes для фильтрации
    public function scopeForSpecialist($query, $specialistId)
    {
        return $query->where('specialist_id', $specialistId);
    }

    public function scopeForChild($query, $childId)
    {
        return $query->where('child_id', $childId);
    }

    public function scopeForParent($query, $parentId)
    {
        return $query->whereHas('child', function($q) use ($parentId) {
            $q->where('parent_id', $parentId);
        });
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>=', now())
            ->where('status', 'planned')
            ->orderBy('start_time');
    }

    public function scopePast($query)
    {
        return $query->where('start_time', '<', now())
            ->orderBy('start_time', 'desc');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_time', [$startDate, $endDate]);
    }
}
