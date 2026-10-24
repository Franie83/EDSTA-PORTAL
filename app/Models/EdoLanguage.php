<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EdoLanguage extends Model
{
    protected $table = 'edo_languages';

    protected $fillable = [
        'name',
        'speakers',
        'area',
        'lga_list',
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