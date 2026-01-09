<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use HasFactory, BelongsToOrganization;

    protected $table = 'homeworks';

    protected $fillable = [
        'child_id',
        'session_id',
        'title',
        'description',
        'resource_url',
        'status',
        'organization_id',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function session()
    {
        return $this->belongsTo(TherapySession::class, 'session_id');
    }
}
