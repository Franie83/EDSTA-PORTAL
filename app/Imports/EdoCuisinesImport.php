<?php

namespace App\Imports;

use App\Models\EdoCuisine;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EdoCuisinesImport implements ToModel, WithHeadingRow
{
    public function model(array $row): \Illuminate\Database\Eloquent\Model|array|null
    {
        if (empty($row['name'])) { return null; }
        return new EdoCuisine([
            'name'                => $row['name'] ?? null,
            'description'         => $row['description'] ?? null,
            'ingredients'         => $row['ingredients'] ?? null,
            'commonly_eaten_with' => $row['commonly_eaten_with'] ?? null,
            'area'                => $row['area'] ?? null,
            'sort_order'          => $row['sort_order'] ?? 0,
            'is_active'           => !empty($row['is_active']) ? (bool)$row['is_active'] : true,
        ]);
    }
}