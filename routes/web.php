<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\clients\HomeController;
use App\Http\Controllers\clients\AboutController;
use App\Http\Controllers\clients\ServicesController;
use App\Http\Controllers\clients\ToursController;
use App\Http\Controllers\clients\TourDetailController;
use App\Http\Controllers\clients\DestinationController;
use App\Http\Controllers\clients\BookingController;
use App\Http\Controllers\clients\TravelGuidesController;
use App\Http\Controllers\clients\BlogDetailController;
use App\Http\Controllers\clients\ContactController;
use App\Http\Controllers\clients\BlogController;
use App\Http\Controllers\clients\LoginController;
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
Route::get('/services', [ServicesController::class, 'index'])->name('services');
Route::get('/tours', [ToursController::class, 'index'])->name('tours');
Route::get('/tour-detail', [TourDetailController::class, 'index'])->name('tour-detail');
Route::get('/destination', [DestinationController::class, 'index'])->name('destination');
Route::get('/booking', [BookingController::class, 'index'])->name('booking');
Route::get('/travel-guides', [TravelGuidesController::class, 'index'])->name('team');
Route::get('/blogs', [BlogController::class, 'index'])->name('blogs');
Route::get('/blog-detail', [BlogDetailController::class, 'index'])->name('blog-detail');
Route::get('/contact', action: [ContactController::class, 'index'])->name('contact');
Route::get('/login', action: [LoginController::class, 'index'])->name('login');
