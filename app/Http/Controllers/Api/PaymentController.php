<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{

    use HttpResponses;

    public function createCheckoutSession(Request $request, Order $order)
    {

        try {

            Stripe::setApiKey(env('STRIPE_SECRET'));

//            $lineItems = [
//                [
//                    'price' => $order->offerPrice,
//                    'quantity' => 1,
//                ]
//            ];
//
//            $session = Session::create([
//                'payment_method_types' => ['card'],
//                'line_items' => $lineItems,
//                'mode' => 'payment',
//                'success_url' => sprintf("%s/payment/success?session_id={CHECKOUT_SESSION_ID}", env('APP_URL_FRONT')),
//                'cancel_url' => sprintf("%s/payment/error", env('APP_URL_FRONT')),
//            ]);

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $order->offerPrice * 100,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
            ]);

            return $this->success(['id' => $paymentIntent->id]);

        } catch (\Throwable $e) {

            return $this->error(message: $e->getMessage());

        }

    }
}
