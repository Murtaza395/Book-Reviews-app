<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Router;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/book/{id}',[HomeController::class,'detail'])->name('book.detail');
Route::post('/save-book-review',[HomeController::class,'saveReview'])->name('book.saveReview');

Route::group(['prefix'=>'account'],function(){
Route::group(['middleware'=> 'guest'],function(){
    Route::get('register',[AccountController::class,'register'])->name('account.register');
    Route::post('processregister',[AccountController::class,'ProcessRegister'])->name('account.ProcessRegister');
    Route::get('login',[AccountController::class,'login'])->name('account.login');
    Route::post('login',[AccountController::class,'authenticate'])->name('account.authenticate');
});
Route::group(['middleware'=> 'auth'],function(){
    Route::get('profile',[AccountController::class,'profile'])->name('account.profile');
    Route::get('logout',[AccountController::class,'logout'])->name('account.logout');
    Route::post('updateprofile',[AccountController::class,'updateprofile'])->name('account.updateprofile');


    Route::group(['middleware'=> 'check-admin'],function(){
    Route::get('books',[BookController::class,'index'])->name('books.index');
    Route::get('books/create',[BookController::class,'create'])->name('books.create');
    Route::post('books',[BookController::class,'store'])->name('books.store');
    Route::get('books/edit/{id}',[BookController::class,'edit'])->name('books.edit');
    Route::post('books/edit/{id}',[BookController::class,'update'])->name('books.update');
    Route::delete('books/delete/{id}',[BookController::class,'destroy'])->name('books.destroy');


    Route::get('reviews',[ReviewController::class,'index'])->name('account.reviews');
    Route::get('reviews/{id}/edit',[ReviewController::class,'edit'])->name('account.reviews.edit');
    Route::post('reviews/{id}/edit',[ReviewController::class,'update'])->name('account.reviews.update');
    Route::delete('reviews/{id}/delete',[ReviewController::class,'delete'])->name('account.reviews.delete');


    });
    Route::get('my-reviews',[AccountController::class,'myReviews'])->name('account.myReviews');
    Route::get('my-reviews/{id}',[AccountController::class,'editReview'])->name('account.myReviews.editReview');
    Route::post('my-reviews/{id}',[AccountController::class,'updateReview'])->name('account.myReviews.updateReview');
    Route::delete('my-reviews/{id}',[AccountController::class,'deleteReview'])->name('account.myReviews.deleteReview');
});
});
