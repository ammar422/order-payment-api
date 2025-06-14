<?php

namespace Modules\Payments\Gateways;

use Modules\Payments\PaymentResult;
use Modules\Payments\Models\Payment;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\Payments\Contracts\PaymentGatewayInterface;

class PayPalGateway implements PaymentGatewayInterface
{
    private PayPalClient $paypalClient;

    public function __construct()
    {
        $this->paypalClient = new PayPalClient();
        $this->paypalClient->setApiCredentials(
            [
                'mode'           => config('paypal.mode'),
                'payment_action' => config('paypal.payment_action'),
                'currency'       => config('paypal.currency'),
                'currency'       => "USD",
                'notify_url'     => config('paypal.notify_url'),
                'locale'         => config('paypal.locale'),
                'validate_ssl'   => config('paypal.validate_ssl'),
                'sandbox' => [
                    'client_id'     =>  config('paypal.sandbox.client_id'),
                    'client_secret' => config('paypal.sandbox.client_secret'),
                ],
                'live' => [
                    'client_id'     =>  config('paypal.live.client_id'),
                    'client_secret' =>  config('paypal.live.client_secret'),
                ],
            ]
        );
        $this->paypalClient->getAccessToken();
    }

    public function pay(float $amount, string $currency, array $metadata = []): PaymentResult
    {
        $amount = $amount > 999 ? throw new HttpResponseException(response()->json('PayPal limit maximum exceeded', 400)) : $amount;
        $order_id = $metadata['order_id'];
        $description = $metadata['description'] ?? '';
        $payment = Payment::create([
            'order_id' => $order_id,
            'status' => 'pending',
            'gatway' => 'paypal',
        ]);
        $response = $this->paypalClient->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $order_id,
                    'amount' => [
                        'currency_code' => $currency,
                        'value' => number_format($amount, 2)
                    ],
                    'description' => $description
                ]
            ],
            'application_context' => [
                'cancel_url' => url("api/v1/payment/callback/paypal?order_id={$order_id}&payment_id={$payment->id}"),
                'return_url' => url("api/v1/payment/callback/paypal?order_id={$order_id}&payment_id={$payment->id}")
            ]
        ]);
        $payment->payment_url = $response['links'][1]['href'] ?? null;
        $payment->transaction_details = json_encode($response);
        $payment->payment_token = $response['id'];
        $payment->save();
        return new PaymentResult(true, __('paymentgateways::main.redirect_to_paypal'), [
            'url' => $response['links'][1]['href'] ?? null,
            'transaction_json' => json_encode($response),
            'reference_id' => $response['id']
        ]);
    }


    public function handleCallback(array $data): PaymentResult
    {
        $token = $data['token'] ?? null;
        $this->paypalClient->showOrderDetails($token);
        $response = $this->paypalClient->capturePaymentOrder($token);
        $isCompleted = $response['status'] === 'COMPLETED';
        return new PaymentResult(
            $isCompleted,
            $isCompleted ? 'paypal success' :  'paypal pending' . ($response['message'] ?? 'Unknown error'),
            [
                'order_id' => $data['order_id'] ?? null,
                'transaction_json' => json_encode($response),
                'reference_id' => $response['id'] ?? null,
            ]
        );
    }
}
