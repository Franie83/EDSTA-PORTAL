<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Imports\EdoCuisinesImport;
use App\Models\EdoCuisine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class EdoCuisineController extends Controller
{
    public function index()
    {
        $cuisines = EdoCuisine::orderBy('sort_order')->orderBy('name')->paginate(50);
        return view('admin.cuisines.index', compact('cuisines'));
    }

    public function create()
    {
        $cuisine = new EdoCuisine(['is_active' => true]);
        return view('admin.cuisines.form', compact('cuisine'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        EdoCuisine::create($data);
        Helpers::logAction('admin_create_cuisine', 'EdoCuisine', null, 'Created cuisine: ' . $data['name']);
        return redirect()->route('admin.cuisines.index')->with('success', 'Cuisine created.');
    }

    public function edit(EdoCuisine $cuisine)
    {
        return view('admin.cuisines.form', compact('cuisine'));
    }

    public function update(Request $request, EdoCuisine $cuisine)
    {
        $data = $this->validated($request);
        $cuisine->update($data);
        Helpers::logAction('admin_update_cuisine', 'EdoCuisine', $cuisine->id, 'Updated cuisine: ' . $data['name']);
        return redirect()->route('admin.cuisines.index')->with('success', 'Cuisine updated.');
    }

    public function destroy(EdoCuisine $cuisine)
    {
        $cuisine->delete();
        Helpers::logAction('admin_delete_cuisine', 'EdoCuisine', $cuisine->id, 'Deleted cuisine: ' . $cuisine->name);
        return back()->with('success', 'Cuisine deleted.');
    }

    public function downloadTemplate()
    {
        $headers = ['name', 'description', 'ingredients', 'commonly_eaten_with', 'area', 'sort_order', 'is_active'];

        return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
            private $headers;
            public function __construct($headers) { $this->headers = $headers; }
            public function array(): array {
                return [
                    $this->headers,
                    ['Ogbolor Soup', 'Made with ground ogbolor seeds, leafy vegetables, and assorted meats. Known for its viscous texture.', 'Ogbolor seeds, leafy vegetables, assorted meats, fish', 'Pounded yam, fufu, or eba', 'Benin City', '1', '1'],
                ];
            }
        }, 'edo_cuisine_template.xlsx');
    }

    public function bulkUpload(Request $request)
    {
        $request->validate(['excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120']);

        try {
            Excel::import(new EdoCuisinesImport, $request->file('excel_file'));
            Helpers::logAction('admin_bulk_upload_cuisines', 'EdoCuisine', null, 'Uploaded cuisines via Excel');
            return redirect()->route('admin.cuisines.index')->with('success', 'Cuisines imported successfully!');
        } catch (\Exception $e) {
            Log::error('Cuisine bulk upload failed: ' . $e->getMessage());
            return redirect()->route('admin.cuisines.index')->with('danger', 'Error importing file: ' . $e->getMessage());
        }
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'                => 'required|string|max:100',
            'description'         => 'nullable|string',
            'ingredients'         => 'nullable|string',
            'commonly_eaten_with' => 'nullable|string|max:120',
            'area'                => 'nullable|string|max:120',
            'sort_order'          => 'nullable|integer|min:0',
            'is_active'           => 'nullable|boolean',
        ]);
    }
}