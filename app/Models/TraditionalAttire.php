<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraditionalAttire extends Model
{
    protected $table = 'traditional_attire';

    protected $fillable = [
        'ethnic_group',
        'gender',
        'attire_items',
        'description',
        'occasions',
        'notes',
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