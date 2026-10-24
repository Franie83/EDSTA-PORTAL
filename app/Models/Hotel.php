<?php

namespace App\Models;

use App\Helpers\Helpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Hotel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'slug', 'description', 'address', 'city',
        'latitude', 'longitude', 'image_url', 'gallery', 'website', 'phone', 'email',
        'stars', 'price_per_night', 'is_featured', 'is_active', 'status',
        'amenities', 'check_in_time', 'check_out_time',
        'phone', 'email', 'website', 'nearby_landmark', 'star_rating', 'ownership', 'staff_strength',
        'rooms_total', 'rooms_functional', 'rooms_suites',
        'has_swimming_pool', 'has_gym', 'has_spa', 'has_wifi', 'has_cctv',
        'has_event_hall', 'has_live_band', 'has_parking', 'has_concierge',
        'has_laundry', 'has_first_aid', 'has_emergency_exit', 'has_fire_extinguisher',
        'price_range_feeding', 'recreation_offerings', 'booking_terms',
    ];

    protected $casts = [
        'gallery'        => 'array',
        'is_featured'    => 'boolean',
        'is_active'      => 'boolean',
        'price_per_night'=> 'decimal:2',
        'stars'          => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function ($h) {
            if (!$h->slug) $h->slug = Str::slug($h->name) . '-' . Str::random(4);
        });
    }

    public function getAvgRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') - 0, 1);
    }

    public function getImageUrlDisplayAttribute(): string
    {
        return Helpers::imageUrl($this->image_url);
    }

    public function user()       { return $this->belongsTo(User::class); }
    public function rooms()      { return $this->hasMany(HotelRoom::class); }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'target_id')
                    ->where('target_type', 'Hotel')
                    ->where('is_approved', true);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'target_id')->where('booking_type', 'Hotel');
    }
}
