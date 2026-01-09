<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialistProfile extends Model
{
    use HasFactory, BelongsToOrganization;

    protected $fillable = [
        'user_id',
        'full_name',
        'specialization',
        'position',
        'work_hours_json',
        'about',
        'education',
        'experience_years',
        'certificates',
        'photo',
        'rating',
        'reviews_count',
        'price_per_session',
        'available_online',
        'available_offline',
        'organization_id',
        'work_calendar',
    ];

    protected $casts = [
        'work_hours_json' => 'array',
        'certificates' => 'array',
        'experience_years' => 'integer',
        'rating' => 'decimal:2',
        'reviews_count' => 'integer',
        'price_per_session' => 'decimal:2',
        'available_online' => 'boolean',
        'available_offline' => 'boolean',
        'work_calendar' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function children()
    {
        return $this->hasMany(Child::class, 'specialist_id');
    }

    public function therapySessions()
    {
        return $this->hasMany(TherapySession::class, 'specialist_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'specialist_id');
    }

    public function schedules()
    {
        return $this->hasMany(SpecialistSchedule::class, 'specialist_id');
    }

    public function updateRating()
    {
        $this->rating = $this->reviews()->avg('rating') ?? 0;
        $this->reviews_count = $this->reviews()->count();
        $this->save();
    }
}
