<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Imports\ClubsLoungesImport;
use App\Models\ClubLounge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ClubLoungeController extends Controller
{
    public function index()
    {
        $clubs = ClubLounge::orderBy('name')->paginate(50);
        return view('admin.clubs-lounges.index', compact('clubs'));
    }

    public function create()
    {
        $club = new ClubLounge(['is_active' => true]);
        return view('admin.clubs-lounges.form', compact('club'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        ClubLounge::create($data);
        Helpers::logAction('admin_create_club', 'ClubLounge', null, 'Created club/lounge: ' . $data['name']);
        return redirect()->route('admin.clubs-lounges.index')->with('success', 'Club/lounge created.');
    }

    public function edit(ClubLounge $club)
    {
        return view('admin.clubs-lounges.form', compact('club'));
    }

    public function update(Request $request, ClubLounge $club)
    {
        $data = $this->validated($request);
        $club->update($data);
        Helpers::logAction('admin_update_club', 'ClubLounge', $club->id, 'Updated club/lounge: ' . $data['name']);
        return redirect()->route('admin.clubs-lounges.index')->with('success', 'Club/lounge updated.');
    }

    public function destroy(ClubLounge $club)
    {
        $club->delete();
        Helpers::logAction('admin_delete_club', 'ClubLounge', $club->id, 'Deleted club/lounge: ' . $club->name);
        return back()->with('success', 'Club/lounge deleted.');
    }

    public function downloadTemplate()
    {
        $headers = [
            'name', 'address', 'phone', 'email', 'website', 'nearby_landmark',
            'ownership', 'staff_strength', 'best_time_to_visit',
            'has_live_band', 'has_casino', 'has_grills', 'has_dance_floor',
            'has_indoor_bar', 'has_outdoor_bar', 'has_dj_booth', 'has_vip_section',
            'has_light_sound', 'has_cctv', 'has_swimming_pool', 'has_lodging',
            'booking_terms', 'gate_fee_regular', 'gate_fee_vip', 'accommodation_fees',
            'image_url', 'is_featured', 'is_active'
        ];

        return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
            private $headers;
            public function __construct($headers) { $this->headers = $headers; }
            public function array(): array {
                return [
                    $this->headers,
                    ['Example Lounge', '12 Airport Road, Benin City', '08012345678', 'info@example.com', 'https://example.com', 'Near Kings Square', 'private', '15-30', 'Evenings and weekends', '1', '0', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', 'Pre-booking and on-the-spot', '₦2,000', '₦10,000', '₦25,000/night', 'https://images.unsplash.com/...', '1', '1'],
                ];
            }
        }, 'clubs_lounges_template.xlsx');
    }

    public function bulkUpload(Request $request)
    {
        $request->validate(['excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120']);

        try {
            Excel::import(new ClubsLoungesImport, $request->file('excel_file'));
            Helpers::logAction('admin_bulk_upload_clubs', 'ClubLounge', null, 'Uploaded clubs/lounges via Excel');
            return redirect()->route('admin.clubs-lounges.index')->with('success', 'Clubs and lounges imported successfully!');
        } catch (\Exception $e) {
            Log::error('Club/lounge bulk upload failed: ' . $e->getMessage());
            return redirect()->route('admin.clubs-lounges.index')->with('danger', 'Error importing file: ' . $e->getMessage());
        }
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'                => 'required|string|max:120',
            'address'             => 'nullable|string|max:255',
            'phone'               => 'nullable|string|max:40',
            'email'               => 'nullable|email|max:120',
            'website'             => 'nullable|string|max:255',
            'nearby_landmark'     => 'nullable|string|max:255',
            'ownership'           => 'nullable|string|max:40',
            'staff_strength'      => 'nullable|string|max:40',
            'best_time_to_visit'  => 'nullable|string|max:120',
            'has_live_band'       => 'nullable|boolean',
            'has_casino'          => 'nullable|boolean',
            'has_grills'          => 'nullable|boolean',
            'has_dance_floor'     => 'nullable|boolean',
            'has_indoor_bar'      => 'nullable|boolean',
            'has_outdoor_bar'     => 'nullable|boolean',
            'has_dj_booth'        => 'nullable|boolean',
            'has_vip_section'     => 'nullable|boolean',
            'has_light_sound'     => 'nullable|boolean',
            'has_cctv'            => 'nullable|boolean',
            'has_swimming_pool'   => 'nullable|boolean',
            'has_lodging'         => 'nullable|boolean',
            'booking_terms'       => 'nullable|string|max:120',
            'gate_fee_regular'    => 'nullable|string|max:80',
            'gate_fee_vip'        => 'nullable|string|max:80',
            'accommodation_fees'  => 'nullable|string|max:80',
            'image_url'           => 'nullable|string|max:500',
            'is_featured'         => 'nullable|boolean',
            'is_active'           => 'nullable|boolean',
        ]);
    }
}