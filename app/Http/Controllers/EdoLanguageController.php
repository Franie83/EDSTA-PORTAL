<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Imports\EdoLanguagesImport;
use App\Models\EdoLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class EdoLanguageController extends Controller
{
    public function index()
    {
        $languages = EdoLanguage::orderBy('sort_order')->orderBy('name')->paginate(50);
        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {
        $language = new EdoLanguage(['is_active' => true]);
        return view('admin.languages.form', compact('language'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        EdoLanguage::create($data);
        Helpers::logAction('admin_create_language', 'EdoLanguage', null, 'Created language: ' . $data['name']);
        return redirect()->route('admin.languages.index')->with('success', 'Language created.');
    }

    public function edit(EdoLanguage $language)
    {
        return view('admin.languages.form', compact('language'));
    }

    public function update(Request $request, EdoLanguage $language)
    {
        $data = $this->validated($request);
        $language->update($data);
        Helpers::logAction('admin_update_language', 'EdoLanguage', $language->id, 'Updated language: ' . $data['name']);
        return redirect()->route('admin.languages.index')->with('success', 'Language updated.');
    }

    public function destroy(EdoLanguage $language)
    {
        $language->delete();
        Helpers::logAction('admin_delete_language', 'EdoLanguage', $language->id, 'Deleted language: ' . $language->name);
        return back()->with('success', 'Language deleted.');
    }

    public function downloadTemplate()
    {
        $headers = ['name', 'speakers', 'area', 'lga_list', 'notes', 'sort_order', 'is_active'];

        return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
            private $headers;
            public function __construct($headers) { $this->headers = $headers; }
            public function array(): array {
                return [
                    $this->headers,
                    ['Edo (Bini)', 'Benin people', 'Southern Edo State', 'Oredo, Ikpoba-Okha, Egor, Uhunmwonde', 'Lingua franca for many residents', '1', '1'],
                ];
            }
        }, 'edo_languages_template.xlsx');
    }

    public function bulkUpload(Request $request)
    {
        $request->validate(['excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120']);

        try {
            Excel::import(new EdoLanguagesImport, $request->file('excel_file'));
            Helpers::logAction('admin_bulk_upload_languages', 'EdoLanguage', null, 'Uploaded languages via Excel');
            return redirect()->route('admin.languages.index')->with('success', 'Languages imported successfully!');
        } catch (\Exception $e) {
            Log::error('Language bulk upload failed: ' . $e->getMessage());
            return redirect()->route('admin.languages.index')->with('danger', 'Error importing file: ' . $e->getMessage());
        }
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'       => 'required|string|max:80',
            'speakers'   => 'nullable|string|max:120',
            'area'       => 'nullable|string|max:120',
            'lga_list'   => 'nullable|string|max:255',
            'notes'      => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'nullable|boolean',
        ]);
    }
}