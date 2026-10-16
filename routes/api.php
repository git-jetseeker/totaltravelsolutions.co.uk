<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\ApiCusomterController;

Route::post('/receive-file', [ApiCusomterController::class, 'receiveFile']);
Route::post('/register', [ApiCusomterController::class, 'apiRegister'])->name("api.register");
Route::post('/login', [ApiCusomterController::class, 'apiLogin'])->name("api.login");
Route::post('/searchResults', [ApiCusomterController::class, 'searchResults'])->name("api.searchResults");
Route::post('/booking', [ApiCusomterController::class, 'booking'])->name("api.booking");
Route::post('/forgot-password', [ApiCusomterController::class, 'forgotPassword'])->name("api.forgot");
Route::post('/reset-password', [ApiCusomterController::class, 'resetPassword'])->name("api.reset");
Route::get('/app-images', [ApiCusomterController::class, 'appImage'])->name("api.appimage");
Route::get('/airports', [ApiCusomterController::class, 'airports'])->name("api.airports");
Route::get('/airportsTerminals', [ApiCusomterController::class, 'airportsTerminals'])->name("api.airportsTerminals");
Route::post('/discount-code',[ApiCusomterController::class,'PromoDiscount'])->name("api.discount");
Route::post('/bookinglist',[ApiCusomterController::class,'bookinglist'])->name("api.bookinglist");
