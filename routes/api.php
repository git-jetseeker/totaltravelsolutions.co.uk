<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\ApiCusomterController;

Route::post('/receive-file', [ApiCusomterController::class, 'receiveFile']);
Route::post('/register', [ApiCusomterController::class, 'apiRegister'])->name("register");
Route::post('/login', [ApiCusomterController::class, 'apiLogin'])->name("login");
Route::post('/searchResults', [ApiCusomterController::class, 'searchResults'])->name("searchResults");
Route::post('/booking', [ApiCusomterController::class, 'booking'])->name("booking");
Route::post('/forgot-password', [ApiCusomterController::class, 'forgotPassword'])->name("forgot");
Route::post('/reset-password', [ApiCusomterController::class, 'resetPassword'])->name("reset");
Route::get('/app-images', [ApiCusomterController::class, 'appImage'])->name("appimage");
Route::get('/airports', [ApiCusomterController::class, 'airports'])->name("airports");
Route::get('/airportsTerminals', [ApiCusomterController::class, 'airportsTerminals'])->name("airportsTerminals");
Route::post('/discount-code',[ApiCusomterController::class,'PromoDiscount'])->name("discount");
Route::post('/bookinglist',[ApiCusomterController::class,'bookinglist'])->name("bookinglist");
