<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClubLounge extends Model
{
    protected $table = 'clubs_lounges';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'website',
        'nearby_landmark',
        'ownership',
        'staff_strength',
        'best_time_to_visit',
        'has_live_band',
        'has_casino',
        'has_grills',
        'has_dance_floor',
        'has_indoor_bar',
        'has_outdoor_bar',
        'has_dj_booth',
        'has_vip_section',
        'has_light_sound',
        'has_cctv',
        'has_swimming_pool',
        'has_lodging',
        'booking_terms',
        'gate_fee_regular',
        'gate_fee_vip',
        'accommodation_fees',
        'image_url',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'has_live_band'     => 'boolean',
            'has_casino'        => 'boolean',
            'has_grills'        => 'boolean',
            'has_dance_floor'   => 'boolean',
            'has_indoor_bar'    => 'boolean',
            'has_outdoor_bar'   => 'boolean',
            'has_dj_booth'      => 'boolean',
            'has_vip_section'   => 'boolean',
            'has_light_sound'   => 'boolean',
            'has_cctv'          => 'boolean',
            'has_swimming_pool' => 'boolean',
            'has_lodging'       => 'boolean',
            'is_featured'       => 'boolean',
            'is_active'         => 'boolean',
        ];
    }
}