
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Plans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl w-full px-6 py-8 mx-auto bg-white rounded-lg shadow-lg">
            <h1 class="text-3xl font-semibold text-center text-gray-800 mb-8">Choose Your Subscription Plan</h1>
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <form id="payment-form" action="{{ route('subscribe') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @foreach($plans as $plan)
                            <label for="plan_{{ $plan->id }}" class="block p-6 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <input type="radio" name="plan_id" id="plan_{{ $plan->id }}" value="{{ $plan->stripe_id }}" required class="mr-2">
                                <div class="text-lg font-bold text-gray-700">{{ $plan->name }}</div>
                                <div class="text-gray-500">${{ number_format($plan->amount / 100, 2) }} / {{ $plan->interval }}</div>
                            </label>
                        @endforeach
                    </div>

                </div>

                <div class="mt-8">
                    <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                    <input type="text" name="customer_name" required class="mt-1 block w-full border border-gray-300 rounded-lg p-2" placeholder="Enter your name">
                </div>

                <div class="mt-8">
                    <label class="block text-sm font-medium text-gray-700">Customer Address</label>
                    <input type="text" name="customer_address" required class="mt-1 block w-full border border-gray-300 rounded-lg p-2" placeholder="Enter your address">
                </div>

                <div class="mt-8">
                    <label class="block text-sm font-medium text-gray-700">Card Information</label>
                    <div id="card-element" class="p-4 border border-gray-200 rounded-lg bg-white shadow-box"></div>
                    <div id="card-errors" role="alert" class="mt-2 text-red-500"></div>
                </div>

                <button type="submit" class="w-full py-3 mt-4 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none">
                    Subscribe Now
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const stripe = Stripe('{{ config('app.stripe_key') }}');
                const elements = stripe.elements();
                const cardElement = elements.create('card', {
                    style: {
                        base: {
                            fontSize: '16px',
                            color: '#32325d',
                            fontFamily: 'Arial, sans-serif',
                            '::placeholder': { color: '#aab7c4' },
                        },
                        invalid: { color: '#fa755a' }
                    }
                });
                cardElement.mount('#card-element');

                const form = document.getElementById('payment-form');
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    const { paymentMethod, error } = await stripe.createPaymentMethod({
                        type: 'card',
                        card: cardElement,
                    });

                    if (error) {
                        document.getElementById('card-errors').innerText = error.message;
                    } else {
                        const hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'payment_method');
                        hiddenInput.setAttribute('value', paymentMethod.id);
                        form.appendChild(hiddenInput);
                        form.submit();
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
