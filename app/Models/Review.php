<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory, BelongsToOrganization;

    protected $fillable = [
        'user_id',
        'specialist_id',
        'rating',
        'comment',
        'organization_id',
        'response',
        'response_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'response_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialist()
    {
        return $this->belongsTo(SpecialistProfile::class, 'specialist_id');
    }
}
