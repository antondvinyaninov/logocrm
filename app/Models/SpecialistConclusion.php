<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialistConclusion extends Model
{
    use HasFactory, BelongsToOrganization;

    protected $fillable = [
        'child_id',
        'specialist_id',
        'organization_id',
        'content',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
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
