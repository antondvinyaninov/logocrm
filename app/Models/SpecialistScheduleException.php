<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialistScheduleException extends Model
{
    use HasFactory, BelongsToOrganization;

    protected $fillable = [
        'specialist_id',
        'organization_id',
        'exception_date',
        'type',
        'start_time',
        'end_time',
        'note',
    ];

    protected $casts = [
        'exception_date' => 'date',
    ];

    public function specialist()
    {
        return $this->belongsTo(SpecialistProfile::class, 'specialist_id');
    }
}
