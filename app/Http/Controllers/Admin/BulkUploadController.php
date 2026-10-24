<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BulkDataImport;

class BulkUploadController extends Controller
{
    public function downloadTemplate($type)
    {
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename={$type}_template.csv"];
        $columns = ['name', 'description', 'address', 'city'];
        
        if ($type === 'attractions') $columns[] = 'ticket_price';
        if ($type === 'hotels') $columns[] = 'price_per_night';

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, array_fill(0, count($columns), 'Example Value'));
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $import = new BulkDataImport($request->type);
            Excel::import($import, $request->file('file'));
            $results = $import->getResults();
            
            $msg = "Upload complete! Inserted: {$results['inserted']}, Skipped: {$results['skipped']}.";
            if (count($results['errors']) > 0) $msg .= " Errors: " . implode(' | ', array_slice($results['errors'], 0, 3));
            
            return back()->with('success', $msg);
        } catch (\Exception $e) {
            return back()->with('danger', 'Error: ' . $e->getMessage());
        }
    }
}