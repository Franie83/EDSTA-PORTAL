<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Advertisement;
use App\Models\AuditLog;
use App\Models\Attraction;
use App\Models\Booking;
use App\Models\Category;
use App\Models\CmsSetting;
use App\Models\Event;
use App\Models\Faq;
use App\Models\Guide;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\News;
use App\Models\Notification;
use App\Models\Partner;
use App\Models\QrCode;
use App\Models\Restaurant;
use App\Models\RestaurantMenu;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Imports\AttractionsImport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    // Section
    public function dashboard()
    {
        $stats = [
            'total_users'       => User::count(),
            'total_attractions' => Attraction::count(),
            'total_bookings'    => Booking::count(),
            'total_guides'      => Guide::count(),
            'total_hotels'      => Hotel::count(),
            'total_restaurants' => Restaurant::count(),
            'total_events'      => Event::count(),
            'total_news'        => News::count(),
            'pending_bookings'  => Booking::where('booking_status', 'Pending')->count(),
            'pending_guides'    => Guide::where('verification_status', 'Pending')->count(),
            'pending_hotels'    => Hotel::where('is_active', false)->count(),
            'pending_restaurants' => Restaurant::where('is_active', false)->count(),
            'pending_events'    => Event::where('is_active', false)->count(),
            'total_clubs'       => \App\Models\ClubLounge::count(),
            'total_permits'     => \App\Models\TourismPermit::count(),
            'total_staff'       => \App\Models\StaffMember::count(),
            'pending_clubs'     => \App\Models\ClubLounge::where('is_active', false)->count(),
        ];

        $recent_pending = [
            'guides'       => Guide::where('verification_status', 'Pending')->with('user')->orderByDesc('created_at')->limit(5)->get(),
            'hotels'       => Hotel::where('is_active', false)->with('user')->orderByDesc('created_at')->limit(5)->get(),
            'restaurants'  => Restaurant::where('is_active', false)->with('user')->orderByDesc('created_at')->limit(5)->get(),
            'events'       => Event::where('is_active', false)->with('user')->orderByDesc('created_at')->limit(5)->get(),
        ];

        $recent_bookings = Booking::with('user')->orderByDesc('created_at')->limit(8)->get();
        $recent_users    = User::orderByDesc('created_at')->limit(5)->get();
        $recent_permits  = \App\Models\TourismPermit::orderByDesc('created_at')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_pending', 'recent_bookings', 'recent_users', 'recent_permits'));
    }

    // Section
    public function users(Request $request)
    {
        $query = User::query();
        if ($s = $request->q)    $query->where(fn($q) => $q->where('first_name', 'like', "%{$s}%")->orWhere('last_name', 'like', "%{$s}%")->orWhere('email', 'like', "%{$s}%"));
        if ($r = $request->role) $query->where('role', $r);
        return view('admin.users.index', ['users' => $query->orderByDesc('created_at')->paginate(20)->withQueryString()]);
    }

    public function userCreate()  { return view('admin.users.form', ['user' => new User()]); }

    public function userStore(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required', 'last_name' => 'required',
            'email' => 'required|email|unique:users', 'password' => 'required|min:6',
            'role' => 'required', 'phone' => 'nullable',
        ]);
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        Helpers::logAction('admin_create_user', 'User', $user->id);
        return redirect()->route('admin.users')->with('success', 'User created.');
    }

    public function userEdit(int $id) { return view('admin.users.form', ['user' => User::findOrFail($id)]); }

    public function userUpdate(Request $request, int $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'first_name' => 'required', 'last_name' => 'required',
            'email' => "required|email|unique:users,email,{$id}",
            'role' => 'required', 'status' => 'required', 'phone' => 'nullable',
        ]);
        if ($request->filled('password')) $data['password'] = Hash::make($request->password);
        $user->update($data);
        Helpers::logAction('admin_update_user', 'User', $id);
        return redirect()->route('admin.users')->with('success', 'User updated.');
    }

    public function userDelete(int $id)
    {
        User::findOrFail($id)->delete();
        Helpers::logAction('admin_delete_user', 'User', $id);
        return back()->with('success', 'User deleted.');
    }

    // Section
    public function attractions(Request $request)
    {
        $query = Attraction::with('category', 'user');
        if ($s = $request->q) $query->where('name', 'like', "%{$s}%");
        return view('admin.attractions.index', [
            'attractions' => $query->orderByDesc('created_at')->paginate(20)->withQueryString(),
        ]);
    }

    public function attractionCreate()
    {
        return view('admin.attractions.form', [
            'attraction' => new Attraction(),
            'categories' => Category::all(),
            'users'      => User::orderBy('first_name')->get(),
        ]);
    }

    public function attractionStore(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'history'       => 'nullable|string',
            'address'       => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:100',
            'category_id'   => 'nullable|exists:categories,id',
            'ticket_price'  => 'nullable|numeric',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
            'opening_hours' => 'nullable|string',
            'website'       => 'nullable|url',
            'phone'         => 'nullable|string|max:50',
            'user_id'       => 'required|exists:users,id',
            'image_url'     => 'nullable|url',
            'historical_background'    => 'nullable|string',
            'unique_selling_points'    => 'nullable|string',
            'ownership'                => 'nullable|string|max:80',
            'investment_opportunities' => 'nullable|string',
            'visiting_tips'            => 'nullable|string',
            'landmarks'                => 'nullable|string|max:255',
            'proximity_from_city'      => 'nullable|string|max:120',
        ]);
        
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active']   = $request->has('is_active');
        
        try {
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $uploadedPath = Helpers::saveUpload($request->file('image'), 'attractions');
                if ($uploadedPath) $data['image_url'] = $uploadedPath;
            } elseif ($request->filled('image_url')) {
                $data['image_url'] = $request->input('image_url');
            }
        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
        }
        
        $attr = Attraction::create($data);
        QrCode::generateForEntity('Attraction', $attr->id);
        Helpers::logAction('admin_create_attraction', 'Attraction', $attr->id);
        return redirect()->route('admin.attractions')->with('success', 'Attraction created successfully.');
    }

    public function attractionEdit(int $id)
    {
        return view('admin.attractions.form', [
            'attraction' => Attraction::findOrFail($id),
            'categories' => Category::all(),
            'users'      => User::orderBy('first_name')->get(),
        ]);
    }

    public function attractionUpdate(Request $request, int $id)
    {
        $attr = Attraction::findOrFail($id);
        
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'history'       => 'nullable|string',
            'address'       => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:100',
            'category_id'   => 'nullable|exists:categories,id',
            'ticket_price'  => 'nullable|numeric',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
            'opening_hours' => 'nullable|string',
            'website'       => 'nullable|url',
            'phone'         => 'nullable|string|max:50',
            'user_id'       => 'required|exists:users,id',
            'image_url'     => 'nullable|url',
            'historical_background'    => 'nullable|string',
            'unique_selling_points'    => 'nullable|string',
            'ownership'                => 'nullable|string|max:80',
            'investment_opportunities' => 'nullable|string',
            'visiting_tips'            => 'nullable|string',
            'landmarks'                => 'nullable|string|max:255',
            'proximity_from_city'      => 'nullable|string|max:120',
        ]);
        
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active']   = $request->has('is_active');
        
        try {
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $uploadedPath = Helpers::saveUpload($request->file('image'), 'attractions');
                if ($uploadedPath) $data['image_url'] = $uploadedPath;
            } elseif ($request->filled('image_url')) {
                $data['image_url'] = $request->input('image_url');
            }
        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
        }
        
        $attr->update($data);
                // Handle gallery images (multi-upload)
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    $path = \App\Helpers\Helpers::saveUpload($file, 'attractions/gallery');
                    $attr->images()->create(['image_path' => $path]);
                }
            }
        }
        Helpers::logAction('admin_update_attraction', 'Attraction', $id);
        return redirect()->route('admin.attractions')->with('success', 'Attraction updated successfully.');
    }

    public function attractionDelete(int $id)
    {
        Attraction::findOrFail($id)->delete();
        Helpers::logAction('admin_delete_attraction', 'Attraction', $id);
        return back()->with('success', 'Attraction deleted.');
    }

    public function deleteAttractionImage($id)
    {
        $image = \App\Models\AttractionImage::findOrFail($id);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
        $image->delete();
        Helpers::logAction('admin_delete_attraction_image', 'AttractionImage', $id);
        return back()->with('success', 'Gallery image deleted.');
    }

    public function attractionRegenQr(int $id)
    {
        $attr = Attraction::findOrFail($id);
        QrCode::generateForEntity('Attraction', $id);
        Helpers::logAction('admin_regen_qr', 'Attraction', $id);
        return back()->with('success', 'QR code regenerated.');
    }

    public function generateAllQr()
    {
        Artisan::call('generate:qr');
        return back()->with('success', 'All QR codes regenerated successfully.');
    }

    // Section
    public function downloadAttractionTemplate()
    {
        $headers = [
            'name', 'description', 'history', 'address', 'city', 
            'ticket_price', 'latitude', 'longitude', 'opening_hours', 
            'website', 'phone', 'image_url', 'category_id', 'user_id', 'is_featured', 'is_active'
        ];

        return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
            private $headers;
            public function __construct($headers) { $this->headers = $headers; }
            public function array(): array {
                return [
                    $this->headers,
                    [
                        'Example Attraction', 'Description here', 'History here', '123 Main St', 'Benin City',
                        '1500', '6.3374', '5.6252', '9am-5pm', 'https://example.com', '08012345678', 'https://images.unsplash.com/photo-...', '1', '1', '1', '1'
                    ]
                ];
            }
        }, 'attractions_template.xlsx');
    }

    public function bulkUploadAttractions(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            Excel::import(new AttractionsImport, $request->file('excel_file'));
            Helpers::logAction('admin_bulk_upload_attractions', 'Attraction', null, 'Uploaded attractions via Excel');
            return redirect()->route('admin.attractions')->with('success', 'Attractions imported successfully!');
        } catch (\Exception $e) {
            Log::error('Bulk upload failed: ' . $e->getMessage());
            return redirect()->route('admin.attractions')->with('danger', 'Error importing file: ' . $e->getMessage());
        }
    }

    // Section
    public function downloadGuideTemplate()
    {
        $headers = ['user_id', 'bio', 'languages', 'specializations', 'experience', 'hourly_rate', 'daily_rate', 'certification', 'is_featured', 'is_available'];

        return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
            private $headers;
            public function __construct($headers) { $this->headers = $headers; }
            public function array(): array {
                return [
                    $this->headers,
                    [
                        '2', 'Expert in Benin history', 'English, Yoruba', 'History, Culture', '5', '7500', '40000', 'Certified Guide', '1', '1'
                    ]
                ];
            }
        }, 'guides_template.xlsx');
    }

    public function bulkUploadGuides(Request $request)
    {
        $request->validate(['excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120']);

        try {
            Excel::import(new \App\Imports\GuidesImport, $request->file('excel_file'));
            Helpers::logAction('admin_bulk_upload_guides', 'Guide', null, 'Uploaded guides via Excel');
            return redirect()->route('admin.guides')->with('success', 'Guides imported successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.guides')->with('danger', 'Error importing file: ' . $e->getMessage());
        }
    }

    // Section
    public function guides(Request $request)
    {
        $query = Guide::with('user');
        if ($s = $request->q) $query->whereHas('user', fn($u) => $u->where('first_name', 'like', "%{$s}%")->orWhere('last_name', 'like', "%{$s}%"));
        if ($v = $request->status) $query->where('verification_status', $v);
        return view('admin.guides.index', ['guides' => $query->orderByDesc('created_at')->paginate(20)->withQueryString()]);
    }

    public function guideCreate()
    {
        return view('admin.guides.form', [
            'guide' => new Guide(),
            'users' => User::orderBy('first_name')->get(),
        ]);
    }

    public function guideStore(Request $request)
    {
        $data = $request->validate([
            'bio'             => 'nullable|string',
            'languages'       => 'nullable|string',
            'specializations' => 'nullable|string',
            'experience'      => 'nullable|integer',
            'hourly_rate'     => 'nullable|numeric',
            'daily_rate'      => 'nullable|numeric',
            'certification'   => 'nullable|string',
            'user_id'         => 'required|exists:users,id',
        ]);
        $data['is_featured']   = $request->has('is_featured');
        $data['is_available']  = $request->has('is_available');
        $guide = Guide::create($data);
        Helpers::logAction('admin_create_guide', 'Guide', $guide->id);
        return redirect()->route('admin.guides')->with('success', 'Guide created.');
    }

    public function guideEdit(int $id)
    {
        return view('admin.guides.form', [
            'guide' => Guide::with('user')->findOrFail($id),
            'users' => User::orderBy('first_name')->get(),
        ]);
    }

    public function guideUpdate(Request $request, int $id)
    {
        $guide = Guide::findOrFail($id);
        $data = $request->validate([
            'bio'             => 'nullable|string',
            'languages'       => 'nullable|string',
            'specializations' => 'nullable|string',
            'experience'      => 'nullable|integer',
            'hourly_rate'     => 'nullable|numeric',
            'daily_rate'      => 'nullable|numeric',
            'certification'   => 'nullable|string',
            'user_id'         => 'required|exists:users,id',
        ]);
        $data['is_featured']  = $request->has('is_featured');
        $data['is_available'] = $request->has('is_available');
        $guide->update($data);
        Helpers::logAction('admin_update_guide', 'Guide', $id);
        return redirect()->route('admin.guides')->with('success', 'Guide updated.');
    }

    public function guideVerify(Request $request, int $id)
    {
        $guide  = Guide::with('user')->findOrFail($id);
        $status = $request->input('status', 'Approved');
        $guide->update(['verification_status' => $status]);
        if ($guide->user) {
            Notification::create([
                'user_id' => $guide->user_id,
                'title'   => "Guide Verification: {$status}",
                'message' => "Your guide profile has been {$status}.",
                'type'    => 'info',
            ]);
        }
        Helpers::logAction('admin_verify_guide', 'Guide', $id, "Status: {$status}");
        return back()->with('success', "Guide {$status}.");
    }

    public function guideDelete(int $id)
    {
        Guide::findOrFail($id)->delete();
        return back()->with('success', 'Guide deleted.');
    }

    // Section
    // Section
    public function downloadHotelTemplate()
    {
        $headers = ['user_id', 'name', 'description', 'address', 'city', 'latitude', 'longitude', 'stars', 'price_per_night', 'amenities', 'check_in_time', 'check_out_time', 'website', 'phone', 'email', 'image_url', 'is_featured', 'is_active'];

        return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
            private $headers;
            public function __construct($headers) { $this->headers = $headers; }
            public function array(): array {
                return [
                    $this->headers,
                    ['2', 'Example Hotel', 'Description here', '123 Main St', 'Benin City', '6.3374', '5.6252', '4', '45000', 'Pool, Gym, WiFi', '2:00 PM', '12:00 PM', 'https://example.com', '08012345678', 'hotel@example.com', 'https://images.unsplash.com/photo-...', '1', '1']
                ];
            }
        }, 'hotels_template.xlsx');
    }

    public function bulkUploadHotels(Request $request)
    {
        $request->validate(['excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120']);

        try {
            Excel::import(new \App\Imports\HotelsImport, $request->file('excel_file'));
            Helpers::logAction('admin_bulk_upload_hotels', 'Hotel', null, 'Uploaded hotels via Excel');
            return redirect()->route('admin.hotels')->with('success', 'Hotels imported successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.hotels')->with('danger', 'Error importing file: ' . $e->getMessage());
        }
    }
    public function hotels(Request $request)
    {
        $query = Hotel::with('user');
        if ($s = $request->q) $query->where('name', 'like', "%{$s}%");
        if ($status = $request->status) {
            $query->where('is_active', $status === 'active' ? true : false);
        }
        return view('admin.hotels.index', ['hotels' => $query->orderByDesc('created_at')->paginate(20)->withQueryString()]);
    }

    public function hotelCreate()
    {
        return view('admin.hotels.form', [
            'hotel' => new Hotel(),
            'users' => User::orderBy('first_name')->get(),
        ]);
    }

    public function hotelStore(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'address'          => 'nullable|string',
            'city'             => 'nullable|string|max:100',
            'latitude'         => 'nullable|numeric',
            'longitude'        => 'nullable|numeric',
            'stars'            => 'nullable|integer|min:1|max:5',
            'price_per_night'  => 'nullable|numeric',
            'amenities'        => 'nullable|array',
            'check_in_time'    => 'nullable|string',
            'check_out_time'   => 'nullable|string',
            'website'          => 'nullable|url',
            'phone'            => 'nullable|string|max:50',
            'email'            => 'nullable|email',
            'user_id'          => 'required|exists:users,id',
            'nearby_landmark'          => 'nullable|string|max:255',
            'star_rating'              => 'nullable|integer|min:1|max:5',
            'ownership'                => 'nullable|string|max:40',
            'staff_strength'           => 'nullable|string|max:40',
            'rooms_total'              => 'nullable|integer|min:0',
            'rooms_functional'         => 'nullable|integer|min:0',
            'rooms_suites'             => 'nullable|integer|min:0',
            'has_swimming_pool'        => 'nullable|boolean',
            'has_gym'                  => 'nullable|boolean',
            'has_spa'                  => 'nullable|boolean',
            'has_wifi'                 => 'nullable|boolean',
            'has_cctv'                 => 'nullable|boolean',
            'has_event_hall'           => 'nullable|boolean',
            'has_live_band'            => 'nullable|boolean',
            'has_parking'              => 'nullable|boolean',
            'has_concierge'            => 'nullable|boolean',
            'has_laundry'              => 'nullable|boolean',
            'has_first_aid'            => 'nullable|boolean',
            'has_emergency_exit'       => 'nullable|boolean',
            'has_fire_extinguisher'    => 'nullable|boolean',
            'price_range_feeding'      => 'nullable|string|max:80',
            'recreation_offerings'     => 'nullable|string',
            'booking_terms'            => 'nullable|string|max:120',
        ]);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active']   = $request->has('is_active');
        if ($request->hasFile('image')) $data['image_url'] = Helpers::saveUpload($request->file('image'), 'hotels');
        $hotel = Hotel::create($data);

        // Handle gallery images (multi-upload)
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    $path = \App\Helpers\Helpers::saveUpload($file, 'hotels/gallery');
                    $hotel->images()->create(['image_path' => $path]);
                }
            }
        }
        Helpers::logAction('admin_create_hotel', 'Hotel', $hotel->id);
        return redirect()->route('admin.hotels')->with('success', 'Hotel created.');
    }

    public function hotelEdit(int $id)
    {
        return view('admin.hotels.form', [
            'hotel' => Hotel::findOrFail($id)->load('images'),
            'users' => User::orderBy('first_name')->get(),
        ]);
    }

    public function hotelUpdate(Request $request, int $id)
    {
        $hotel = Hotel::findOrFail($id);
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'address'          => 'nullable|string',
            'city'             => 'nullable|string|max:100',
            'latitude'         => 'nullable|numeric',
            'longitude'        => 'nullable|numeric',
            'stars'            => 'nullable|integer|min:1|max:5',
            'price_per_night'  => 'nullable|numeric',
            'amenities'        => 'nullable|array',
            'check_in_time'    => 'nullable|string',
            'check_out_time'   => 'nullable|string',
            'website'          => 'nullable|url',
            'phone'            => 'nullable|string|max:50',
            'email'            => 'nullable|email',
            'user_id'          => 'required|exists:users,id',
            'nearby_landmark'          => 'nullable|string|max:255',
            'star_rating'              => 'nullable|integer|min:1|max:5',
            'ownership'                => 'nullable|string|max:40',
            'staff_strength'           => 'nullable|string|max:40',
            'rooms_total'              => 'nullable|integer|min:0',
            'rooms_functional'         => 'nullable|integer|min:0',
            'rooms_suites'             => 'nullable|integer|min:0',
            'has_swimming_pool'        => 'nullable|boolean',
            'has_gym'                  => 'nullable|boolean',
            'has_spa'                  => 'nullable|boolean',
            'has_wifi'                 => 'nullable|boolean',
            'has_cctv'                 => 'nullable|boolean',
            'has_event_hall'           => 'nullable|boolean',
            'has_live_band'            => 'nullable|boolean',
            'has_parking'              => 'nullable|boolean',
            'has_concierge'            => 'nullable|boolean',
            'has_laundry'              => 'nullable|boolean',
            'has_first_aid'            => 'nullable|boolean',
            'has_emergency_exit'       => 'nullable|boolean',
            'has_fire_extinguisher'    => 'nullable|boolean',
            'price_range_feeding'      => 'nullable|string|max:80',
            'recreation_offerings'     => 'nullable|string',
            'booking_terms'            => 'nullable|string|max:120',
        ]);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active']   = $request->has('is_active');
        if ($request->hasFile('image')) $data['image_url'] = Helpers::saveUpload($request->file('image'), 'hotels');
        $hotel->update($data);

        // Handle gallery images (multi-upload)
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    $path = \App\Helpers\Helpers::saveUpload($file, 'hotels/gallery');
                    $hotel->images()->create(['image_path' => $path]);
                }
            }
        }
        Helpers::logAction('admin_update_hotel', 'Hotel', $id);
        return redirect()->route('admin.hotels')->with('success', 'Hotel updated.');
    }

    public function hotelDelete(int $id)
    {
        Hotel::findOrFail($id)->delete();
        return back()->with('success', 'Hotel deleted.');
    }

    public function deleteHotelImage(int $id)
    {
        $image = \App\Models\HotelImage::findOrFail($id);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
        $image->delete();
        Helpers::logAction('admin_delete_hotel_image', 'HotelImage', $id);
        return back()->with('success', 'Gallery image deleted.');
    }

    public function approveHotel(int $id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->is_active = true;
        $hotel->save();

        Notification::create([
            'user_id' => $hotel->user_id,
            'title'   => 'Hotel Approved',
            'message' => 'Your hotel "'.$hotel->name.'" has been approved and is now live on the platform.',
            'type'    => 'success',
        ]);

        Helpers::logAction('approve_hotel', 'Hotel', $id);
        return back()->with('success', 'Hotel approved and is now active.');
    }

    // Section
    // Section
    public function downloadRestaurantTemplate()
    {
        $headers = ['user_id', 'name', 'description', 'address', 'city', 'latitude', 'longitude', 'cuisine_type', 'opening_hours', 'avg_price', 'website', 'phone', 'email', 'image_url', 'is_featured', 'is_active'];

        return Excel::download(new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
            private $headers;
            public function __construct($headers) { $this->headers = $headers; }
            public function array(): array {
                return [
                    $this->headers,
                    ['2', 'Example Restaurant', 'Description here', '123 Main St', 'Benin City', '6.3374', '5.6252', 'Nigerian', '11am-10pm', '3500', 'https://example.com', '08012345678', 'restaurant@example.com', 'https://images.unsplash.com/photo-...', '1', '1']
                ];
            }
        }, 'restaurants_template.xlsx');
    }

    public function bulkUploadRestaurants(Request $request)
    {
        $request->validate(['excel_file' => 'required|file|mimes:xlsx,xls,csv|max:5120']);

        try {
            Excel::import(new \App\Imports\RestaurantsImport, $request->file('excel_file'));
            Helpers::logAction('admin_bulk_upload_restaurants', 'Restaurant', null, 'Uploaded restaurants via Excel');
            return redirect()->route('admin.restaurants')->with('success', 'Restaurants imported successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.restaurants')->with('danger', 'Error importing file: ' . $e->getMessage());
        }
    }
    public function restaurants(Request $request)
    {
        $query = Restaurant::with('user');
        if ($s = $request->q) $query->where('name', 'like', "%{$s}%");
        if ($status = $request->status) {
            $query->where('is_active', $status === 'active' ? true : false);
        }
        return view('admin.restaurants.index', ['restaurants' => $query->orderByDesc('created_at')->paginate(20)->withQueryString()]);
    }

    public function restaurantCreate()
    {
        return view('admin.restaurants.form', [
            'restaurant' => new Restaurant(),
            'users'      => User::orderBy('first_name')->get(),
        ]);
    }

    public function restaurantStore(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'address'      => 'nullable|string',
            'city'         => 'nullable|string|max:100',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'cuisine_type' => 'nullable|string|max:100',
            'opening_hours'=> 'nullable|string',
            'avg_price'    => 'nullable|numeric',
            'website'      => 'nullable|url',
            'phone'        => 'nullable|string|max:50',
            'email'        => 'nullable|email',
            'user_id'      => 'required|exists:users,id',
        ]);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active']   = $request->has('is_active');
        if ($request->hasFile('image')) $data['image_url'] = Helpers::saveUpload($request->file('image'), 'restaurants');
        $rest = Restaurant::create($data);

        // Handle gallery images (multi-upload)
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('restaurants/gallery', 'public');
                    $rest->images()->create(['image_path' => $path]);
                }
            }
        }
        Helpers::logAction('admin_create_restaurant', 'Restaurant', $rest->id);
        return redirect()->route('admin.restaurants')->with('success', 'Restaurant created.');
    }

    public function restaurantEdit(int $id)
    {
        return view('admin.restaurants.form', [
            'restaurant' => Restaurant::findOrFail($id),
            'users'      => User::orderBy('first_name')->get(),
        ]);
    }

    public function restaurantUpdate(Request $request, int $id)
    {
        $rest = Restaurant::findOrFail($id);
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'address'      => 'nullable|string',
            'city'         => 'nullable|string|max:100',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'cuisine_type' => 'nullable|string|max:100',
            'opening_hours'=> 'nullable|string',
            'avg_price'    => 'nullable|numeric',
            'website'      => 'nullable|url',
            'phone'        => 'nullable|string|max:50',
            'email'        => 'nullable|email',
            'user_id'      => 'required|exists:users,id',
        ]);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active']   = $request->has('is_active');
        if ($request->hasFile('image')) $data['image_url'] = Helpers::saveUpload($request->file('image'), 'restaurants');
        $rest->update($data);

        // Handle gallery images (multi-upload)
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('restaurants/gallery', 'public');
                    $rest->images()->create(['image_path' => $path]);
                }
            }
        }
        Helpers::logAction('admin_update_restaurant', 'Restaurant', $id);
        return redirect()->route('admin.restaurants')->with('success', 'Restaurant updated.');
    }

    public function restaurantDelete(int $id)
    {
        Restaurant::findOrFail($id)->delete();
        return back()->with('success', 'Restaurant deleted.');
    }

    public function deleteRestaurantImage(int $id)
    {
        $image = \App\Models\RestaurantImage::findOrFail($id);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
        $image->delete();
        Helpers::logAction('admin_delete_restaurant_image', 'RestaurantImage', $id);
        return back()->with('success', 'Gallery image deleted.');
    }

    public function approveRestaurant(int $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->is_active = true;
        $restaurant->save();

        Notification::create([
            'user_id' => $restaurant->user_id,
            'title'   => 'Restaurant Approved',
            'message' => 'Your restaurant "'.$restaurant->name.'" has been approved and is now live on the platform.',
            'type'    => 'success',
        ]);

        Helpers::logAction('approve_restaurant', 'Restaurant', $id);
        return back()->with('success', 'Restaurant approved and is now active.');
    }

    // Section
    public function events(Request $request)
    {
        $query = Event::with('user');
        if ($s = $request->q) $query->where('name', 'like', "%{$s}%");
        if ($status = $request->status) {
            $query->where('is_active', $status === 'active' ? true : false);
        }
        return view('admin.events.index', ['events' => $query->orderByDesc('created_at')->paginate(20)->withQueryString()]);
    }

    public function eventCreate()
    {
        return view('admin.events.form', [
            'event' => new Event(),
            'users' => User::orderBy('first_name')->get(),
        ]);
    }

    public function eventStore(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'location'     => 'nullable|string|max:255',
            'address'      => 'nullable|string|max:255',
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'ticket_price' => 'nullable|numeric',
            'capacity'     => 'nullable|integer',
            'organizer'    => 'nullable|string|max:255',
            'category'     => 'nullable|string|max:100',
            'website'      => 'nullable|url',
            'user_id'      => 'required|exists:users,id',
        ]);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active']   = $request->has('is_active');
        if ($request->hasFile('image')) $data['image_url'] = Helpers::saveUpload($request->file('image'), 'events');
        $event = Event::create($data);

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('events/gallery', 'public');
                    $event->images()->create(['image_path' => $path]);
                }
            }
        }

        Helpers::logAction('admin_create_event', 'Event', $event->id);
        return redirect()->route('admin.events')->with('success', 'Event created.');
    }

    public function eventEdit(int $id)
    {
        return view('admin.events.form', [
            'event' => Event::findOrFail($id),
            'users' => User::orderBy('first_name')->get(),
        ]);
    }

    public function eventUpdate(Request $request, int $id)
    {
        $event = Event::findOrFail($id);
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'location'     => 'nullable|string|max:255',
            'address'      => 'nullable|string|max:255',
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'ticket_price' => 'nullable|numeric',
            'capacity'     => 'nullable|integer',
            'organizer'    => 'nullable|string|max:255',
            'category'     => 'nullable|string|max:100',
            'website'      => 'nullable|url',
            'user_id'      => 'required|exists:users,id',
        ]);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active']   = $request->has('is_active');
        if ($request->hasFile('image')) $data['image_url'] = Helpers::saveUpload($request->file('image'), 'events');
        $event->update($data);
                if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('events/gallery', 'public');
                    $event->images()->create(['image_path' => $path]);
                }
            }
        }
        return redirect()->route('admin.events')->with('success', 'Event updated.');
    }

    public function eventDelete(int $id) { Event::findOrFail($id)->delete(); return back()->with('success', 'Event deleted.'); }

    public function approveEvent(int $id)
    {
        $event = Event::findOrFail($id);
        $event->is_active = true;
        $event->save();

        Notification::create([
            'user_id' => $event->user_id,
            'title'   => 'Event Approved',
            'message' => 'Your event "'.$event->name.'" has been approved and is now live on the platform.',
            'type'    => 'success',
        ]);

        Helpers::logAction('approve_event', 'Event', $id);
        return back()->with('success', 'Event approved and is now active.');
    }

    // Section
    public function bookings(Request $request)
    {
        $query = Booking::with('user');
        if ($s = $request->status) $query->where('booking_status', $s);
        if ($t = $request->type)   $query->where('booking_type', $t);
        return view('admin.bookings.index', ['bookings' => $query->orderByDesc('created_at')->paginate(25)->withQueryString()]);
    }

    public function bookingApprove(Request $request, int $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update([
            'booking_status' => 'AdminApproved',
            'admin_comment'  => $request->admin_comment,
        ]);
        $booking->logStatus('AdminApproved', $request->admin_comment);

        Notification::create([
            'user_id' => $booking->user_id,
            'title'   => 'Booking Approved by Admin',
            'message' => "Your booking {$booking->reference_code} has been approved by admin. Waiting for vendor confirmation.",
            'type'    => 'booking',
        ]);

        Helpers::logAction('admin_approve_booking', 'Booking', $id);
        return back()->with('success', 'Booking approved, waiting for vendor confirmation.');
    }

    public function bookingReject(Request $request, int $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update([
            'booking_status' => 'AdminRejected',
            'admin_comment'  => $request->admin_comment,
        ]);
        $booking->logStatus('AdminRejected', $request->admin_comment);

        Notification::create([
            'user_id' => $booking->user_id,
            'title'   => 'Booking Rejected by Admin',
            'message' => "Your booking {$booking->reference_code} has been rejected by admin. Reason: " . $request->admin_comment,
            'type'    => 'booking',
        ]);

        Helpers::logAction('admin_reject_booking', 'Booking', $id);
        return back()->with('success', 'Booking rejected.');
    }

    public function bookingDetail(int $id)
    {
        $booking = Booking::with('histories.user', 'user')->findOrFail($id);
        return view('admin.bookings.detail', compact('booking'));
    }

    // Section
    public function reviews(Request $request)
    {
        $query = Review::with('user');
        if ($t = $request->type) $query->where('target_type', $t);
        if ($status = $request->status) {
            $query->where('is_approved', $status === 'approved' ? true : false);
        }
        return view('admin.reviews.index', ['reviews' => $query->orderByDesc('created_at')->paginate(25)->withQueryString()]);
    }

    public function reviewDelete(int $id) { Review::findOrFail($id)->delete(); return back()->with('success', 'Review deleted.'); }

    public function approveReview(int $id)
    {
        $review = Review::findOrFail($id);
        $review->is_approved = true;
        $review->save();

        Helpers::logAction('approve_review', 'Review', $id);
        return back()->with('success', 'Review approved and is now visible.');
    }

    // Section
    public function analytics(Request $request)
    {
        $from = $request->input('from') ? Carbon::parse($request->from)->startOfDay() : null;
        $to   = $request->input('to')   ? Carbon::parse($request->to)->endOfDay() : null;

        $bookingQuery = Booking::query();
        if ($from) $bookingQuery->where('created_at', '>=', $from);
        if ($to)   $bookingQuery->where('created_at', '<=', $to);

        $completedQuery = clone $bookingQuery;
        $completedQuery->where('booking_status', 'Completed');

        $userQuery = User::query();
        if ($from) $userQuery->where('created_at', '>=', $from);
        if ($to)   $userQuery->where('created_at', '<=', $to);

        $revenueQuery = Booking::where('booking_status', 'Completed');
        if ($from) $revenueQuery->where('created_at', '>=', $from);
        if ($to)   $revenueQuery->where('created_at', '<=', $to);
        if (!$from && !$to) {
            $revenueQuery->where('created_at', '>=', Carbon::now()->subDays(30));
        }

        $revenueData = $revenueQuery->selectRaw('DATE(created_at) as date, SUM(total_price) as total')
                                    ->groupBy('date')
                                    ->orderBy('date')
                                    ->pluck('total', 'date');
        $revenue_labels = $revenueData->keys()->map(fn($d) => Carbon::parse($d)->format('d M'))->toArray();
        $revenue_values = $revenueData->values()->toArray();

        return view('admin.analytics', [
            'stats' => [
                'users'       => $userQuery->count(),
                'attractions' => Attraction::count(),
                'hotels'      => Hotel::count(),
                'restaurants' => Restaurant::count(),
                'events'      => Event::count(),
                'news'        => News::count(),
                'guides'      => Guide::count(),
                'bookings'    => $bookingQuery->count(),
                'reviews'     => Review::count(),
                'revenue'     => $completedQuery->sum('total_price') - 0,
            ],
            'bookings_by_status' => (clone $bookingQuery)->selectRaw('booking_status, count(*) as total')->groupBy('booking_status')->pluck('total', 'booking_status'),
            'bookings_by_type'   => (clone $bookingQuery)->selectRaw('booking_type, count(*) as total')->groupBy('booking_type')->pluck('total', 'booking_type'),
            'users_by_role'      => $userQuery->selectRaw('role, count(*) as total')->groupBy('role')->pluck('total', 'role'),
            'revenue_labels'     => $revenue_labels,
            'revenue_data'       => $revenue_values,
        ]);
    }

    public function analyticsExport(Request $request)
    {
        $type = $request->input('type', 'bookings');
        $from = $request->input('from') ? Carbon::parse($request->from)->startOfDay() : null;
        $to   = $request->input('to')   ? Carbon::parse($request->to)->endOfDay() : null;

        $query = match($type) {
            'bookings' => Booking::with('user')->when($from, fn($q) => $q->where('created_at', '>=', $from))
                                                ->when($to, fn($q) => $q->where('created_at', '<=', $to)),
            'users'    => User::when($from, fn($q) => $q->where('created_at', '>=', $from))
                              ->when($to, fn($q) => $q->where('created_at', '<=', $to)),
            default    => collect(),
        };

        $rows = match($type) {
            'bookings' => $query->get()->map(fn($b) => [
                $b->reference_code,
                $b->booking_type,
                $b->target_name,
                $b->user?->full_name,
                $b->total_price,
                $b->booking_status,
                $b->created_at?->format('Y-m-d'),
            ]),
            'users' => $query->get()->map(fn($u) => [
                $u->full_name,
                $u->email,
                $u->role,
                $u->heritage_points,
                $u->created_at?->format('Y-m-d'),
            ]),
            default => collect(),
        };

        $headers = match($type) {
            'bookings' => ['Reference', 'Type', 'Target', 'User', 'Amount', 'Status', 'Date'],
            'users'    => ['Name', 'Email', 'Role', 'Points', 'Registered'],
            default    => [],
        };

        $csv = implode(',', $headers) . "\n";
        foreach ($rows as $row) {
            $csv .= implode(',', array_map(fn($v) => '"' . str_replace('"', '""', $v) . '"', $row)) . "\n";
        }

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$type}-export-" . now()->format('Ymd') . ".csv\"",
        ]);
    }

    // Section
    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('user');
        if ($s = $request->q) $query->where('action', 'like', "%{$s}%")->orWhere('description', 'like', "%{$s}%");
        return view('admin.audit-logs', ['logs' => $query->orderByDesc('created_at')->paginate(30)->withQueryString()]);
    }

    // Section
    public function cms()
    {
                                        $defaults = [
            // BRANDING
            ['key' => 'site_name', 'label' => 'Site Name', 'type' => 'text', 'group' => 'branding'],
            ['key' => 'site_logo', 'label' => 'Site Logo (Left)', 'type' => 'image', 'group' => 'branding'],
            ['key' => 'site_logo_right', 'label' => 'Site Logo (Right)', 'type' => 'image', 'group' => 'branding'],
            ['key' => 'site_favicon', 'label' => 'Favicon', 'type' => 'image', 'group' => 'branding'],
            
            // GENERAL (Contact & SEO)
            ['key' => 'contact_phone', 'label' => 'Contact Phone', 'type' => 'text', 'group' => 'general'],
            ['key' => 'contact_email', 'label' => 'Contact Email', 'type' => 'email', 'group' => 'general'],
            ['key' => 'contact_address', 'label' => 'Address', 'type' => 'text', 'group' => 'general'],
            ['key' => 'footer_text', 'label' => 'Footer Text', 'type' => 'text', 'group' => 'general'],
            ['key' => 'meta_description', 'label' => 'Meta Description', 'type' => 'textarea', 'group' => 'general'],
            
            // HOMEPAGE
            ['key' => 'hero_image', 'label' => 'Homepage Hero Image', 'type' => 'image', 'group' => 'homepage'],
            ['key' => 'hero_title', 'label' => 'Hero Title', 'type' => 'text', 'group' => 'homepage'],
            ['key' => 'hero_subtitle', 'label' => 'Hero Subtitle', 'type' => 'textarea', 'group' => 'homepage'],
            
            // ABOUT PAGE (ONLY THESE TWO)
            ['key' => 'about_mission', 'label' => 'Our Mission', 'type' => 'textarea', 'group' => 'general'],
            ['key' => 'about_text', 'label' => 'Our Vision', 'type' => 'textarea', 'group' => 'general'],

            // SOCIAL
            ['key' => 'social_facebook',  'label' => 'Facebook URL',  'type' => 'text', 'group' => 'social'],
            ['key' => 'social_twitter',   'label' => 'Twitter / X URL', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_instagram', 'label' => 'Instagram URL', 'type' => 'text', 'group' => 'social'],
            ['key' => 'social_linkedin',  'label' => 'LinkedIn URL',  'type' => 'text', 'group' => 'social'],
            ['key' => 'social_youtube',   'label' => 'YouTube URL',   'type' => 'text', 'group' => 'social'],
            ['key' => 'social_tiktok',    'label' => 'TikTok URL',    'type' => 'text', 'group' => 'social'],
            ['key' => 'social_whatsapp',  'label' => 'WhatsApp URL',  'type' => 'text', 'group' => 'social'],
        ];

        foreach ($defaults as $d) {
            CmsSetting::firstOrCreate(['key' => $d['key']], $d + ['value' => '']);
        }

        $cms_groups = CmsSetting::orderBy('id')->get()->groupBy('group')->toArray();

        return view('admin.cms', compact('cms_groups'));
    }

    public function cmsUpdate(Request $request)
    {
        foreach ($request->input('settings', []) as $key => $value) {
            // Convert null to empty string to prevent database constraint crashes
            CmsSetting::where('key', $key)->update(['value' => $value ?? '']);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $file) {
                if ($file->isValid()) {
                    $oldSetting = CmsSetting::where('key', $key)->first();
                    if ($oldSetting && $oldSetting->value) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldSetting->value);
                    }
                    $path = \App\Helpers\Helpers::saveUpload($file, 'cms');
                    CmsSetting::where('key', $key)->update(['value' => $path]);
                }
            }
        }

        Helpers::logAction('admin_update_cms', 'CmsSetting');
        return back()->with('success', 'Site settings and branding updated successfully.');
    }

    // Section
    // Section
    public function news(Request $request)
    {
        $query = News::query();
        if ($s = $request->q) $query->where('title', 'like', "%{$s}%");
        return view('admin.news.index', ['articles' => $query->orderByDesc('created_at')->paginate(20)->withQueryString()]);
    }

    public function newsCreate()
    {
        return view('admin.news.form', ['article' => new News()]);
    }

    public function newsStore(Request $request)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'category'   => 'nullable|string|max:100',
            'author'     => 'nullable|string|max:255',
            'tags'       => 'nullable|string|max:255',
            'image_url'  => 'nullable|url',
        ]);
        
        $data['is_featured']  = $request->has('is_featured');
        $data['is_published'] = $request->has('is_published');
        $data['author_user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $data['image_url'] = Helpers::saveUpload($request->file('image'), 'news');
        }

        News::create($data);
        Helpers::logAction('admin_create_news', 'News', null);
        return redirect()->route('admin.news')->with('success', 'Article published.');
    }

    public function newsEdit(int $id)
    {
        return view('admin.news.form', ['article' => News::findOrFail($id)]);
    }

    public function newsUpdate(Request $request, int $id)
    {
        $article = News::findOrFail($id);
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'category'   => 'nullable|string|max:100',
            'author'     => 'nullable|string|max:255',
            'tags'       => 'nullable|string|max:255',
            'image_url'  => 'nullable|url',
        ]);
        
        $data['is_featured']  = $request->has('is_featured');
        $data['is_published'] = $request->has('is_published');

        if ($request->hasFile('image')) {
            $data['image_url'] = Helpers::saveUpload($request->file('image'), 'news');
        }

        $article->update($data);
        Helpers::logAction('admin_update_news', 'News', $id);
        return redirect()->route('admin.news')->with('success', 'Article updated.');
    }

    public function newsDelete(int $id)
    {
        News::findOrFail($id)->delete();
        Helpers::logAction('admin_delete_news', 'News', $id);
        return back()->with('success', 'Article deleted.');
    }
    public function faqs()          { return view('admin.faqs.index', ['faqs' => Faq::orderBy('sort_order')->paginate(30)]); }
    public function faqStore(Request $request) { Faq::create($request->validate(['question' => 'required', 'answer' => 'required', 'category' => 'nullable', 'sort_order' => 'integer'])); return back()->with('success', 'FAQ added.'); }
    public function faqUpdate(Request $request, int $id) { Faq::findOrFail($id)->update($request->only('question', 'answer', 'category', 'sort_order', 'is_active')); return back()->with('success', 'FAQ updated.'); }
    public function faqDelete(int $id) { Faq::findOrFail($id)->delete(); return back()->with('success', 'FAQ deleted.'); }

    // Section
    public function partners() { return view('admin.partners.index', ['partners' => Partner::orderBy('sort_order')->paginate(30)]); }
    public function partnerStore(Request $request)
    {
        $data = $request->validate(['name' => 'required', 'website' => 'nullable', 'sort_order' => 'integer']);
        if ($request->hasFile('logo')) $data['logo_url'] = Helpers::saveUpload($request->file('logo'), 'partners');
        Partner::create($data);
        return back()->with('success', 'Partner added.');
    }
    public function partnerDelete(int $id) { Partner::findOrFail($id)->delete(); return back()->with('success', 'Partner removed.'); }

    // Section
    public function notifyAll(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255', 
            'message' => 'required|string',
            'audience' => 'nullable|string'
        ]);

        $query = User::query();
        
        // Filter by audience if not sending to everyone
        if ($request->filled('audience') && $request->audience !== 'all') {
            $query->where('role', $request->audience);
        }

        $totalUsers = $query->count();
        if ($totalUsers === 0) {
            return back()->with('danger', 'No users found in the selected audience.');
        }

        $now = now();
        $type = $request->type - 'info';

        // Chunk the insert to prevent SQL memory limit crashes on huge databases
        $query->select('id')->chunkById(500, function ($users) use ($request, $type, $now) {
            $data = [];
            foreach ($users as $user) {
                $data[] = [
                    'user_id'    => $user->id,
                    'title'      => $request->title,
                    'message'    => $request->message,
                    'type'       => $type,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            // Bulk Insert (1 query per 500 users instead of 1 query per user)
            \App\Models\Notification::insert($data);
        });

        Helpers::logAction('admin_notify_all', 'Notification', null, "Sent to {$totalUsers} users (Audience: " . ($request->audience - 'all') . ")");
        return back()->with('success', "Notification sent to {$totalUsers} users.");
    }

    // Section
    public function flushDatabase()
    {
        if (Auth::user()->role !== 'Super Admin') {
            return back()->with('danger', 'Only a Super Admin can flush the database.');
        }

        try {
            \Illuminate\Support\Facades\Artisan::call('db:wipe', ['--force' => true]);
            Helpers::logAction('admin_flush_db', 'System', null, 'Database completely flushed');
            return back()->with('success', 'Database flushed successfully. You should now reseed it.');
        } catch (\Exception $e) {
            return back()->with('danger', 'Error flushing database: ' . $e->getMessage());
        }
    }

    public function reseedDatabase()
    {
        if (Auth::user()->role !== 'Super Admin') {
            return back()->with('danger', 'Only a Super Admin can reseed the database.');
        }

        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
            Helpers::logAction('admin_reseed_db', 'System', null, 'Database reseeded');
            return back()->with('success', 'Database migrated and reseeded successfully!');
        } catch (\Exception $e) {
            return back()->with('danger', 'Error reseeding database: ' . $e->getMessage());
        }
    }

    // Section
    public function downloadZip()
    {
        $projectRoot = base_path();
        $zipName     = 'edo-tourism-laravel-' . now()->format('Ymd-His') . '.zip';
        $tmpPath     = sys_get_temp_dir() . '/' . $zipName;

        $zip = new \ZipArchive();
        if ($zip->open($tmpPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return back()->with('danger', 'Could not create ZIP file.');
        }

        $exclude = ['vendor', 'node_modules', '.git', 'storage/logs', 'storage/framework/cache', 'storage/framework/sessions', 'storage/framework/views'];

        $files = new \RecursiveIteratorIterator(
            new \RecursiveCallbackFilterIterator(
                new \RecursiveDirectoryIterator($projectRoot, \RecursiveDirectoryIterator::SKIP_DOTS),
                function ($file, $key, $iterator) use ($exclude, $projectRoot) {
                    $rel = ltrim(str_replace($projectRoot, '', $file->getPathname()), DIRECTORY_SEPARATOR);
                    foreach ($exclude as $ex) {
                        if (str_starts_with($rel, $ex)) return false;
                    }
                    return true;
                }
            )
        );

        foreach ($files as $file) {
            if ($file->isFile()) {
                $rel = ltrim(str_replace($projectRoot, '', $file->getRealPath()), DIRECTORY_SEPARATOR);
                $zip->addFile($file->getRealPath(), $rel);
            }
        }
        $zip->close();

        Helpers::logAction('admin_download_zip', 'System', null, 'Project ZIP downloaded');

        return response()->download($tmpPath, $zipName, ['Content-Type' => 'application/zip'])
                         ->deleteFileAfterSend(true);
    }

    // Section
    public function categories()
    {
        return view('admin.categories.index', ['categories' => Category::orderBy('sort_order')->get()]);
    }

    public function categoryCreate()
    {
        return view('admin.categories.index', ['categories' => Category::orderBy('sort_order')->get()]);
    }

    public function categoryStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:80',
            'color' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
        ]);

        $data['is_active'] = $request->has('is_active');
        if (empty($data['sort_order'])) {
            $data['sort_order'] = Category::max('sort_order') + 1;
        }

        if (empty($data['icon'])) { $data['icon'] = 'bi-tag'; }
        Category::create($data);
        Helpers::logAction('admin_create_category', 'Category', null);
        return back()->with('success', 'Category added.');
    }

    public function categoryUpdate(Request $request, int $id)
    {
        $category = Category::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:80',
            'color' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer',
        ]);

        $data['is_active'] = $request->has('is_active');
        $category->update($data);
        Helpers::logAction('admin_update_category', 'Category', $id);
        return back()->with('success', 'Category updated.');
    }

    public function categoryEdit(int $id)
    {
        return view('admin.categories.index', [
            'categories'   => Category::orderBy('sort_order')->get(),
            'editCategory' => Category::findOrFail($id),
        ]);
    }

    public function categoryDelete(int $id)
    {
        Category::findOrFail($id)->delete();
        Helpers::logAction('admin_delete_category', 'Category', $id);
        return back()->with('success', 'Category deleted.');
    }


    public function staffIndex()
    {
        $staff = \App\Models\StaffMember::orderBy('sort_order')->get();
        return view('admin.staff.index', compact('staff'));
    }

    public function staffCreate()
    {
        return view('admin.staff.form', ['member' => new \App\Models\StaffMember()]);
    }

    public function staffStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email',
            'image_url' => 'nullable|string',
            'image_file' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer',
        ]);
        if ($request->hasFile('image_file')) {
            $data['image_url'] = \App\Helpers\Helpers::saveUpload($request->file('image_file'), 'staff');
        }
        \App\Models\StaffMember::create($data);
        return redirect()->route('admin.staff.index')->with('success', 'Staff member added.');
    }

    public function staffEdit(int $id)
    {
        $member = \App\Models\StaffMember::findOrFail($id);
        return view('admin.staff.form', compact('member'));
    }

    public function staffUpdate(Request $request, int $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email',
            'image_url' => 'nullable|string',
            'image_file' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer',
        ]);
        if ($request->hasFile('image_file')) {
            $data['image_url'] = \App\Helpers\Helpers::saveUpload($request->file('image_file'), 'staff');
        }
        \App\Models\StaffMember::findOrFail($id)->update($data);
        return redirect()->route('admin.staff.index')->with('success', 'Staff member updated.');
    }

    public function staffDelete(int $id)
    {
        \App\Models\StaffMember::findOrFail($id)->delete();
        return back()->with('success', 'Staff member deleted.');
    }

    public function deleteEventImage(int $id)
    {
        $image = \App\Models\EventImage::findOrFail($id);
        \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
        $image->delete();
        Helpers::logAction('admin_delete_event_image', 'EventImage', $id);
        return back()->with('success', 'Gallery image deleted.');
    }
}