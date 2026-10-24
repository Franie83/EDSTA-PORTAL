<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BulkDataImport implements ToCollection, WithHeadingRow
{
    protected $type;
    protected $inserted = 0;
    protected $skipped = 0;
    protected $errors = [];

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNum = $index + 2;
            try {
                $this->processRow($row->toArray(), $rowNum);
                $this->inserted++;
            } catch (\Exception $e) {
                $this->skipped++;
                $this->errors[] = "Row {$rowNum}: " . $e->getMessage();
            }
        }
    }

    private function processRow(array $row, int $rowNum)
    {
        if (empty($row['name'])) throw new \Exception("Name is required.");

        $name = $row['name'];
        $slug = Str::slug($name) . '-' . uniqid();

        switch ($this->type) {
            case 'attractions':
                \App\Models\Attraction::create(['name' => $name, 'slug' => $slug, 'description' => $row['description'] ?? '', 'address' => $row['address'] ?? '', 'city' => $row['city'] ?? 'Benin City', 'ticket_price' => $row['ticket_price'] ?? 0, 'is_active' => 1, 'status' => 'approved', 'user_id' => Auth::id() ?? 1]);
                break;
            case 'hotels':
                \App\Models\Hotel::create(['name' => $name, 'slug' => $slug, 'description' => $row['description'] ?? '', 'address' => $row['address'] ?? '', 'city' => $row['city'] ?? 'Benin City', 'price_per_night' => $row['price_per_night'] ?? 0, 'is_active' => 1, 'status' => 'approved', 'user_id' => Auth::id() ?? 1]);
                break;
            case 'restaurants':
                \App\Models\Restaurant::create(['name' => $name, 'slug' => $slug, 'description' => $row['description'] ?? '', 'address' => $row['address'] ?? '', 'city' => $row['city'] ?? 'Benin City', 'is_active' => 1, 'status' => 'approved', 'user_id' => Auth::id() ?? 1]);
                break;
        }
    }

    public function getResults() {
        return ['inserted' => $this->inserted, 'skipped' => $this->skipped, 'errors' => $this->errors];
    }
}