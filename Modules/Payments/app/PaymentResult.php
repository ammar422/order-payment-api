<?php

namespace Modules\Payments;

class PaymentResult
{
    public function __construct(
        public bool $success,
        public string $message,
        public ?array $data = null,
    ) {}
}
