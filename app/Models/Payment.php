<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, BelongsToOrganization;

    protected $fillable = [
        'child_id',
        'amount',
        'payment_date',
        'payment_method',
        'status',
        'notes',
        'description',
        'organization_id',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function parent()
    {
        return $this->hasOneThrough(
            ParentProfile::class,
            Child::class,
            'id',
            'id',
            'child_id',
            'parent_id'
        );
    }
}
