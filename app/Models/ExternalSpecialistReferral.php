<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalSpecialistReferral extends Model
{
    use HasFactory, BelongsToOrganization;

    protected $fillable = [
        'child_id',
        'organization_id',
        'created_by',
        'specialist_type',
        'reason',
        'referral_date',
        'appointment_date',
        'status',
        'visit_date',
        'results',
        'recommendations',
        'attachments',
    ];

    protected $casts = [
        'referral_date' => 'date',
        'appointment_date' => 'date',
        'visit_date' => 'date',
        'attachments' => 'array',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Получить статус на русском
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Ожидает записи',
            'scheduled' => 'Запись назначена',
            'completed' => 'Посещение состоялось',
            'cancelled' => 'Отменено',
            default => $this->status,
        };
    }

    // Получить цвет статуса для UI
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'orange',
            'scheduled' => 'blue',
            'completed' => 'green',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }
}
