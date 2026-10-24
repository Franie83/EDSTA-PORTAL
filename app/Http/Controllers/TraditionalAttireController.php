<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Imports\TraditionalAttiresImport;
use App\Models\TraditionalAttire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class TraditionalAttireController extends Controller
{
    public function index()
    {
        $attires = TraditionalAttire::orderBy('sort_order')->orderBy('ethnic_group')->paginate(50);
        return view('admin.attires.index', compact('attires'));
    }

    public function create()
    {
        $attire = new TraditionalAttire(['is_active' => true, 'gender' => 'unisex']);
        return view('admin.attires.form', compact('attire'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        TraditionalAttire::create($data);
        Helpers::logAction('admin_create_attire', 'TraditionalAttire', null, 'Created attire: ' . $data['ethnic_group'] . '/' . $data['gender']);
        return redirect()->route('admin.attires.index')->with('success', 'Attire created.');
    }

    public function edit(TraditionalAttire $attire)
    {
        return view('admin.attires.form', compact('attire'));
    }

    public function update(Request $request, TraditionalAttire $attire)
    {
        $data = $this->validated($request);
        $attire->update($data);
        Helpers::logAction('admin_update_attire', 'TraditionalAttire', $attire->id, 'Updated attire: ' . $data['ethnic_group'] . '/' . $data['gender']);
        return redirect()->route('admin.attires.index')->with('success', 'Attire updated.');
    }

    public function destroy(TraditionalAttire $attire)
    {
        $attire->delete();
        Helpers::logAction('admin_delete_attire', 'TraditionalAttire', $attire->id, 'Deleted attire: ' . $attire->ethnic_group . '/' . $attire->gender);
        return back()->with('success', 'Attire deleted.');
    }

    public function downloadTemplate()
    {
        $headers = ['ethnic_group', 'gender', 'attire_items', 'description', 'occasions', 'notes', 'sort_order', 'is_active'];

        return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
            private $headers;
            public function __construct($headers) { $this->headers = $headers; }
            public function array(): array {
                return [
                    $this->headers,
                    ['Bini (Benin)', 'male', 'Ivie-Emada wrapper, beaded vest, Okuku cap', 'Long flowing wrapper with embroidered shirt and coral bead accessories', 'Weddings, coronations, Igue Festival, chieftaincy ceremonies', 'Coral beads symbolize wealth and royalty', '1', '1'],
                ];
            }
        }, 'traditional_attire_template.xlsx');
    }

    public function bulkUpload(Request $request)
    {
        $request->validate(['excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120']);

        try {
            Excel::import(new TraditionalAttiresImport, $request->file('excel_file'));
            Helpers::logAction('admin_bulk_upload_attires', 'TraditionalAttire', null, 'Uploaded traditional attire via Excel');
            return redirect()->route('admin.attires.index')->with('success', 'Traditional attire imported successfully!');
        } catch (\Exception $e) {
            Log::error('Attire bulk upload failed: ' . $e->getMessage());
            return redirect()->route('admin.attires.index')->with('danger', 'Error importing file: ' . $e->getMessage());
        }
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'ethnic_group' => 'required|string|max:80',
            'gender'       => 'required|in:male,female,unisex',
            'attire_items' => 'nullable|string',
            'description'  => 'nullable|string',
            'occasions'    => 'nullable|string|max:255',
            'notes'        => 'nullable|string',
            'sort_order'   => 'nullable|integer|min:0',
            'is_active'    => 'nullable|boolean',
        ]);
    }
}