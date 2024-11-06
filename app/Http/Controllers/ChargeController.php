<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChargeController extends Controller
{
    public function showChargeForm()
    {
        return view('charge');
    }

    public function charge(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string',
        ]);

        $user = $request->user();
        
        try {
            // Convert amount to cents
            $amountInCents = $request->amount * 100;

            // Create a payment intent with a return URL
            $paymentIntent = $user->createSetupIntent([
                //'amount' => $amountInCents,
                //'currency' => 'usd',
                'payment_method' => $request->payment_method,
                'confirm' => true,
                'return_url' => route('checkout-success'), // specify your success route
            ]);
            // Redirect to the payment intent's next_action URL if necessary
            if (isset($paymentIntent->next_action)) {
                return redirect()->away($paymentIntent->next_action->redirect_to_url->url);
            }

            return redirect()->route('checkout-success')->with('success', 'Payment successful!');

        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->withErrors(['error' => 'Payment failed: ' . $e->getMessage()]);
        }
    }
}
