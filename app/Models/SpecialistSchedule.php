<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialistSchedule extends Model
{
    use HasFactory, BelongsToOrganization;

    protected $fillable = [
        'specialist_id',
        'organization_id',
        'day_of_week',
        'start_time',
        'end_time',
        'break_start',
        'break_end',
        'repeat_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function specialist()
    {
        return $this->belongsTo(SpecialistProfile::class, 'specialist_id');
    }
}
