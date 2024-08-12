<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\PayFastController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->middleware(['removePublic'])->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('childrenRegister', 'childrenRegister');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('contactUs', 'contactUs');    
});

Route::get('getSchool', [MainController::class, 'getActiveSchool']);
Route::get('getProvince', [MainController::class, 'getActiveProvince']); 
Route::get('getSponsors', [MainController::class, 'getSponsors']);

Route::middleware('auth:api')->group(function () {
    // Route::get('getCategories', [MainController::class, 'getActiveCategories']); 
    Route::get('getSkill', [MainController::class, 'getActiveSkill']);    
    Route::get('getSkillById/{id}', [MainController::class, 'getActiveSkillById']);  
      
    Route::get('getRankings', [MainController::class, 'getActiveRankings']);
    Route::get('getRankingById/{id}', [MainController::class, 'getActiveRankingsById']);      
    
    Route::post('submitScore', [MainController::class, 'submitScore']);
});

Route::post('/payfast/create-payment', [PayFastController::class, 'createPayment'])->name('payfast.create');
Route::post('/payfast/notify', [PayFastController::class, 'notify'])->name('payfast.notify');
Route::get('/payfast/return', [PayFastController::class, 'return'])->name('payfast.return');
Route::get('/payfast/cancel', [PayFastController::class, 'cancel'])->name('payfast.cancel');