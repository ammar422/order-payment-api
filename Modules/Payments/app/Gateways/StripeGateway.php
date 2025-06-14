<?php

namespace Modules\Payments\Gateways;

use Stripe\Services\StripeService;
use Modules\Payments\PaymentResult;
use Modules\Payments\Models\Payment;
use Modules\Payments\Contracts\PaymentGatewayInterface;

class StripeGateway implements PaymentGatewayInterface
{
    public $stripe;
    public function __construct()
    {
        $stripeService = app(StripeService::class);
        $this->stripe = $stripeService;
    }

    public function pay(float $amount, string $currency, array $metadata = []): PaymentResult
    {
        $order_id = $metadata['order_id'];
        $payment = Payment::create([
            'order_id' => $order_id,
            'status' => 'pending',
            'gatway' => 'stripe',
        ]);
        $stripe =  $this->stripe->create_checkout_session([
            'currency' => env('STRIP_CURRENCY', 'USD'),
            'amount' => intval(round($amount * 100)),
            'product_name' => 'prduct_name',
            'ref_id' => $metadata['order_id'],
            'success_url' => url("api/v1/payment/callback/stripe?order_id={$order_id}&payment_id={$payment->id}"),
            'cancel_url' => url("api/v1/payment/callback/stripe?order_id={$order_id}&payment_id={$payment->id}"),
        ]);
        $payment->payment_url = $stripe['url'];
        $payment->transaction_details = json_encode($stripe);
        $payment->payment_token = $stripe['id'];
        $payment->save();
        return new PaymentResult(true, __('paymentgateways::main.redirect_to_stripe'), [
            'url' => $stripe['url'],
            'transaction_json' => json_encode($stripe),
            'reference_id' => $stripe['id']
        ]);
    }

    public function handleCallback(array $data): PaymentResult
    {
  
        $stripe = $this->stripe->get_checkout_session_status($data['payment_token']);

        $status = $stripe['payment_status'] == 'paid';
        $message = $status ? 'payment_verified' : 'process transaction';

        return new PaymentResult($status, $message, [
            'order_id' => $data['order_id'],
            'transaction_json' => json_encode($stripe),
            'reference_id' => $stripe['id'] ?? $data['reference_id']
        ]);
    }
}
