<?php

namespace App\Imports;

use App\Models\Attraction;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Database\Eloquent\Model;

class AttractionsImport implements ToModel, WithHeadingRow
{
    public function model(array $row): Model|array|null
    {
        if (empty($row['name'])) { return null; }

        // Resolve category_id: prefer explicit category_id, then category_name lookup
        $categoryId = null;
        if (!empty($row['category_id']) && is_numeric($row['category_id'])) {
            $categoryId = (int)$row['category_id'];
        } elseif (!empty($row['category_name'])) {
            $category = Category::where('name', trim($row['category_name']))->first();
            if ($category) { $categoryId = $category->id; }
        }

        // Defensive numeric defaults — many DB columns are NOT NULL
        $ticketPrice = (isset($row['ticket_price']) && is_numeric($row['ticket_price'])) ? $row['ticket_price'] : 0;
        $latitude    = (isset($row['latitude'])     && is_numeric($row['latitude']))     ? $row['latitude']    : 0;
        $longitude   = (isset($row['longitude'])    && is_numeric($row['longitude']))    ? $row['longitude']   : 0;

        return new Attraction([
            'name'                     => $row['name'] ?? null,
            'description'              => $row['description'] ?? null,
            'history'                  => $row['history'] ?? null,
            'historical_background'    => $row['historical_background'] ?? null,
            'unique_selling_points'    => $row['unique_selling_points'] ?? null,
            'ownership'                => $row['ownership'] ?? null,
            'investment_opportunities' => $row['investment_opportunities'] ?? null,
            'visiting_tips'            => $row['visiting_tips'] ?? null,
            'landmarks'                => $row['landmarks'] ?? null,
            'proximity_from_city'      => $row['proximity_from_city'] ?? null,
            'address'                  => $row['address'] ?? null,
            'city'                     => $row['city'] ?? null,
            'ticket_price'             => $ticketPrice,
            'opening_hours'            => $row['opening_hours'] ?? null,
            'latitude'                 => $latitude,
            'longitude'                => $longitude,
            'website'                  => $row['website'] ?? null,
            'phone'                    => $row['phone'] ?? null,
            'image_url'                => $row['image_url'] ?? null,
            'category_id'              => $categoryId,
            'user_id'                  => (isset($row['user_id']) && is_numeric($row['user_id'])) ? (int)$row['user_id'] : 1,
            'is_featured'              => !empty($row['is_featured']) ? (bool)$row['is_featured'] : false,
            'is_active'                => !empty($row['is_active']) ? (bool)$row['is_active'] : true,
        ]);
    }
}