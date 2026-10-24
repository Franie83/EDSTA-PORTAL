<?php
namespace App\Imports;
use App\Models\Restaurant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RestaurantsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Restaurant([
            'user_id' => $row['user_id'] ?? null,
            'name' => $row['name'] ?? null,
            'description' => $row['description'] ?? null,
            'city' => $row['city'] ?? null,
            'cuisine_type' => $row['cuisine_type'] ?? null
        ]);
    }
}