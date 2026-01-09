<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory, BelongsToOrganization;

    protected $fillable = [
        'name',
        'contact',
        'message',
        'status',
        'notes',
        'organization_id',
    ];
}
