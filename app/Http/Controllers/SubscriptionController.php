<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Stripe\Exception\InvalidRequestException;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return view('subscriptions.index', compact('plans'));
    }

    public function subscribe(Request $request)
    {
        // Validate the request
        $request->validate([
            'plan_id' => 'required|string',
            'customer_name' => 'required|string|max:255',
            'customer_address' => 'required|string|max:255',
            'payment_method' => 'required|string',
        ]);

        // Get the currently authenticated user
        $user = auth()->user();

        try {
            // If this user is not yet a customer, create one
            if (!$user->stripe_id) {
                $user->createAsStripeCustomer([
                    'name' => $request->customer_name,
                    'address' => [
                        'line1' => $request->customer_address,
                    ],
                ]);
            }

            // Update the user's payment method
            $user->updateDefaultPaymentMethod($request->payment_method);

            // Subscribe the user to the plan
            $de = $user->newSubscription('main', $request->plan_id)->create($request->payment_method);
            return redirect()->route('checkout-success')->with('success', 'Subscription successful!');
        } catch (InvalidRequestException $e) {
            // Handle the error
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
