<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeCheckout;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        // Set your secret key
        Stripe::setApiKey();

        // Create a Checkout Session
        $session = StripeCheckout::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Your Product Name',
                    ],
                    'unit_amount' => 2000, // Amount in cents (e.g., $20.00)
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success'),
            'cancel_url' => route('checkout.cancel'),
        ]);

        return response()->json(['id' => $session->id]);
    }

    public function success()
    {
        return view('checkout.success');
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }

    public function showPaymentForm()
	{
	    return view('checkout.form');
	}

	public function processPayment(Request $request)
	{
	    $request->validate([
	        'amount' => 'required|numeric|min:1',
	    ]);

	    $user = $request->user();

	    $payment = $user->pay(
	        $request->get('amount')
	    );
	}


}
