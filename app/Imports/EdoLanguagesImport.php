<?php

namespace App\Imports;

use App\Models\EdoLanguage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EdoLanguagesImport implements ToModel, WithHeadingRow
{
    public function model(array $row): \Illuminate\Database\Eloquent\Model|array|null
    {
        if (empty($row['name'])) { return null; }
        return new EdoLanguage([
            'name'       => $row['name'] ?? null,
            'speakers'   => $row['speakers'] ?? null,
            'area'       => $row['area'] ?? null,
            'lga_list'   => $row['lga_list'] ?? null,
            'notes'      => $row['notes'] ?? null,
            'sort_order' => $row['sort_order'] ?? 0,
            'is_active'  => !empty($row['is_active']) ? (bool)$row['is_active'] : true,
        ]);
    }
}