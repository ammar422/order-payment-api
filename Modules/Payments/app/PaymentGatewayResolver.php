<?php

namespace Modules\Payments;

use Modules\Payments\Contracts\PaymentGatewayInterface;
use Modules\Payments\Gateways\PayPalGateway;
use Modules\Payments\Gateways\StripeGateway;
use Illuminate\Http\Exceptions\HttpResponseException;

class PaymentGatewayResolver
{
    public function resolve(string|null $gatewayName): PaymentGatewayInterface
    {
        return match ($gatewayName) {
            'stripe'  => new StripeGateway(),
            'paypal'  => new PayPalGateway(),
            default   =>  throw new HttpResponseException(
                response()->json([
                    'error' => 'Unsupported gateway',
                    'gateway' => $gatewayName ?? 'Gateway Not found',
                    'status' => false,
                ], 400)
            ),
        };
    }
}
