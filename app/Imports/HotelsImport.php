<?php
namespace App\Imports;
use App\Models\Hotel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HotelsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Hotel([
            'user_id' => $row['user_id'] ?? null,
            'name' => $row['name'] ?? null,
            'description' => $row['description'] ?? null,
            'city' => $row['city'] ?? null,
            'price_per_night' => $row['price_per_night'] ?? null
        ]);
    }
}