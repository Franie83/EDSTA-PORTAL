<?php
namespace App\Imports;
use App\Models\News;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NewsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new News([
            'title' => $row['title'] ?? null,
            'content' => $row['content'] ?? null,
            'author' => $row['author'] ?? null
        ]);
    }
}