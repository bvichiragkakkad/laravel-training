<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'payment_method' => 'required',
        ]);

        $user = Auth::user();
        
        // Create a new Stripe customer and subscribe
        $user->createOrGetStripeCustomer();
        $user->updateDefaultPaymentMethod($request->payment_method);
        
        // Subscribe the user to a plan
        $user->newSubscription('default', 'your_plan_id')->create($request->payment_method);

        return response()->json(['message' => 'Subscription successful!']);
    }
}
