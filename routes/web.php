<?php

use App\Livewire\AboutUsPage;
use App\Livewire\Auth\AccountPage;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\ContactPage;
use App\Livewire\HomePage;
use App\Livewire\ProductDetailPage;
use App\Livewire\ProductsPage;
use App\Livewire\CategoriesPage;
use App\Livewire\CartPage;
use App\Livewire\CheckoutPage;
use App\Livewire\SuccessPage;
use App\Livewire\CancelPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\MyOrderDetailPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', HomePage::class)->name('index');

Route::get('/categories', CategoriesPage::class)->name('product-categories');

Route::get('/products', ProductsPage::class)->name('all-products');

Route::get('/product/{slug}', ProductDetailPage::class)->name('product-details');

Route::get('/cart', CartPage::class)->name('cart-products');

Route::get('/checkout', CheckoutPage::class)->name('checkout');

Route::get('/my-orders', MyOrdersPage::class)->name('my-orders');

Route::get('/my-orders/{order}', MyOrderDetailPage::class)->name('my-order-details');

Route::get('/account', AccountPage::class)->middleware('auth')->name('account');

// Auth routes

Route::get('/login', LoginPage::class)->name('login');

Route::get('/register', RegisterPage::class)->name('register');

Route::get('/forgot-password', ForgotPasswordPage::class)->name('forgot-password');

Route::get('/reset-password', ResetPasswordPage::class)->name('reset-password');

// Logout route
Route::post('/logout', function() {
    Auth::logout();
    return redirect()->route('index');
})->middleware('auth')->name('logout');

Route::get('/success', SuccessPage::class)->name('success');

Route::get('/cancelled', CancelPage::class)->name('cancelled');

Route::get('/contact', ContactPage::class)->name('contact');
Route::get('/about-us', AboutUsPage::class)->name('about-us');

// Coupon Help Page
Route::get('/coupon-help', function() {
    return view('coupon-help');
})->name('coupon-help');

// Legacy Admin Routes - Commented out as we're now using Filament admin panel
// Route::prefix('admin')->middleware('auth')->group(function () {
//     Route::get('/coupons', App\Livewire\Admin\CouponManagement::class)->name('admin.coupons');
// });

// Payment Gateway Callback Routes
Route::prefix('payment')->group(function () {
    Route::get('/jazzcash/callback', [\App\Http\Controllers\PaymentController::class, 'jazzCashCallback']);
    Route::get('/easypaisa/callback', [\App\Http\Controllers\PaymentController::class, 'easyPaisaCallback']);
    Route::get('/bank/callback', [\App\Http\Controllers\PaymentController::class, 'bankCallback']);
});


// Route::get('/', function () {
//     return view('welcome');
// });
