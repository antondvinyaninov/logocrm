<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildSpecialistHistory extends Model
{
    use HasFactory, BelongsToOrganization;

    protected $table = 'child_specialist_history';

    protected $fillable = [
        'child_id',
        'specialist_id',
        'organization_id',
        'started_at',
        'ended_at',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'date',
        'ended_at' => 'date',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function specialist()
    {
        return $this->belongsTo(SpecialistProfile::class);
    }
}
