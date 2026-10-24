<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourOperator extends Model
{
    protected $table = 'tour_operators';

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'website',
        'nearby_landmark',
        'ownership',
        'staff_strength',
        'scope_of_service',
        'service_availability',
        'age_limit',
        'cost_range_edo_south',
        'cost_range_edo_north',
        'cost_range_edo_central',
        'aware_of_edsta',
        'suggestions',
        'yearly_interstate_visitors',
        'yearly_international_visitors',
        'image_url',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'aware_of_edsta'  => 'boolean',
            'is_featured'     => 'boolean',
            'is_active'       => 'boolean',
            'yearly_interstate_visitors'    => 'integer',
            'yearly_international_visitors' => 'integer',
        ];
    }
}