<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EdoCuisine extends Model
{
    protected $table = 'edo_cuisine';

    protected $fillable = [
        'name',
        'description',
        'ingredients',
        'commonly_eaten_with',
        'area',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active'  => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}