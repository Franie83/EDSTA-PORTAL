<?php

namespace App\Imports;

use App\Models\TraditionalAttire;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TraditionalAttiresImport implements ToModel, WithHeadingRow
{
    public function model(array $row): \Illuminate\Database\Eloquent\Model|array|null
    {
        if (empty($row['ethnic_group'])) { return null; }
        return new TraditionalAttire([
            'ethnic_group' => $row['ethnic_group'] ?? null,
            'gender'       => $row['gender'] ?? 'unisex',
            'attire_items' => $row['attire_items'] ?? null,
            'description'  => $row['description'] ?? null,
            'occasions'    => $row['occasions'] ?? null,
            'notes'        => $row['notes'] ?? null,
            'sort_order'   => $row['sort_order'] ?? 0,
            'is_active'    => !empty($row['is_active']) ? (bool)$row['is_active'] : true,
        ]);
    }
}