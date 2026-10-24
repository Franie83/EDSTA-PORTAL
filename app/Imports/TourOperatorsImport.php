<?php

namespace App\Imports;

use App\Models\TourOperator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TourOperatorsImport implements ToModel, WithHeadingRow
{
    public function model(array $row): \Illuminate\Database\Eloquent\Model|array|null
    {
        if (empty($row['name'])) { return null; }
        return new TourOperator([
            'name'                          => $row['name'] ?? null,
            'address'                       => $row['address'] ?? null,
            'phone'                         => $row['phone'] ?? null,
            'email'                         => $row['email'] ?? null,
            'website'                       => $row['website'] ?? null,
            'nearby_landmark'               => $row['nearby_landmark'] ?? null,
            'ownership'                     => $row['ownership'] ?? null,
            'staff_strength'                => $row['staff_strength'] ?? null,
            'scope_of_service'              => $row['scope_of_service'] ?? null,
            'service_availability'          => $row['service_availability'] ?? null,
            'age_limit'                     => $row['age_limit'] ?? null,
            'cost_range_edo_south'          => $row['cost_range_edo_south'] ?? null,
            'cost_range_edo_north'          => $row['cost_range_edo_north'] ?? null,
            'cost_range_edo_central'        => $row['cost_range_edo_central'] ?? null,
            'aware_of_edsta'                => !empty($row['aware_of_edsta']) ? (bool)$row['aware_of_edsta'] : false,
            'suggestions'                   => $row['suggestions'] ?? null,
            'yearly_interstate_visitors'    => isset($row['yearly_interstate_visitors']) && is_numeric($row['yearly_interstate_visitors']) ? (int)$row['yearly_interstate_visitors'] : null,
            'yearly_international_visitors' => isset($row['yearly_international_visitors']) && is_numeric($row['yearly_international_visitors']) ? (int)$row['yearly_international_visitors'] : null,
            'image_url'                     => $row['image_url'] ?? null,
            'is_featured'                   => !empty($row['is_featured']) ? (bool)$row['is_featured'] : false,
            'is_active'                     => !empty($row['is_active']) ? (bool)$row['is_active'] : true,
        ]);
    }
}