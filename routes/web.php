<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Front\FrontController;


Route::get('/clear', function() {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    return redirect()->back();
});

Route::get('/contributor', [App\Http\Controllers\Front\FrontController::class, 'contributor'])->name('front.contributor');
Route::controller(FrontController::class)->group(function () {


Route::get('/fact-file', 'factFile')->name('front.factFile');
Route::get('/media-literacy', 'mediaLiteracy')->name('front.mediaLiteracy');
Route::get('/methodology', 'methodology')->name('front.methodology');


Route::get('/load-more-news', 'getMoreNews')->name('front.load.more.news');
    Route::get('/', 'index')->name('front.index');
    Route::get('/about-us', 'aboutUs')->name('front.aboutUs');
    Route::get('/contact-us', 'contactUs')->name('front.contactUs');
    Route::get('/privacy-policy', 'privacyPolicy')->name('front.privacyPolicy');
    Route::get('/terms-condition', 'termsCondition')->name('front.termsCondition');
    Route::get('/archive', 'archive')->name('front.archive');
    Route::get('/team', 'team')->name('front.team');

    Route::post('/contact-us-post', 'contactUsPost')->name('front.contactUsPost');
    Route::get('/services', 'services')->name('front.services');
Route::post('/fact-check-submit-request', 'submitFactCheckRequest')->name('front.request.submit');
Route::get('/fact-check-search', 'searchFactCheck')->name('front.factCheck.search');
    Route::get('/news-category/{slug}', 'newsList')->name('front.category.news');
  
    Route::get('/news/{slug}', 'newsDetails')->name('front.news.details');
    Route::get('/single/post/{id}', 'newsDetailsOld')->name('news.detailsOld');
// ভিডিও গ্যালারি লিস্ট পেজ (সকল ভিডিও)
Route::get('/video-gallery', 'videoList')->name('front.video.gallery');
});

// Video News Detail Route
Route::get('/video-gallery/{slug}', [App\Http\Controllers\Front\FrontController::class, 'videoDetail'])->name('front.video.details');
//frontend part start 
Route::post('/contact-us-post', [App\Http\Controllers\Front\FrontController::class, 'contactUsPost'])->name('front.contactUsPost');
Route::get('/search', [FrontController::class, 'search'])->name('front.search');
   
Route::post('/comment-store', [FrontController::class, 'storeComment'])->name('front.comment.store');
Route::post('/reaction-store', [FrontController::class, 'storeReaction'])->name('front.reaction.store');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/payment/success', [FrontController::class, 'paymentSuccess'])->name('payment.success');
Route::post('/payment/fail', [FrontController::class, 'paymentFail'])->name('payment.fail');
Route::post('/payment/cancel', [FrontController::class, 'paymentCancel'])->name('payment.cancel');

Route::get('/latest-fact-checks', [FrontController::class, 'latestFactChecks'])->name('front.latest.news');




