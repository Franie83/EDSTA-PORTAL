<?php

namespace App\Imports;

use App\Models\ClubLounge;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClubsLoungesImport implements ToModel, WithHeadingRow
{
    public function model(array $row): \Illuminate\Database\Eloquent\Model|array|null
    {
        if (empty($row['name'])) { return null; }
        return new ClubLounge([
            'name'                => $row['name'] ?? null,
            'address'             => $row['address'] ?? null,
            'phone'               => $row['phone'] ?? null,
            'email'               => $row['email'] ?? null,
            'website'             => $row['website'] ?? null,
            'nearby_landmark'     => $row['nearby_landmark'] ?? null,
            'ownership'           => $row['ownership'] ?? null,
            'staff_strength'      => $row['staff_strength'] ?? null,
            'best_time_to_visit'  => $row['best_time_to_visit'] ?? null,
            'has_live_band'       => !empty($row['has_live_band']),
            'has_casino'          => !empty($row['has_casino']),
            'has_grills'          => !empty($row['has_grills']),
            'has_dance_floor'     => !empty($row['has_dance_floor']),
            'has_indoor_bar'      => !empty($row['has_indoor_bar']),
            'has_outdoor_bar'     => !empty($row['has_outdoor_bar']),
            'has_dj_booth'        => !empty($row['has_dj_booth']),
            'has_vip_section'     => !empty($row['has_vip_section']),
            'has_light_sound'     => !empty($row['has_light_sound']),
            'has_cctv'            => !empty($row['has_cctv']),
            'has_swimming_pool'   => !empty($row['has_swimming_pool']),
            'has_lodging'         => !empty($row['has_lodging']),
            'booking_terms'       => $row['booking_terms'] ?? null,
            'gate_fee_regular'    => $row['gate_fee_regular'] ?? null,
            'gate_fee_vip'        => $row['gate_fee_vip'] ?? null,
            'accommodation_fees'  => $row['accommodation_fees'] ?? null,
            'image_url'           => $row['image_url'] ?? null,
            'is_featured'         => !empty($row['is_featured']),
            'is_active'           => !empty($row['is_active']) ? (bool)$row['is_active'] : true,
        ]);
    }
}