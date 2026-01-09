<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory, BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'price',
        'duration_minutes',
        'session_type',
        'format',
        'max_participants',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_minutes' => 'integer',
        'max_participants' => 'integer',
        'is_active' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function getSessionTypeNameAttribute()
    {
        return match($this->session_type) {
            'individual' => 'Индивидуальное',
            'group' => 'Групповое',
            default => $this->session_type,
        };
    }

    public function getFormatNameAttribute()
    {
        return match($this->format) {
            'online' => 'Онлайн',
            'offline' => 'Офлайн',
            'both' => 'Онлайн/Офлайн',
            default => $this->format,
        };
    }
}
