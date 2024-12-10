<?php

use App\Http\Controllers\admin\AdminManagementController;
use App\Http\Controllers\admin\BookingManagementController;
use App\Http\Controllers\admin\ContactManagementController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\LoginAdminController;
use App\Http\Controllers\admin\ToursManagementController;
use App\Http\Controllers\admin\UserManagementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\clients\HomeController;
use App\Http\Controllers\clients\AboutController;
use App\Http\Controllers\clients\ServicesController;
use App\Http\Controllers\clients\ToursController;
use App\Http\Controllers\clients\TourDetailController;
use App\Http\Controllers\clients\DestinationController;
use App\Http\Controllers\clients\BookingController;
use App\Http\Controllers\clients\TravelGuidesController;
use App\Http\Controllers\clients\ContactController;
use App\Http\Controllers\clients\UserProfileController;
use App\Http\Controllers\clients\LoginController;
use App\Http\Controllers\clients\LoginGoogleController;
use App\Http\Controllers\clients\MyTourController;
use App\Http\Controllers\clients\PayPalController;
use App\Http\Controllers\clients\SearchController;
use App\Http\Controllers\clients\TourBookedController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/destination', [DestinationController::class, 'index'])->name('destination');
Route::get('/travel-guides', [TravelGuidesController::class, 'index'])->name('team');

//Handle Login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('user-login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('activate-account/{token}', [LoginController::class, 'activateAccount'])->name('activate.account');

//Login with google
Route::get('auth/google', [LoginGoogleController::class, 'redirectToGoogle'])->name('login-google');
Route::get('auth/google/callback', [LoginGoogleController::class, 'handleGoogleCallback']);

//Handle Get tours , filter Tours
Route::get('/tours', [ToursController::class, 'index'])->name('tours');
Route::get('/filter-tours', [ToursController::class, 'filterTours'])->name('filter-tours');

//Handle user profile
Route::get('/user-profile', [UserProfileController::class, 'index'])->name('user-profile')->middleware('checkLoginClient');
Route::post('/user-profile', [UserProfileController::class, 'update'])->name('update-user-profile');
Route::post('/change-password-profile', [UserProfileController::class, 'changePassword'])->name('change-password');
Route::post('/change-avatar-profile', [UserProfileController::class, 'changeAvatar'])->name('change-avatar');


//Hanlde checkout
Route::post('/booking/{id?}', [BookingController::class, 'index'])->name('booking')->middleware('checkLoginClient');
Route::post('/create-booking', [BookingController::class, 'createBooking'])->name('create-booking');
Route::get('/booking', [BookingController::class, 'handlePaymentMomoCallback'])->name('handlePaymentMomoCallback');

//Payment with paypal
Route::get('create-transaction', [PayPalController::class, 'createTransaction'])->name('createTransaction');
Route::get('process-transaction', [PayPalController::class, 'processTransaction'])->name('processTransaction');
Route::get('success-transaction', [PayPalController::class, 'successTransaction'])->name('successTransaction');
Route::get('cancel-transaction', [PayPalController::class, 'cancelTransaction'])->name('cancelTransaction');

//Payment with Momo
Route::post('/create-momo-payment', [BookingController::class, 'createMomoPayment'])->name('createMomoPayment');


//Tour booked
Route::get('/tour-booked', [TourBookedController::class, 'index'])->name('tour-booked')->middleware('checkLoginClient');
Route::post('/cancel-booking', [TourBookedController::class, 'cancelBooking'])->name('cancel-booking');

//My tour
Route::get('/my-tours', [MyTourController::class, 'index'])->name('my-tours')->middleware('checkLoginClient');

//get Tour detail and handle submit reviews
Route::get('/tour-detail/{id?}', [TourDetailController::class, 'index'])->name('tour-detail');
Route::post('/checkBooking', [BookingController::class, 'checkBooking'])->name('checkBooking')->middleware('checkLoginClient');
Route::post('/reviews', [TourDetailController::class, 'reviews'])->name('reviews')->middleware('checkLoginClient');

//Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/create-contact', [ContactController::class, 'createContact'])->name('create-contact');


//Search 
Route::get('/search', [SearchController::class, 'index'])->name(name: 'search');
Route::get('/search-voice-text', [SearchController::class, 'searchTours'])->name('search-voice-text');


//ADMIN
// Routes without middleware
Route::prefix('admin')->group(function () {
    Route::get('/login', [LoginAdminController::class, 'index'])->name('admin.login');
    Route::post('/login-account', [LoginAdminController::class, 'loginAdmin'])->name('admin.login-account');
    Route::get('/logout', [LoginAdminController::class, 'logout'])->name('admin.logout');

});

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    //Management admin
    Route::get('/admin', [AdminManagementController::class, 'index'])->name('admin.admin');
    Route::post('/update-admin', [AdminManagementController::class, 'updateAdmin'])->name('admin.update-admin');
    Route::post('/update-avatar', [AdminManagementController::class, 'updateAvatar'])->name('admin.update-avatar');

    //Handler management user
    Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users');
    Route::post('/active-user', [UserManagementController::class, 'activeUser'])->name('admin.active-user');
    Route::post('/status-user', [UserManagementController::class, 'changeStatus'])->name('admin.status-user');

    //Management Tours
    Route::get('/tours', [ToursManagementController::class, 'index'])->name('admin.tours');

    Route::get('/page-add-tours', [ToursManagementController::class, 'pageAddTours'])->name('admin.page-add-tours');
    Route::post('/add-tours', [ToursManagementController::class, 'addTours'])->name('admin.add-tours');
    Route::post('/add-images-tours', [ToursManagementController::class, 'addImagesTours'])->name('admin.add-images-tours');
    Route::post('/add-timeline', [ToursManagementController::class, 'addTimeline'])->name('admin.add-timeline');

    Route::post('/delete-tour', [ToursManagementController::class, 'deleteTour'])->name('admin.delete-tour');

    Route::get('/tour-edit', [ToursManagementController::class, 'getTourEdit'])->name('admin.tour-edit');
    Route::post('/edit-tour', [ToursManagementController::class, 'updateTour'])->name('admin.edit-tour');
    Route::post('/add-temp-images', [ToursManagementController::class, 'uploadTempImagesTours'])->name('admin.add-temp-images');

    //Management Booking
    Route::get('/booking', [BookingManagementController::class, 'index'])->name('admin.booking');
    Route::post('/confirm-booking', [BookingManagementController::class, 'confirmBooking'])->name('admin.confirm-booking');
    Route::get('/booking-detail/{id?}', [BookingManagementController::class, 'showDetail'])->name('admin.booking-detail');
    Route::post('/finish-booking', [BookingManagementController::class, 'finishBooking'])->name('admin.finish-booking');
    Route::post('/received-money', [BookingManagementController::class, 'receiviedMoney'])->name('admin.received');

    //Send mail pdf
    Route::post('/admin/send-pdf', [BookingManagementController::class, 'sendPdf'])->name('admin.send.pdf');

    //Contact management
    Route::get('/contact', [ContactManagementController::class, 'index'])->name('admin.contact');
    Route::post('/reply-contact', [ContactManagementController::class, 'replyContact'])->name('admin.reply-contact');

});