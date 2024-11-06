<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run()
    {
        Plan::create([
            'name' => 'Basic Plan',
            'stripe_id' => 'price_1QEs88SDbwETnB6R4S6biyY1', // Stripe Price ID
            'amount' => 1000, // $10.00
            'currency' => 'usd',
            'interval' => 'monthly',
        ]);

        Plan::create([
            'name' => 'Pro Plan',
            'stripe_id' => 'price_1QEs8xSDbwETnB6RZjnMOiim', // Stripe Price ID
            'amount' => 2000, // $20.00
            'currency' => 'usd',
            'interval' => 'monthly',
        ]);
    }
}
