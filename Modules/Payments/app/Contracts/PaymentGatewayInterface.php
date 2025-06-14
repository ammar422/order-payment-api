<?php

namespace Modules\Payments\Contracts;

use Modules\Payments\PaymentResult;


interface PaymentGatewayInterface
{
    public function pay(float $amount, string $currency, array $metadata = []): PaymentResult;

    public function handleCallback(array $data): PaymentResult;

}
