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
use App\Http\Controllers\Admin\ProfileUpdateController;
use App\Http\Controllers\Admin\ResetPasswordController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\GlobalRankingController;
use App\Http\Controllers\TermsController;

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
})->name('login')->middleware(['removePublic','guest']);

Route::post('login',[LoginController::class, 'index'])->name('user.login');

Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');
Route::get('account/verify/{token}', [UsersController::class, 'verifyAccount'])->name('user.verify'); 
Route::get('accountVerified', function () {
    return view('emailVerify');
})->name('accountVerified')->middleware(['removePublic','guest']);

Route::prefix('admin')->middleware(['admin','removePublic'])->group(function () {

    Route::get('dashboard',[DashboardController::class, 'index'])->name('admin.dashboard');

    /* USERS */
    Route::resource('users', UsersController::class);
    Route::resource('parent', ParentController::class);
    Route::resource('children', ChildController::class);
    Route::post('saveChildrenFromPopup', [ChildController::class,'saveChildrenFromPopup'])->name('children.savechildren');

    Route::post('users/additional-fields', [CommonController::class,'additionalFieldsForUser'])->name('users.additional_fields');

    /* COMMON FOR CHANGES STATUS */
    Route::post('common/changestatus', [CommonController::class,'changeStatus'])->name('common.changestatus');

    Route::post('common/changePaymentStatus', [CommonController::class,'changePaymentStatus'])->name('common.changePaymentStatus');


    /* CATEGROY */
    Route::resource('category', CategoriesController::class);

    /* PROVINCE */
    Route::resource('province', ProvinceController::class);

    /* SCHOOL */
    Route::resource('school', SchoolController::class);

    /* SKILL */
    Route::resource('skill', SkillController::class);
    Route::post('/skill/reorder', [SkillController::class, 'reorder'])->name('skill.reorder');

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

    /* GLOBAL RANKINGS*/
    Route::get('globalranking/index', [GlobalRankingController::class,'index'])->name('globalranking.index');

    /* NOTIFICATION */
    Route::resource('notification', NotificationController::class);

    /* FEES */
    Route::resource('fees', FeesController::class);

    /* PROFILE UPDATE */
    Route::resource('profile_update', ProfileUpdateController::class);

    /* CONTACT US */
    Route::resource('contactus', ContactUsController::class);
    Route::get('/contactus/{contactus}', [ContactUsController::class, 'show'])->name('contactus.show');

    /* SKILL REVIEW */
    Route::resource('skill-review', SkillReviewController::class);
    Route::get('/skill-review/{skill_review}', [SkillReviewController::class, 'show'])->name('skill-review.show');
    Route::post('skill-review/update_status', [SkillReviewController::class,'updateOrderStatus'])->name('skill-review.update_status');

    Route::get('/payment/index', [PaymentController::class,'index'])->name('payment.index');

    /* SCHOOL */
    Route::resource('pages', PagesController::class);
});

Route::get('getSchoolByProvinceId/{id}', [SchoolController::class, 'getSchoolByProvinceId'])->name('school.getSchoolByProvinceId');

Route::get('terms', [TermsController::class, 'index'])->name('terms');
Route::get('policy', [TermsController::class, 'policy'])->name('policy');

/* PASSWORD RESET */
Route::get('password/reset/{token}', [ResetPasswordController::class,'showResetForm'])->name('show.reset.form');

Route::post('password/reset/update', [ResetPasswordController::class,'resetPassword'])->name('reset.password');

/*Payment*/
Route::get('/payment/success', [PaymentController::class,'paymentReturn'])->name('payment.return-url')->middleware(['removePublic']);

Route::get('/payment/cancel', [PaymentController::class,'paymentCancel'])->name('payment.cancel-url')->middleware(['removePublic']);

Route::post('/payment/notify', [PaymentController::class,'paymentNotify'])->name('payment.notify-url')->middleware(['removePublic']);