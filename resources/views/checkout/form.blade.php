<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h2>One-Time Payment</h2>
    <form id="payment-form">
        <input type="hidden" name="amount" value="1000"> <!-- Amount in cents -->
        <div id="card-element"></div>
        <button type="submit" id="submit-button">Pay</button>
        <div id="card-errors" role="alert"></div>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            const stripe = Stripe('{{ config('app.stripe_key') }}');

            // Fetch Payment Intent Client Secret
            const { clientSecret } = await fetch("{{ route('payment.process') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-Token": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ amount: 1000 }) // Pass amount dynamically
            }).then(response => response.json());

            const elements = stripe.elements();
            const cardElement = elements.create('card');
            cardElement.mount('#card-element');

            const form = document.getElementById('payment-form');
            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: cardElement,
                    }
                });

                if (error) {
                    document.getElementById('card-errors').innerText = error.message;
                } else if (paymentIntent.status === 'succeeded') {
                    alert('Payment Successful!');
                }
            });
        });
    </script>
</body>
</html>
