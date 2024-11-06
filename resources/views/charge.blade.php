<!-- resources/views/charge.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Make a One-Time Charge') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-6 py-8 bg-white rounded-lg shadow-lg">
            <h1 class="text-2xl font-semibold mb-8">Make a Payment</h1>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <form id="payment-form" action="{{ route('charge') }}" method="POST" class="space-y-6">
                @csrf

                <label class="block text-sm font-medium text-gray-700">Amount ($)</label>
                <input type="number" name="amount" required class="mt-1 block w-full border-gray-300 rounded-lg p-2" placeholder="Enter amount to charge">

                <label class="block text-sm font-medium text-gray-700 mt-4">Card Information</label>
                <div id="card-element" class="p-4 border border-gray-300 rounded-lg bg-white"></div>
                <div id="card-errors" role="alert" class="mt-2 text-red-500"></div>

                <button type="submit" class="w-full py-3 mt-6 font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Pay Now
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
                    style: { base: { fontSize: '16px', color: '#32325d' } }
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
