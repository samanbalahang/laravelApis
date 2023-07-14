<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TheTestController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostCommentsController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\PreRegisterController;
use App\Http\Controllers\PreRegisterColleagueController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\CollectionCommentController;
use App\Http\Controllers\CollectionCatController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GalleryCatController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\TuitionController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\FactorController;
use App\Http\Controllers\TiketsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\extrassController;
use App\Http\Controllers\ExtraClassController;
use App\Http\Controllers\TuterController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ThePublicController;
use App\Http\Controllers\PaymentController;

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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// برای تست
Route::resource('/test',                    TheTestController::class);
// صفحه نخست
Route::get('/home-screen',                  [thepublicController::class,"index"]);
// برای خبر
Route::get('/news',                         [PostController::class,"index"]);
// برای خبر
Route::post('/news',                        [PostController::class,"store"]);
// مقالات
Route::get('/article',                      [PostController::class,"index"]);
// مقالات
Route::post('/article',                     [PostController::class,"store"]);
// //  انواع پست
Route::get('/post',                         [PostController::class,"index"]);
// ثبت انواع پست
Route::post('/post',                        [PostController::class,"store"]);
// Route::resource('/post',                    PostController::class);
// ویرایش پست
Route::post('/post/{url}',                  [PostController::class,"update"]);
// ارسال نظر برای پست
Route::resource('/post-comments',           PostCommentsController::class);
// دریافت تلفن از کاربر
Route::post('/user/sign-up',                [UserProfileController::class,"getPhone"])->name('user.getPhone');
// دریافت کد چهار رقمی از کاربر  
Route::post('/user/send-digit',             [UserProfileController::class,"getDigit"])->name('user.getDigit');
// لاگین
Route::post('/user/login',                  [UserProfileController::class,"login"])->name('user.login');
//درج پروفایل
Route::post('/userprofile',                 [UserProfileController::class,"store"])->name('user.setprofile');
// فراموشی رمز عبور
Route::post('/user/forgot-password',        [UserProfileController::class,"forgotPassword"])->name('user.forgotPassword');
// دریافت همه اخبار
Route::get('/news',                         [PostController::class,"index"])->name('post.news');
// دریافت همه مقالات
Route::get('/article',                      [PostController::class,"index"])->name('post.article');
// فرم پیش ثبت نام
Route::resource('/preRegister',             PreRegisterController::class);
// فرم پیش ثبت نام کارمندان
Route::resource('/preRegcolleag',           PreRegisterColleagueController::class);
// مدیا ها
Route::resource('/media',                   MediaController::class);
// مجموعه ها
Route::get('/collection',                   [CollectionController::class,"index"]);
// مجموعه ها
Route::post('/collection',                  [CollectionController::class,"store"]);
// یک مجموعه
Route::get('/collection/{url}',             [CollectionController::class,"show"]);
//کامنت های یک مجموعه
Route::post('/collection-comment',          [CollectionCommentController::class,"store"]);
// دسته بندی مجموعه ها
Route::resource('/collection-cat',          CollectionCatController::class);
// گالری
Route::resource('/gallery',                 GalleryController::class);
// گالری
Route::resource('/gallery-cat',             GalleryCatController::class);
// گالری
Route::post('/gallery_cat',                 [GalleryCatController::class,"store"]);
// دریافت تمام عکس های یک گالری
Route::get('/gallery_cat_media/{id}',       [GalleryCatController::class,"show"]);
//ثبت عکس برای دسته بندی
Route::post('/gallery_cat_media',           [GalleryCatController::class,"media"]);
// کلاس ها
Route::resource('/class',                   ClassesController::class);
// شهریه
Route::resource('/tuition',                 TuitionController::class);
// رسید
Route::resource('/bill',                    BillController::class);
// سبد خرید
Route::resource('/cart',                    CartController::class);
//  سبد خرید یک کاربر خاص
Route::get('/cart-user/{id}',              [CartController::class,"cartUser"]);
// فاکتور
Route::resource('/factor',                  FactorController::class);
// تیکت
Route::resource('/tiket',                   TiketsController::class);
// نقش کاربری
Route::resource('/role',                    RoleController::class);
// فوق برنامه ها
Route::resource('/extras',                  extrassController::class);
//کلاس های فوق برنامه
Route::resource('/extras-class',            ExtraClassController::class);
// دریافت یک خبر خاص
Route::get('/news/{uri?}',                  [PostController::class,"postUri"])->name('post.uri');
// دریافت یک مقاله خاص
Route::get('/article/{uri?}',               [PostController::class,"postUri"])->name('post.uri');
// ارسال اخبار و پست در یک جیسون
Route::get('/news-article',                 [PostController::class,"newsArticle"]);
// معلم ها
Route::resource('/tutor',                   TuterController::class);
//سوالات متداول
Route::resource('/faq',                     FaqController::class);
//سوالات قوانین و مقررات
Route::resource('/terms-conditions',        PostController::class);
//درباره ما
Route::resource('/about-us',                PostController::class);
//تماس با ما
Route::resource('/contact-us',              PostController::class);
//ویرایش کلاس
Route::resource('/class}',                  ClassesController::class);
//ویرایش کلاس
Route::resource('/collection',               CollectionController::class);
// ارسال درخواست برای پرداخت
Route::post('/payment',                     [PaymentController::class,"index"]);
Route::get('/payment',                      [PaymentController::class,"getPaymentResult"]);
Route::post('/payment-result',              [PaymentController::class,"result"]);
Route::post('/payment-verify',              [PaymentController::class,"verify"]);






Route::group(['prefix' => 'admin-panel'],function(){
});
// صفحه نخست ارسال داده ها
Route::post('/home-screen',                  [thepublicController::class,"store"]);
// ارسال صفحه قوانین و مقررات
Route::post('/terms-conditions',             [PostController::class,"store"]);
// ارسال صفحه درباره ما
Route::post('/about-us',                     [PostController::class,"store"]);
// ارسال صفحه تماس با ما
Route::post('/contact-us',                   [PostController::class,"store"]);
// ارسال پست
Route::delete('/post/{url}',                 [PostController::class,"destroy"]);
// ارسال سوالات متداول
Route::post('/faq',                          [PostController::class,"store"]);
//حذف کلاس
Route::delete('/class/{url}',                [ClassesController::class,"destroy"]);
// حذف گالری
Route::delete('/gallery_cat/{id}',           [GalleryCatController::class,"destroy"]);
// حذف مدیا از گالی-ری
Route::delete('/gallery_cat_media/{id}',     [GalleryCatController::class,"destroymedia"]);






// انواع پست
Route::get('/{uri?}',                       [PostController::class,"postUri"])->name('post.uri');

