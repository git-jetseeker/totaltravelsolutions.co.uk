<?php

use App\Http\Controllers\front\HomeController as FrontHomeController;
use App\Http\Controllers\front\BookingController as FrontBookingController;
use App\Http\Controllers\front\LoungeController as FrontLoungeController;
use App\Http\Controllers\front\HotelController as FrontHotelController;
use App\Http\Controllers\front\TransferController as FrontTransferController;
use App\Http\Controllers\TicketsController as TicketsController;
use App\Http\Controllers\SubscribersController as SubscribersController;
use App\Http\Controllers\CustomerController as CustomerController;
use App\Http\Controllers\HomeController as HomeController;
use App\Http\Controllers\CronController as CronController;
use App\Http\Controllers\AirportController as AirportController;
use App\Http\Controllers\CompanyController as CompanyController;
use App\Http\Controllers\PartnersController as PartnersController;
use App\Http\Controllers\CompaniesProductPriceController as CompaniesProductPriceController;
use App\Http\Controllers\PagesController as PagesController;
use App\Http\Controllers\FaqsController as FaqsController;
use App\Http\Controllers\ReviewsController as ReviewsController;
use App\Http\Controllers\DiscountsController as DiscountsController;
use App\Http\Controllers\EmailTemplatesController as EmailTemplatesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\front\EmailController as FrontEmailController;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Route::get('/cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('optimize:clear');
    echo "cache cleared";
});

// ********************************************************************************* Front Routes *********************************************************************************
Route::get('/', [FrontHomeController::class, 'index'])->name("main");
Route::post('/store-email', [FrontEmailController::class, 'store'])->name('store.email');
Route::get('/test-email', [EmailController::class, 'testEmail']);

Route::get('/landing', [FrontHomeController::class, 'landing'])->name("landing");
Route::get('/all-reviews', [FrontHomeController::class, 'all_reviews'])->name("allReviews");

Route::get('/search', [FrontHomeController::class, 'search'])->name("search");
Route::post('/result', [FrontHomeController::class, 'getSearchResult'])->name('searchresult.ajax');
Route::get('/aphinfo', [FrontHomeController::class, 'getAphInfo'])->name("aphinfo");
Route::get('/terms-and-conditions', [FrontHomeController::class, 'terms_and_conditions'])->name("terms-and-conditions");
Route::get('/affiliates', [FrontHomeController::class, 'affiliates'])->name("affiliates");
Route::get('/cookies', [FrontHomeController::class, 'cookies'])->name("cookies");
Route::get('/privacy-policy', [FrontHomeController::class, 'privacy_policy'])->name("privacy-policy");
Route::get('/site-security', [FrontHomeController::class, 'site_security'])->name("site-security");
Route::get('/airport-guide', [FrontHomeController::class, 'airport_guide'])->name("airport_guide");
Route::get('/resultfortravelez', [FrontHomeController::class, 'getSearchResultForTravelez'])->name("resultfortravelez");
Route::get('/result', [FrontHomeController::class, 'getSearchResult'])->name('searchresult');
Route::post('/booking', [FrontHomeController::class, 'addBookingForm'])->name("addBookingForm");
Route::get('/booking', [FrontHomeController::class, 'showBookingForm'])->name("booking.show");
Route::post('/loadinfo', [FrontHomeController::class, 'loadinfo'])->name("loadinfo");
Route::get('/booking/incomplete/{id}', [FrontHomeController::class, 'addBookingFormincomplete'])->name("addBookingForm2");
Route::post('/checkBooking', [FrontBookingController::class, 'checkBooking'])->name("checkBooking");
Route::post('/booking/checkout', [FrontBookingController::class, 'checkout'])->name("checkout");
Route::post('/booking/incomplete/booking/checkout', [FrontBookingController::class, 'checkout'])->name("checkout1");
Route::post('/booking/payout', [FrontBookingController::class, 'paymentwithstripe'])->name("paymentwithstripe");
Route::post('booking/incomplete/booking/payout', [FrontBookingController::class, 'paymentwithstripe'])->name("paymentwithstripe");
Route::post('/booking/payout_failed', [FrontBookingController::class, 'payout_failed'])->name("payout_failed");
Route::get('/booking/thankyou/{id}', [FrontBookingController::class, 'thanyou'])->name("thankyou");
Route::get('/ppc/airport-parking', [FrontHomeController::class, 'ppc_airport_parking'])->name("ppcAirportParking");
Route::post('/booking/paymentwithPayzone', [FrontBookingController::class, 'paymentwithPayzone'])->name("paymentwithPayzone");
Route::get('/searchresult_lounge', [FrontHomeController::class, 'getSearchResultLounge'])->name("searchresult_lounge");
Route::post('/searchresult_lounge', [FrontHomeController::class, 'getSearchResultLounge'])->name("searchresult_lounge.ajax");
Route::post('/booking_lounge', [FrontLoungeController::class, 'addBookingFormLounge'])->name("addBookingFormLounge");
Route::get('/booking_lounge', [FrontLoungeController::class, 'showBookingFormLounge'])->name("booking_lounge.show");
Route::post('/lounge_checkout', [FrontLoungeController::class, 'lounge_checkout'])->name("lounge_checkout");
Route::post('/checkBookingLounge', [FrontLoungeController::class, 'checkBookingLounge'])->name("checkBookingLounge");
Route::post('/booking/payout_lounge', [FrontLoungeController::class, 'payout_lounge'])->name("payout_lounge");
Route::post('/booking/payout_failed_lounge', [FrontLoungeController::class, 'payout_failed_lounge'])->name("payout_failed_lounge");
Route::get('/booking_lounge/thankyou/{id}', [FrontLoungeController::class, 'thankyou'])->name("thankyou_lounge");
Route::get('/searchresult_hotel', [FrontHomeController::class, 'getSearchResultHotel'])->name("searchresult_hotel");
Route::post('/searchresult_hotel', [FrontHomeController::class, 'getSearchResultHotel'])->name("searchresult_hotel.ajax");
Route::get('/hotel/search/results', [FrontHomeController::class, 'hotelSearchResultsFromSession'])->name("hotel.search.results");
Route::get('/hotel/detail/{productId}', [FrontHomeController::class, 'hotelDetail'])->name("hotel.detail");
Route::post('/booking_hotel', [FrontHotelController::class, 'addBookingFormHotel'])->name("addBookingFormHotel");
Route::get('/booking_hotel', [FrontHotelController::class, 'showBookingFormHotel'])->name("booking_hotel.show");
Route::post('/hotel_checkout', [FrontHotelController::class, 'hotel_checkout'])->name("hotel_checkout");
Route::post('/checkBookingHotel', [FrontHotelController::class, 'checkBookingHotel'])->name("checkBookingHotel");
Route::post('/hotels/payout', [FrontHotelController::class, 'payout_hotel'])->name("payout_hotel");
Route::get('/hotels/thankyou/{id}', [FrontHotelController::class, 'thankyou'])->name("thankyou_hotel");
Route::get('/about-us', [FrontHomeController::class, 'about_us'])->name("about-us");
Route::post('/searchresult_transfer', [FrontHomeController::class, 'getSearchResultTransfer'])->name("searchresult_transfer");
Route::get('/searchresult_transfer', [FrontHomeController::class, 'getSearchResultTransfer'])->name("searchresult_transfer.show");
Route::post('/get_location_suggestion', [FrontTransferController::class, 'get_location_suggestion'])->name("get_location_suggestion");
Route::post('/get_location_suggestion_drop', [FrontTransferController::class, 'get_location_suggestion_drop'])->name("get_location_suggestion_drop");
Route::post('/booking_transfer', [FrontTransferController::class, 'addBookingFormTransfer'])->name("addBookingFormTransfer");
Route::get('/booking_transfer', [FrontTransferController::class, 'showBookingFormTransfer'])->name("booking_transfer.show");
Route::post('/transfer_checkout', [FrontTransferController::class, 'transfer_checkout'])->name("transfer_checkout");
Route::post('/checkBookingTransfer', [FrontTransferController::class, 'checkBookingTransfer'])->name("checkBookingTransfer");
Route::post('/booking/payout_failed_transfer', [FrontTransferController::class, 'payout_failed_transfer'])->name("payout_failed_transfer");
Route::post('/booking/payout_transfer', [FrontTransferController::class, 'payout_transfer'])->name("payout_transfer");
Route::get('/airport/{slug}', [FrontHomeController::class, 'page'])->name("page");
Route::get('/airports', [FrontHomeController::class, 'airports'])->name("airports");
Route::get('/support', [TicketsController::class, 'index'])->name("support");
Route::post('/store', [TicketsController::class, 'store'])->name("submit-ticket");
Route::post('/submit-reply', [TicketsController::class, 'submit_reply'])->name("submit-reply");
Route::post('/addNote', [TicketsController::class, 'addNote'])->name("addNote");
Route::post('/search-ticket', [TicketsController::class, 'search_ticket'])->name("search_ticket");
// Route::post('/search-ticket', [TicketsController::class, 'search-ticket'])->name("search_ticket");
Route::get('/ticket/view/{id}', [TicketsController::class, 'view'])->name("view-ticket");
Route::get('/manage-booking', [FrontBookingController::class, 'manage_booking'])->name("manage_booking");
Route::get('/manage-booking/detail', [FrontBookingController::class, 'showManageBookingDetail'])->name("manage_booking.show");
Route::post('/booking-search', [FrontBookingController::class, 'booking_search'])->name("booking_search");
Route::post('/reSendEmailBooking', [FrontBookingController::class, 'reSendEmailBooking'])->name("reSendEmailBooking");
Route::get('/admin/export_subscriber_excel', [SubscribersController::class, 'export_subscriber_excel'])->name("export_subscriber_excel");
Route::get('/admin/export_customer_excel', [CustomerController::class, 'export_customer_excel'])->name("export_customer_excel");
Route::get('/faqs', [FrontHomeController::class, 'faqs'])->name("faqs");
Route::get('/faqs_pages', [FrontHomeController::class, 'faqs_pages'])->name("faqs_pages");
Route::get('/parkingzone-reviews', [FrontHomeController::class, 'reviews'])->name("reviews");
Route::get('/parkingzone-reviews2', [FrontHomeController::class, 'reviews2'])->name("reviews2");

Route::get('/airport-types', [FrontHomeController::class, 'airport_types'])->name("airport_types");
Route::post('/subscribe_user', [FrontHomeController::class, 'subscribe_user'])->name("subscribe_user");
Route::post('/subscribe_user_and_discount', [FrontHomeController::class, 'subscribe_user_and_discount'])->name("subscribe_user_and_discount");
Route::get('/sitemap', [FrontHomeController::class, 'sitemap'])->name("sitemap");
// Route::get('/airport-parking', [FrontHomeController::class, 'airportsparking'])->name("airportsparking");

Route::get('/airport-parking', function () {
    return redirect('/');
})->name("airportsparking");

Route::get('/parking-services', [FrontHomeController::class, 'airportsparking'])->name("airportsparking1");
Route::get('/airport-transfer', [FrontHomeController::class, 'airporttransfer'])->name("airporttransfer");
Route::get('/lounges', [FrontHomeController::class, 'lounges'])->name("lounges");
Route::get('/airport-hotels', [FrontHomeController::class, 'airportHotels'])->name("airport-hotels");
Route::get('/feedback', [FrontHomeController::class, 'feedback'])->name("feedback");
Route::post('/feedback', [FrontHomeController::class, 'store'])->name("submit-feedback");
// Route::get('/contact-us', [FrontHomeController::class, 'contact'])->name("contact-us");
Route::post('/contact-us-submit', [FrontHomeController::class, 'contactUsSubmit'])->name("contact-us-submit");
Route::get('/blogs', [FrontHomeController::class, 'blogs'])->name("blogs");
Route::get('/blog/{slug}', [FrontHomeController::class, 'blog_detail'])->name("blog_detail");
Route::get('/{page}', [FrontHomeController::class, 'static_page'])->name("static_page");


Route::resource('/admin/airport','AirportController');
Route::resource('/admin/company','CompanyController');
Route::resource('/admin/agent','PartnersController');
Route::resource('/admin/awards','AwardsController');
Route::resource('/admin/company/plan','CompaniesProductPriceController');
Route::resource('/admin/pages','PagesController');
Route::resource('/admin/faqs','FaqsController');
Route::resource('/admin/reviews','ReviewsController');
Route::resource('/admin/subscribers','SubscribersController');
Route::resource('/admin/customers','CustomerController');
Route::resource('/admin/discounts','DiscountsController');
Route::resource('/admin/emails','EmailTemplatesController');
Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name("home");

Route::get('/cron/incomplete', [CronController::class, 'index'])->name("cron_incomplete");
Route::get('/cron/incompletesms', [CronController::class, 'sendsms_incomplete'])->name("cron_incomplete_sms");
Route::get('/cron/mailchimp', [CronController::class, 'sendmails_mailchimp'])->name("mailchimp");



// Route::get('/', 'front\HomeController@index')->name("main");
// Route::get('/search', 'front\HomeController@search')->name("search");
// Route::post('/result', 'front\HomeController@getSearchResult')->name("searchresult");
// Route::get('/aphinfo', 'front\HomeController@getAphInfo')->name("aphinfo");
// Route::get('/resultfortravelez', 'front\HomeController@getSearchResultForTravelez')->name("resultfortravelez"); 
// Route::get('/result', 'front\HomeController@getSearchResult')->name("searchresult");
// Route::post('/booking', 'front\HomeController@addBookingForm')->name("addBookingForm");
// Route::post('/loadinfo', 'front\HomeController@loadinfo')->name("loadinfo");

// Route::get('/booking/incomplete/{id}', 'front\HomeController@addBookingFormincomplete')->name("addBookingForm2");

// Route::post('/checkBooking', 'front\BookingController@checkBooking')->name("checkBooking");

// Route::post('/booking/checkout', 'front\BookingController@checkout')->name("checkout");

// Route::post('/booking/incomplete/booking/checkout', 'front\BookingController@checkout')->name("checkout1");

// Route::post('/booking/payout', 'front\BookingController@paymentwithstripe')->name("paymentwithstripe");

// Route::post('/booking/payout_failed', 'front\BookingController@payout_failed')->name("payout_failed");

// Route::get('/booking/thankyou/{id}', 'front\BookingController@thanyou')->name("thankyou");

// Route::post('/booking/paymentwithPayzone', 'front\BookingController@paymentwithPayzone')->name("paymentwithPayzone");


// Route::get('/searchresult_lounge', 'front\HomeController@getSearchResultLounge')->name("searchresult_lounge");

// Route::post('/searchresult_lounge', 'front\HomeController@getSearchResultLounge')->name("searchresult_lounge");

// Route::post('/booking_lounge', 'front\LoungeController@addBookingFormLounge')->name("addBookingFormLounge");

// Route::post('/lounge_checkout', 'front\LoungeController@lounge_checkout')->name("lounge_checkout");

// Route::post('/checkBookingLounge', 'front\LoungeController@checkBookingLounge')->name("checkBookingLounge");

// Route::post('booking/payout_lounge', 'front\LoungeController@payout_lounge')->name("payout_lounge");

// Route::post('booking/payout_failed_lounge', 'front\LoungeController@payout_failed_lounge')->name("payout_failed_lounge");

// Route::get('/booking_lounge/thankyou/{id}', 'front\LoungeController@thankyou')->name("thankyou_lounge");

// Route::get('/about-us', 'front\HomeController@about_us')->name("about-us");

// Route::post('/searchresult_transfer', 'front\HomeController@getSearchResultTransfer')->name("searchresult_transfer");

// Route::post('/get_location_suggestion', 'front\TransferController@get_location_suggestion')->name("get_location_suggestion");

// Route::post('/get_location_suggestion_drop', 'front\TransferController@get_location_suggestion_drop')->name("get_location_suggestion_drop");

// Route::post('/booking_transfer', 'front\TransferController@addBookingFormTransfer')->name("addBookingFormTransfer");

// Route::post('/transfer_checkout', 'front\TransferController@transfer_checkout')->name("transfer_checkout");

// Route::post('/checkBookingTransfer', 'front\TransferController@checkBookingTransfer')->name("checkBookingTransfer");
// Route::post('booking/payout_failed_transfer', 'front\TransferController@payout_failed_transfer')->name("payout_failed_transfer");

// Route::post('booking/payout_transfer', 'front\TransferController@payout_transfer')->name("payout_transfer");

// Route::get('/airport/{slug}', 'front\HomeController@page')->name("page");

// Route::get('/airports', 'front\HomeController@airports')->name("airports");

// Route::get('/support', 'TicketsController@index')->name("support");

// Route::post('/store', 'TicketsController@store')->name("submit-ticket");

// Route::post('/submit-reply', 'TicketsController@submit_reply')->name("submit-reply");

// Route::post('/addNote', 'BackendticketController@addNote')->name("addNote");

// Route::post('/search-ticket', 'TicketsController@search_ticket')->name("search_ticket");

//Route::post('/booking-ticket', 'front\BookingController@booking_search')->name("booking_search");

// Route::get('/ticket/view/{id}', 'TicketsController@view')->name("view-ticket");

// Route::get('/manage-booking', 'front\BookingController@manage_booking')->name("manage_booking");

// Route::post('/booking-search', 'front\BookingController@booking_search')->name("booking_search");

// Route::post('/reSendEmailBooking', 'front\BookingController@reSendEmailBooking')->name("reSendEmailBooking");



// Route::get('/admin/export_subscriber_excel', 'SubscribersController@export_subscriber_excel')->name("export_subscriber_excel");

// Route::get('/admin/export_customer_excel', 'CustomerController@export_customer_excel')->name("export_customer_excel");



// Route::get('faqs', 'front\HomeController@faqs')->name("faqs");

// Route::get('faqs_pages', 'front\HomeController@faqs_pages')->name("faqs_pages");

// Route::get('parkingzone-reviews', 'front\HomeController@reviews')->name("reviews");

// Route::get('parkingzone-reviews2', 'front\HomeController@reviews2')->name("reviews2"); 

// Route::get('airport-guide', 'front\HomeController@airport_guide')->name("airport_guide");

// Route::get('airport-types', 'front\HomeController@airport_types')->name("airport_types");

// Route::post('/subscribe_user', 'front\HomeController@subscribe_user')->name("subscribe_user");

// Route::post('/subscribe_user_and_discount', 'front\HomeController@subscribe_user_and_discount')->name("subscribe_user_and_discount");

// Route::get('/sitemap', 'front\HomeController@sitemap')->name("sitemap");

// Route::get('/airport-parking', 'front\HomeController@airportsparking')->name("airportsparking");

// Route::get('/airport-transfer', 'front\HomeController@airporttransfer')->name("airporttransfer");

// Route::get('/lounges', 'front\HomeController@lounges')->name("lounges");

// Route::get('/feedback', 'front\HomeController@feedback')->name("feedback");

// Route::post('/feedback', 'front\HomeController@store')->name("submit-feedback");

// Route::get('/contact-us', 'front\HomeController@contact')->name("contact-us");
// Route::post('/contact-us-submit', 'front\HomeController@contactUsSubmit')->name("contact-us-submit");

// Route::get('/blogs', 'front\HomeController@blogs')->name("blogs");

// Route::get('blog/{slug}', 'front\HomeController@blog_detail')->name("blog_detail");

// Route::get('/privacy-policy', 'front\HomeController@privacy_policy')->name("privacy-policy");

//Route::get('/send', 'EmailController@send'); //for email test



// ********************************************************************************* Resource Routes *********************************************************************************


// Route::resource('/admin/airport','AirportController');

// Route::resource('/admin/company','CompanyController');

// Route::resource('/admin/agent','PartnersController');

// Route::resource('/admin/awards','AwardsController');

// Route::resource('/admin/company/plan','CompaniesProductPriceController');

// Route::resource('/admin/pages','PagesController');

// //Route::resource('/admin/reviews','ReviewsController');

// Route::resource('/admin/faqs','FaqsController');

// Route::resource('/admin/reviews','ReviewsController');

// Route::resource('/admin/subscribers','SubscribersController');

// Route::resource('/admin/customers','CustomerController');

// Route::resource('/admin/discounts','DiscountsController');

// Route::resource('/admin/emails','EmailTemplatesController');

// Auth::routes();


// Route::get('/home', 'HomeController@index')->name('home');

// Route::get('{page}', 'front\HomeController@static_page')->name("static_page");

// Route::get('/cron/incomplete', 'CronController@index')->name("cron_incomplete");

// Route::get('/cron/incompletesms', 'CronController@sendsms_incomplete')->name("cron_incomplete_sms");

// Route::get('/cron/mailchimp', 'CronController@sendmails_mailchimp')->name("mailchimp");
