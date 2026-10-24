<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Imports\TourOperatorsImport;
use App\Models\TourOperator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class TourOperatorController extends Controller
{
    public function index()
    {
        $operators = TourOperator::orderBy('name')->paginate(50);
        return view('admin.tour-operators.index', compact('operators'));
    }

    public function create()
    {
        $operator = new TourOperator(['is_active' => true]);
        return view('admin.tour-operators.form', compact('operator'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        TourOperator::create($data);
        Helpers::logAction('admin_create_tour_operator', 'TourOperator', null, 'Created tour operator: ' . $data['name']);
        return redirect()->route('admin.tour-operators.index')->with('success', 'Tour operator created.');
    }

    public function edit(TourOperator $operator)
    {
        return view('admin.tour-operators.form', compact('operator'));
    }

    public function update(Request $request, TourOperator $operator)
    {
        $data = $this->validated($request);
        $operator->update($data);
        Helpers::logAction('admin_update_tour_operator', 'TourOperator', $operator->id, 'Updated tour operator: ' . $data['name']);
        return redirect()->route('admin.tour-operators.index')->with('success', 'Tour operator updated.');
    }

    public function destroy(TourOperator $operator)
    {
        $operator->delete();
        Helpers::logAction('admin_delete_tour_operator', 'TourOperator', $operator->id, 'Deleted tour operator: ' . $operator->name);
        return back()->with('success', 'Tour operator deleted.');
    }

    public function downloadTemplate()
    {
        $headers = [
            'name', 'address', 'phone', 'email', 'website', 'nearby_landmark',
            'ownership', 'staff_strength', 'scope_of_service', 'service_availability', 'age_limit',
            'cost_range_edo_south', 'cost_range_edo_north', 'cost_range_edo_central',
            'aware_of_edsta', 'suggestions',
            'yearly_interstate_visitors', 'yearly_international_visitors',
            'image_url', 'is_featured', 'is_active'
        ];

        return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
            private $headers;
            public function __construct($headers) { $this->headers = $headers; }
            public function array(): array {
                return [
                    $this->headers,
                    ['Example Tours Ltd', '12 Airport Road, Benin City', '08012345678', 'info@example.com', 'https://example.com', 'Near Kings Square', 'private', '10-20', 'Edo South, Edo Central', 'All year round', 'No age limit', '₦15,000-₦50,000', '₦20,000-₦60,000', '₦10,000-₦40,000', '1', 'More signage at tourist sites', '500', '50', 'https://images.unsplash.com/...', '0', '1'],
                ];
            }
        }, 'tour_operators_template.xlsx');
    }

    public function bulkUpload(Request $request)
    {
        $request->validate(['excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120']);

        try {
            Excel::import(new TourOperatorsImport, $request->file('excel_file'));
            Helpers::logAction('admin_bulk_upload_tour_operators', 'TourOperator', null, 'Uploaded tour operators via Excel');
            return redirect()->route('admin.tour-operators.index')->with('success', 'Tour operators imported successfully!');
        } catch (\Exception $e) {
            Log::error('Tour operator bulk upload failed: ' . $e->getMessage());
            return redirect()->route('admin.tour-operators.index')->with('danger', 'Error importing file: ' . $e->getMessage());
        }
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'                          => 'required|string|max:120',
            'address'                       => 'nullable|string|max:255',
            'phone'                         => 'nullable|string|max:40',
            'email'                         => 'nullable|email|max:120',
            'website'                       => 'nullable|string|max:255',
            'nearby_landmark'               => 'nullable|string|max:255',
            'ownership'                     => 'nullable|string|max:40',
            'staff_strength'                => 'nullable|string|max:40',
            'scope_of_service'              => 'nullable|string|max:80',
            'service_availability'          => 'nullable|string|max:120',
            'age_limit'                     => 'nullable|string|max:80',
            'cost_range_edo_south'          => 'nullable|string|max:80',
            'cost_range_edo_north'          => 'nullable|string|max:80',
            'cost_range_edo_central'        => 'nullable|string|max:80',
            'aware_of_edsta'                => 'nullable|boolean',
            'suggestions'                   => 'nullable|string',
            'yearly_interstate_visitors'    => 'nullable|integer|min:0',
            'yearly_international_visitors' => 'nullable|integer|min:0',
            'image_url'                     => 'nullable|string|max:500',
            'is_featured'                   => 'nullable|boolean',
            'is_active'                     => 'nullable|boolean',
        ]);
    }
}