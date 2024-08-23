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
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\ConsentMessageController;
use App\Http\Controllers\Admin\SponsorsController;
use App\Http\Controllers\Admin\EmailTemplatesController;
use App\Http\Controllers\Admin\CommonController;
use App\Http\Controllers\Admin\CmsPagesController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\SkillReviewController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\RankingController;
use App\Http\Controllers\Admin\FeesController;

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


Route::get('', function () {
    return view('login');
})->name('login')->middleware(['removePublic']);

Route::post('login',[LoginController::class, 'index'])->name('user.login');

Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->middleware(['removePublic'])->group(function () {

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

    /* SKILL */
    Route::resource('skill', SkillController::class);

    /* CONSENT MESSAGE */
    Route::resource('consent', ConsentMessageController::class);

     /* CMS PAGES */
    Route::resource('cms_page', CmsPagesController::class);

    /* SPONSORS */
    Route::resource('sponsors', SponsorsController::class);

    /* EMAIL TEMPLATES */
    Route::resource('email-templates', EmailTemplatesController::class);

    /* RANKINGS*/
    Route::get('ranking/index', [RankingController::class,'index'])->name('ranking.index');

    /* NOTIFICATION */
    Route::resource('notification', NotificationController::class);

    /* FEES */
    Route::resource('fees', FeesController::class);

    /* CONTACT US */
    Route::resource('contactus', ContactUsController::class);
    Route::get('/contactus/{contactus}', [ContactUsController::class, 'show'])->name('contactus.show');

    /* SKILL REVIEW */
    Route::resource('skill-review', SkillReviewController::class);
    Route::get('/skill-review/{skill_review}', [SkillReviewController::class, 'show'])->name('skill-review.show');
    Route::post('skill-review/update_status', [SkillReviewController::class,'updateOrderStatus'])->name('skill-review.update_status');
});

