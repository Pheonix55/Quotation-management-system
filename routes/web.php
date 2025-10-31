<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TermsController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'loginPage'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('loginPost');
Route::get('/register', [AuthController::class, 'registerPage'])->name('registerView');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/forget-password', [AuthController::class, 'forgetPassword'])->name('forget-password');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');



/////////////////////////////////////////////////////
Route::get('/get-a-quote', [DashboardController::class, 'getQuote'])->name('quote')->middleware(IsAdmin::class);

Route::post('/get-a-quote/store', [DashboardController::class, 'storeQuoteStep1'])->name('quote.store')->middleware(IsAdmin::class);

Route::get('/quotation/{id}/add-products', [DashboardController::class, 'addProducts'])
    ->name('quotation.addProducts')->middleware(IsAdmin::class);


Route::post('/quotation/{id}/save/products', [DashboardController::class, 'saveQuotationProducts'])
    ->name('quotation.saveProducts')->middleware(IsAdmin::class);
Route::get('/quotation/{id}/completePage', [DashboardController::class, 'completeQuotationView'])
    ->name('quotation.completeView')->middleware(IsAdmin::class);

Route::post('/quotation/{id}/complete', [DashboardController::class, 'completeQuotation'])
    ->name('quotation.complete')->middleware(IsAdmin::class);
Route::get('/quotations/{id}/add-terms', [DashboardController::class, 'addTerms'])->name('quotations.addTerms')->middleware(IsAdmin::class);

Route::post('/quotations/{id}/store-terms', [DashboardController::class, 'storeTerms'])->name('quotations.storeTerms')->middleware(IsAdmin::class);

Route::get('/quotations/{id}/show', [DashboardController::class, 'show'])->name('quotations.show')->middleware(IsAdmin::class);

Route::get('/quotations/{quotation}/download-pdf', [DashboardController::class, 'downloadPdf'])
    ->name('quotations.download-pdf')->middleware(IsAdmin::class);

Route::get('/quotations/{quotation}/view-pdf', [DashboardController::class, 'viewPdf'])
    ->name('quotations.view-pdf')->middleware(IsAdmin::class);


/////////////////////////////////////////////////////////////////////



Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/product/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/delete', [ProductController::class, 'destroy'])->name('product.destroy');

    Route::get('/search-products', [ProductController::class, 'search'])->name('products.search');

    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/customer/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('/customer/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('/customer/delete', [CustomerController::class, 'destroy'])->name('customer.destroy');


    Route::get('/terms', [TermsController::class, 'index'])->name('terms.index');
    Route::post('/terms/store', [TermsController::class, 'store'])->name('terms.store');
    Route::get('/terms/edit/{id}', [TermsController::class, 'edit'])->name('terms.edit');
    Route::get('/terms/create', [TermsController::class, 'create'])->name('terms.create');
    Route::put('/terms/update/{id}', [TermsController::class, 'update'])->name('terms.update');
    Route::delete('/terms/delete', [TermsController::class, 'destroy'])->name('terms.destroy');


    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::get('/category/create', [CategoryController::class, 'create'])->name('category.create');
    Route::put('/category/update/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/delete', [CategoryController::class, 'destroy'])->name('category.destroy');
});
