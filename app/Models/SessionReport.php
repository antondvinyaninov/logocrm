<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionReport extends Model
{
    use HasFactory, BelongsToOrganization;

    protected $fillable = [
        'session_id',
        'goals_json',
        'comment',
        'rating',
        'organization_id',
    ];

    protected $casts = [
        'goals_json' => 'array',
    ];

    public function session()
    {
        return $this->belongsTo(TherapySession::class, 'session_id');
    }
}
