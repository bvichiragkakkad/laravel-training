<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ChargeController;
//use Laravel\Folio\Folio;
//use Laravel\Folio\Facades\Folio;
//use Laravel\Folio\Folio;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/checkout', function (Request $request) {
    $stripePriceId = 'price_1QDR0kFWXYzgSBzhycQnMh7B';
 
    $quantity = 1;
 
    return $request->user()->checkout([$stripePriceId => $quantity], [
        'success_url' => route('checkout-success'),
        'cancel_url' => route('checkout-cancel'),
    ]);
})->name('checkout');


	Route::get('/subscribe', [SubscriptionController::class, 'index'])->name('subscribe');
	Route::post('/subscribe', [SubscriptionController::class, 'subscribe']);

	 
	Route::view('/checkout/success', 'checkout.success')->name('checkout-success');
	Route::view('/checkout/cancel', 'checkout.cancel')->name('checkout-cancel');

	Route::get('/charge', [ChargeController::class, 'showChargeForm'])->name('charge.form');
	Route::post('/charge', [ChargeController::class, 'charge'])->name('charge');

	Route::get('/payment', [CheckoutController::class, 'showPaymentForm'])->name('payment.form');
	Route::post('/payment/process', [CheckoutController::class, 'processPayment'])->name('payment.process');

	// Automatically includes routes in the routes/folio directory
	// Route::group(['prefix' => ''], function () {
	//     Folio::routes();
	// });

});



Route::post('/stripe/webhook', [WebhookController::class, 'handleWebhook']);




require __DIR__.'/auth.php';
