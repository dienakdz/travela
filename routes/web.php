<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\clients\HomeController;
use App\Http\Controllers\clients\AboutController;
use App\Http\Controllers\clients\ServicesController;
use App\Http\Controllers\clients\PackagesController;
use App\Http\Controllers\clients\DestinationController;
use App\Http\Controllers\clients\BookingController;
use App\Http\Controllers\clients\TravelGuidesController;
use App\Http\Controllers\clients\TestimonialController;
use App\Http\Controllers\clients\ContactController;

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
Route::get('/packages', [PackagesController::class, 'index'])->name('packages');
Route::get('/destination', [DestinationController::class, 'index'])->name('destination');
Route::get('/booking', [BookingController::class, 'index'])->name('booking');
Route::get('/travel-guides', [TravelGuidesController::class, 'index'])->name('team');
Route::get('/testimonial', [TestimonialController::class, 'index'])->name('testimonial');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');


