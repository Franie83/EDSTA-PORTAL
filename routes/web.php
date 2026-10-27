<?php


use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AttractionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\QrController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\Review;

// Section


// Section

// Section
Route::get('/dashboard-test', [DashboardController::class, 'test'])->name('dashboard.test')->middleware('auth');

// Section
Route::get('/',          [MainController::class, 'home'])->name('main.home');
Route::get('/about',     [MainController::class, 'about'])->name('main.about');
Route::get('/contact',   [MainController::class, 'contact'])->name('main.contact');
Route::get('/culture', [\App\Http\Controllers\CultureController::class, 'index'])->name('main.culture');
Route::post('/contact',  [MainController::class, 'contactSubmit'])->name('main.contact_submit');
Route::get('/faq',       [MainController::class, 'faq'])->name('main.faq');
Route::get('/privacy',   [MainController::class, 'privacy'])->name('main.privacy');
Route::get('/terms',     [MainController::class, 'terms'])->name('main.terms');

// Section
Route::get('/auth/login',              [AuthController::class, 'showLogin'])->name('auth.login');
Route::post('/auth/login',             [AuthController::class, 'login'])->name('auth.login_post');
Route::get('/auth/register',           [AuthController::class, 'showRegister'])->name('auth.register');
Route::post('/auth/register',          [AuthController::class, 'register'])->name('auth.register_post');
Route::get('/auth/logout',             [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/auth/quick-login/{role}', [AuthController::class, 'quickLogin'])->name('auth.quick_login');

// Section
Route::get('/attractions',       [AttractionController::class, 'index'])->name('attractions.list_attractions');
Route::get('/attractions/{id}',  [AttractionController::class, 'show'])->name('attractions.detail');

// Section
Route::get('/guides',       [GuideController::class, 'index'])->name('guides.list_guides');
Route::get('/guides/{id}',  [GuideController::class, 'show'])->name('guides.detail');

// Section
Route::get('/hotels',       [HotelController::class, 'index'])->name('hotels.list_hotels');
Route::get('/hotels/{id}',  [HotelController::class, 'show'])->name('hotels.detail');

// Section
Route::get('/restaurants',       [RestaurantController::class, 'index'])->name('restaurants.list_restaurants');
Route::get('/restaurants/{id}',  [RestaurantController::class, 'show'])->name('restaurants.detail');

// Section
Route::get('/events',       [EventController::class, 'index'])->name('events.list_events');
Route::get('/events/{id}',  [EventController::class, 'show'])->name('events.detail');

// Section
Route::get('/news',       [NewsController::class, 'index'])->name('news.list_news');
Route::get('/news/{id}',  [NewsController::class, 'show'])->name('news.detail');
Route::post('/news/{id}/comment', [NewsController::class, 'comment'])->middleware('auth')->name('news.comment');

// Section
Route::get('/qr/{code}',  [QrController::class, 'scan'])->name('qr.scan');
Route::get('/search',     [SearchController::class, 'results'])->name('search.results');

// Section
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard',           [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/profile',   [DashboardController::class, 'profile'])->name('dashboard.profile');

    // Tour Operator & Club/Lounge dashboards
    Route::get('/dashboard/tours', [DashboardController::class, 'myTourOperators'])->name('dashboard.tour-operator.index');
    Route::get('/dashboard/tours/create', [DashboardController::class, 'createTourAttraction'])->name('dashboard.tour-operator.create');
    Route::post('/dashboard/tours', [DashboardController::class, 'storeTourAttraction'])->name('dashboard.tour-operator.store');
    Route::get('/dashboard/tours/{id}/edit', [DashboardController::class, 'editTourAttraction'])->name('dashboard.tour-operator.edit');
    Route::put('/dashboard/tours/{id}', [DashboardController::class, 'updateTourAttraction'])->name('dashboard.tour-operator.update');
    Route::post('/dashboard/tours/{id}/update', [DashboardController::class, 'updateTourAttraction'])->name('dashboard.tour-operator.update.post');
    Route::get('/dashboard/tours/image/{id}/delete', [DashboardController::class, 'deleteTourAttractionImage'])->name('dashboard.tour-operator.image_delete');
    Route::get('/dashboard/venue', [DashboardController::class, 'myClubLounges'])->name('dashboard.club-lounge.index');
    Route::get('/dashboard/venue/create', [DashboardController::class, 'createClubLounge'])->name('dashboard.club-lounge.create');
    Route::post('/dashboard/venue', [DashboardController::class, 'storeClubLounge'])->name('dashboard.club-lounge.store');
    Route::get('/dashboard/venue/{id}/edit', [DashboardController::class, 'editClubLounge'])->name('dashboard.club-lounge.edit');
    Route::post('/dashboard/venue/{id}/update', [DashboardController::class, 'updateClubLounge'])->name('dashboard.club-lounge.update');
    Route::get('/dashboard/venue/image/{id}/delete', [DashboardController::class, 'deleteDashboardClubImage'])->name('dashboard.club-lounge.image_delete');
    Route::post('/dashboard/profile',  [DashboardController::class, 'updateProfile'])->name('dashboard.update_profile');
    Route::post('/dashboard/notifications/{id}/read',  [DashboardController::class, 'markRead'])->name('dashboard.mark_read');
    Route::post('/dashboard/notifications/read-all',   [DashboardController::class, 'markAllRead'])->name('dashboard.mark_all_read');
    Route::get('/dashboard/bookings/{id}',             [DashboardController::class, 'bookingDetail'])->name('dashboard.booking_detail');
    Route::post('/dashboard/favourites/{type}/{id}',   [DashboardController::class, 'toggleFavourite'])->name('dashboard.toggle_favourite');

    // Section
    Route::get('/dashboard/guide/edit',        [DashboardController::class, 'editGuide'])->name('dashboard.guide.edit');
    Route::post('/dashboard/guide/update',     [DashboardController::class, 'updateGuide'])->name('dashboard.guide.update');
    Route::get('/dashboard/guide/image/{id}/delete', [DashboardController::class, 'deleteDashboardGuideImage'])->name('dashboard.guide.image_delete');

    // Section
    Route::get('/dashboard/hotels',           [DashboardController::class, 'myHotels'])->name('dashboard.hotels.index');
    Route::get('/dashboard/hotels/create',    [DashboardController::class, 'createHotel'])->name('dashboard.hotels.create');
    Route::post('/dashboard/hotels',          [DashboardController::class, 'storeHotel'])->name('dashboard.hotels.store');
    Route::get('/dashboard/hotels/{id}/edit', [DashboardController::class, 'editHotel'])->name('dashboard.hotels.edit');
    Route::put('/dashboard/hotels/{id}',      [DashboardController::class, 'updateHotel'])->name('dashboard.hotels.update');
    // Fallback for browsers that don't support PUT
    Route::post('/dashboard/hotels/{id}/update', [DashboardController::class, 'updateHotel'])->name('dashboard.hotels.update.post');
    Route::get('/dashboard/hotels/image/{id}/delete', [DashboardController::class, 'deleteDashboardHotelImage'])->name('dashboard.hotels.image_delete');

    // Section
    Route::get('/dashboard/restaurants',           [DashboardController::class, 'myRestaurants'])->name('dashboard.restaurants.index');
    Route::get('/dashboard/restaurants/create',    [DashboardController::class, 'createRestaurant'])->name('dashboard.restaurants.create');
    Route::post('/dashboard/restaurants',          [DashboardController::class, 'storeRestaurant'])->name('dashboard.restaurants.store');
    Route::get('/dashboard/restaurants/{id}/edit', [DashboardController::class, 'editRestaurant'])->name('dashboard.restaurants.edit');
    Route::put('/dashboard/restaurants/{id}',      [DashboardController::class, 'updateRestaurant'])->name('dashboard.restaurants.update');
    Route::post('/dashboard/restaurants/{id}/update', [DashboardController::class, 'updateRestaurant'])->name('dashboard.restaurants.update.post');
    Route::get('/dashboard/restaurants/image/{id}/delete', [DashboardController::class, 'deleteDashboardRestaurantImage'])->name('dashboard.restaurants.image_delete');

    // Section
    Route::get('/dashboard/events',           [DashboardController::class, 'myEvents'])->name('dashboard.events.index');
    Route::get('/dashboard/events/create',    [DashboardController::class, 'createEvent'])->name('dashboard.events.create');
    Route::post('/dashboard/events',          [DashboardController::class, 'storeEvent'])->name('dashboard.events.store');
    Route::get('/dashboard/events/{id}/edit', [DashboardController::class, 'editEvent'])->name('dashboard.events.edit');
    Route::put('/dashboard/events/{id}',      [DashboardController::class, 'updateEvent'])->name('dashboard.events.update');
    Route::post('/dashboard/events/{id}/update', [DashboardController::class, 'updateEvent'])->name('dashboard.events.update.post');
    Route::get('/dashboard/events/image/{id}/delete', [DashboardController::class, 'deleteDashboardEventImage'])->name('dashboard.events.image_delete');

    // Bookings
    Route::get('/bookings/new/{type}/{id}',       [BookingController::class, 'create'])->name('bookings.new_booking');
    Route::post('/bookings/new/{type}/{id}',      [BookingController::class, 'store'])->name('bookings.store_booking');
    Route::post('/bookings/{id}/cancel',          [BookingController::class, 'cancel'])->name('bookings.cancel_booking');
    Route::post('/bookings/{id}/target-confirm',  [BookingController::class, 'targetConfirm'])->name('bookings.target_confirm_booking');
    Route::post('/bookings/{id}/target-reject',   [BookingController::class, 'targetReject'])->name('bookings.target_reject_booking');
    Route::post('/bookings/{id}/complete',        [BookingController::class, 'complete'])->name('bookings.complete_booking');

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// Section
Route::prefix('/admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/',           [AdminController::class, 'dashboard'])->name('dashboard');

    // Users
    Route::get('/users',             [AdminController::class, 'users'])->name('users');
    Route::get('/users/new',         [AdminController::class, 'userCreate'])->name('user_create');
    Route::post('/users/new',        [AdminController::class, 'userStore'])->name('user_store');
    Route::get('/users/{id}/edit',   [AdminController::class, 'userEdit'])->name('user_edit');
    Route::post('/users/{id}/edit',  [AdminController::class, 'userUpdate'])->name('user_update');
    Route::post('/users/{id}/delete',[AdminController::class, 'userDelete'])->name('user_delete');

    // Attractions
    Route::get('/attractions',               [AdminController::class, 'attractions'])->name('attractions');
    Route::get('/attractions/new',           [AdminController::class, 'attractionCreate'])->name('attraction_create');
    Route::post('/attractions/new',          [AdminController::class, 'attractionStore'])->name('attraction_store');

    // Attraction gallery image delete
    Route::get('/attractions/image/{id}/delete', [AdminController::class, 'deleteAttractionImage'])->name('attraction_image_delete');
    Route::get('/attractions/{id}/edit',     [AdminController::class, 'attractionEdit'])->name('attraction_edit');
    Route::post('/attractions/{id}/edit',    [AdminController::class, 'attractionUpdate'])->name('attraction_update');
    Route::post('/attractions/{id}/delete',  [AdminController::class, 'attractionDelete'])->name('attraction_delete');
    Route::post('/attractions/{id}/qr',      [AdminController::class, 'attractionRegenQr'])->name('attraction_regen_qr');
    Route::post('/attractions/generate-all-qr', [AdminController::class, 'generateAllQr'])->name('attractions.generate_all_qr');

    // Bulk Upload Attractions
    Route::get('/attractions/template', [AdminController::class, 'downloadAttractionTemplate'])->name('attraction_template');
    Route::post('/attractions/bulk-upload', [AdminController::class, 'bulkUploadAttractions'])->name('attraction_bulk_upload');
        // Bulk Upload Guides
    Route::get('/guides/template', [AdminController::class, 'downloadGuideTemplate'])->name('guide_template');
    Route::post('/guides/bulk-upload', [AdminController::class, 'bulkUploadGuides'])->name('guide_bulk_upload');

    // Guides
    Route::get('/guides',               [AdminController::class, 'guides'])->name('guides');
    Route::get('/guides/new',           [AdminController::class, 'guideCreate'])->name('guide_create');
    Route::post('/guides/new',          [AdminController::class, 'guideStore'])->name('guide_store');
    Route::get('/guides/{id}/edit',     [AdminController::class, 'guideEdit'])->name('guide_edit');
    Route::post('/guides/{id}/edit',    [AdminController::class, 'guideUpdate'])->name('guide_update');
    Route::post('/guides/{id}/verify',  [AdminController::class, 'guideVerify'])->name('guide_verify');
    Route::post('/guides/{id}/delete',  [AdminController::class, 'guideDelete'])->name('guide_delete');

    // Bulk Upload Hotels
    Route::get('/hotels/template', [AdminController::class, 'downloadHotelTemplate'])->name('hotel_template');
    Route::post('/hotels/bulk-upload', [AdminController::class, 'bulkUploadHotels'])->name('hotel_bulk_upload');
    // Hotels
    Route::get('/hotels',              [AdminController::class, 'hotels'])->name('hotels');
    Route::get('/hotels/new',          [AdminController::class, 'hotelCreate'])->name('hotel_create');
    Route::post('/hotels/new',         [AdminController::class, 'hotelStore'])->name('hotel_store');
    Route::get('/hotels/{id}/edit',    [AdminController::class, 'hotelEdit'])->name('hotel_edit');
    Route::post('/hotels/{id}/edit',   [AdminController::class, 'hotelUpdate'])->name('hotel_update');
    Route::post('/hotels/{id}/delete', [AdminController::class, 'hotelDelete'])->name('hotel_delete');
    Route::post('/hotels/{id}/approve', [AdminController::class, 'approveHotel'])->name('hotel_approve');
    Route::get('/hotels/image/{id}/delete', [AdminController::class, 'deleteHotelImage'])->name('hotel_image_delete');

    // Bulk Upload Restaurants
    Route::get('/restaurants/template', [AdminController::class, 'downloadRestaurantTemplate'])->name('restaurant_template');
    Route::post('/restaurants/bulk-upload', [AdminController::class, 'bulkUploadRestaurants'])->name('restaurant_bulk_upload');
    // Restaurants
    Route::get('/restaurants',              [AdminController::class, 'restaurants'])->name('restaurants');
    Route::get('/restaurants/new',          [AdminController::class, 'restaurantCreate'])->name('restaurant_create');
    Route::post('/restaurants/new',         [AdminController::class, 'restaurantStore'])->name('restaurant_store');
    Route::get('/restaurants/{id}/edit',    [AdminController::class, 'restaurantEdit'])->name('restaurant_edit');
    Route::post('/restaurants/{id}/edit',   [AdminController::class, 'restaurantUpdate'])->name('restaurant_update');
    Route::post('/restaurants/{id}/delete', [AdminController::class, 'restaurantDelete'])->name('restaurant_delete');
    Route::post('/restaurants/{id}/approve', [AdminController::class, 'approveRestaurant'])->name('restaurant_approve');
    Route::get('/restaurants/image/{id}/delete', [AdminController::class, 'deleteRestaurantImage'])->name('restaurant_image_delete');

    // Events
    Route::get('/events',              [AdminController::class, 'events'])->name('events');
    Route::get('/events/new',          [AdminController::class, 'eventCreate'])->name('event_create');
    Route::post('/events/new',         [AdminController::class, 'eventStore'])->name('event_store');
    Route::get('/events/{id}/edit',    [AdminController::class, 'eventEdit'])->name('event_edit');
    Route::get('/events/image/{id}/delete', [AdminController::class, 'deleteEventImage'])->name('events.image_delete');
    Route::post('/events/{id}/edit',   [AdminController::class, 'eventUpdate'])->name('event_update');
    Route::post('/events/{id}/delete', [AdminController::class, 'eventDelete'])->name('event_delete');
    Route::post('/events/{id}/approve', [AdminController::class, 'approveEvent'])->name('event_approve');

    // News
    Route::get('/news',              [AdminController::class, 'news'])->name('news');
    Route::get('/news/new',          [AdminController::class, 'newsCreate'])->name('news_create');
    Route::post('/news/new',         [AdminController::class, 'newsStore'])->name('news_store');
    Route::get('/news/{id}/edit',    [AdminController::class, 'newsEdit'])->name('news_edit');
    Route::post('/news/{id}/edit',   [AdminController::class, 'newsUpdate'])->name('news_update');
    Route::post('/news/{id}/delete', [AdminController::class, 'newsDelete'])->name('news_delete');

    // Categories
    Route::get('/categories',              [AdminController::class, 'categories'])->name('categories');
    Route::get('/categories/new',          [AdminController::class, 'categoryCreate'])->name('category_create');
    Route::post('/categories/new',         [AdminController::class, 'categoryStore'])->name('category_store');
    Route::post('/categories/{id}/edit',   [AdminController::class, 'categoryUpdate'])->name('category_update');
    Route::get('/categories/{id}/edit',    [AdminController::class, 'categoryEdit'])->name('category_edit');
    Route::post('/categories/{id}/delete', [AdminController::class, 'categoryDelete'])->name('category_delete');

    // Bookings
    Route::get('/bookings',              [AdminController::class, 'bookings'])->name('bookings');
    Route::post('/bookings/{id}/approve',[AdminController::class, 'bookingApprove'])->name('booking_approve');
    Route::post('/bookings/{id}/reject', [AdminController::class, 'bookingReject'])->name('booking_reject');
    Route::get('/bookings/{id}',         [AdminController::class, 'bookingDetail'])->name('booking_detail');

    // Reviews
    Route::get('/reviews',              [AdminController::class, 'reviews'])->name('reviews');
    Route::post('/reviews/{id}/delete', [AdminController::class, 'reviewDelete'])->name('review_delete');
    Route::post('/reviews/{id}/approve', [AdminController::class, 'approveReview'])->name('review_approve');

    // Analytics / Audit / CMS
    Route::get('/analytics',  [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit_logs');
    Route::get('/cms',        [AdminController::class, 'cms'])->name('cms');
    Route::post('/cms',       [AdminController::class, 'cmsUpdate'])->name('cms_update');

    // FAQs
    Route::get('/faqs',              [AdminController::class, 'faqs'])->name('faqs');
    Route::post('/faqs/new',         [AdminController::class, 'faqStore'])->name('faq_store');
    Route::post('/faqs/{id}/edit',   [AdminController::class, 'faqUpdate'])->name('faq_update');
    Route::post('/faqs/{id}/delete', [AdminController::class, 'faqDelete'])->name('faq_delete');

    // Partners
    Route::get('/partners',               [AdminController::class, 'partners'])->name('partners');
    Route::post('/partners/new',          [AdminController::class, 'partnerStore'])->name('partner_store');
    Route::post('/partners/{id}/delete',  [AdminController::class, 'partnerDelete'])->name('partner_delete');

    // Notify all
    Route::post('/notify-all', [AdminController::class, 'notifyAll'])->name('notify_all');

    // CSV/Excel export
    Route::get('/analytics/export', [AdminController::class, 'analyticsExport'])->name('analytics_export');

    // Database Tools
    Route::post('/database/flush', [AdminController::class, 'flushDatabase'])->name('database.flush');
    Route::post('/database/reseed', [AdminController::class, 'reseedDatabase'])->name('database.reseed');
        // Download ZIP
    Route::get('/download-zip', [AdminController::class, 'downloadZip'])->name('download_zip');

        // Danger Zone Routes

        // -- Edo Culture entities --
        Route::resource('languages', \App\Http\Controllers\EdoLanguageController::class);
        Route::get ('languages/template',        [\App\Http\Controllers\EdoLanguageController::class, 'downloadTemplate'])->name('languages.template');
        Route::post('languages/bulk-upload',     [\App\Http\Controllers\EdoLanguageController::class, 'bulkUpload'])->name('languages.bulk_upload');

        Route::resource('cuisines', \App\Http\Controllers\EdoCuisineController::class);
        Route::get ('cuisines/template',         [\App\Http\Controllers\EdoCuisineController::class, 'downloadTemplate'])->name('cuisines.template');
        Route::post('cuisines/bulk-upload',      [\App\Http\Controllers\EdoCuisineController::class, 'bulkUpload'])->name('cuisines.bulk_upload');

        Route::resource('attires', \App\Http\Controllers\TraditionalAttireController::class);
        Route::get ('attires/template',          [\App\Http\Controllers\TraditionalAttireController::class, 'downloadTemplate'])->name('attires.template');
        Route::post('attires/bulk-upload',       [\App\Http\Controllers\TraditionalAttireController::class, 'bulkUpload'])->name('attires.bulk_upload');

        Route::resource('tour-operators', \App\Http\Controllers\TourOperatorController::class)->parameters(['tour-operators' => 'operator']);
        Route::get ('tour-operators/template',   [\App\Http\Controllers\TourOperatorController::class, 'downloadTemplate'])->name('tour-operators.template');
        Route::post('tour-operators/bulk-upload',[\App\Http\Controllers\TourOperatorController::class, 'bulkUpload'])->name('tour-operators.bulk_upload');

        Route::resource('clubs-lounges', \App\Http\Controllers\ClubLoungeController::class)->parameters(['clubs-lounges' => 'club']);
        Route::get ('clubs-lounges/template',    [\App\Http\Controllers\ClubLoungeController::class, 'downloadTemplate'])->name('clubs-lounges.template');
        Route::post('clubs-lounges/bulk-upload', [\App\Http\Controllers\ClubLoungeController::class, 'bulkUpload'])->name('clubs-lounges.bulk_upload');


    });
// Password Reset Routes
Route::get('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset')->where('token', '.*');
Route::post('/reset-password', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Public Staff Directory
Route::get('/staff', [\App\Http\Controllers\MainController::class, 'staff'])->name('main.staff');

// Admin Staff Management (inside admin group)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/staff', [\App\Http\Controllers\AdminController::class, 'staffIndex'])->name('staff.index');
    Route::get('/staff/new', [\App\Http\Controllers\AdminController::class, 'staffCreate'])->name('staff.create');
    Route::post('/staff/new', [\App\Http\Controllers\AdminController::class, 'staffStore'])->name('staff.store');
    Route::get('/staff/{id}/edit', [\App\Http\Controllers\AdminController::class, 'staffEdit'])->name('staff.edit');
    Route::post('/staff/{id}/edit', [\App\Http\Controllers\AdminController::class, 'staffUpdate'])->name('staff.update');
    Route::post('/staff/{id}/delete', [\App\Http\Controllers\AdminController::class, 'staffDelete'])->name('staff.delete');
});

// Public Clubs & Lounges
Route::get('/clubs-lounges', [\App\Http\Controllers\MainController::class, 'clubs'])->name('clubs.list');
Route::get('/clubs-lounges/{id}', [\App\Http\Controllers\MainController::class, 'clubShow'])->name('clubs.detail');
// Permits & Certificates
Route::get('/verify/{number}', [\App\Http\Controllers\PermitController::class, 'verify'])->name('permits.verify');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/permits', [\App\Http\Controllers\PermitController::class, 'index'])->name('permits.index');
    Route::get('/permits/create', [\App\Http\Controllers\PermitController::class, 'create'])->name('permits.create');
    Route::post('/permits', [\App\Http\Controllers\PermitController::class, 'store'])->name('permits.store');
    Route::get('/permits/{id}', [\App\Http\Controllers\PermitController::class, 'show'])->name('permits.show');
    Route::get('/permits/{id}/edit', [\App\Http\Controllers\PermitController::class, 'edit'])->name('permits.edit');
    Route::put('/permits/{id}', [\App\Http\Controllers\PermitController::class, 'update'])->name('permits.update');
    Route::delete('/permits/{id}', [\App\Http\Controllers\PermitController::class, 'destroy'])->name('permits.destroy');
    Route::post('/permits/{id}/expire', [\App\Http\Controllers\PermitController::class, 'expire'])->name('permits.expire');
});
    // Business Owner Permit Routes
    Route::prefix('dashboard/permits')->name('dashboard.permits.')->group(function () {
        Route::get('/', [\App\Http\Controllers\PermitApplicationController::class, 'index'])->name('index');
        Route::get('/apply', [\App\Http\Controllers\PermitApplicationController::class, 'create'])->name('create');
        Route::post('/pay', [\App\Http\Controllers\PermitApplicationController::class, 'processPayment'])->name('process');
        Route::get('/{id}', [\App\Http\Controllers\PermitApplicationController::class, 'show'])->name('show');
    });
