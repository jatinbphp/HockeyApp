<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\ParentController;
use App\Http\Controllers\Admin\ChildController;
use App\Http\Controllers\Admin\ProvinceController;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\CommonController;

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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('login',[LoginController::class, 'index'])->name('user.login');

Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');

Route::get('dashboard',[DashboardController::class, 'index'])->name('admin.dashboard');

/* USERS */
Route::resource('users', UsersController::class);
Route::resource('parent', ParentController::class);
Route::resource('children', ChildController::class);

Route::post('users/additional-fields', [CommonController::class,'additionalFieldsForUser'])->name('users.additional_fields');

/* COMMON FOR CHANGES STATUS */
Route::post('common/changestatus', [CommonController::class,'changeStatus'])->name('common.changestatus');


/* CATEGROY */
Route::resource('category', CategoriesController::class);

/* PROVINCE */
Route::resource('province', ProvinceController::class);

/* SCHOOL */
Route::resource('school', SchoolController::class);


