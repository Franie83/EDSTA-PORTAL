<?php
namespace App\Imports;
use App\Models\Guide;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuidesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Guide([
            'user_id' => $row['user_id'] ?? null,
            'bio' => $row['bio'] ?? null,
            'experience' => $row['experience'] ?? null,
            'hourly_rate' => $row['hourly_rate'] ?? null
        ]);
    }
}