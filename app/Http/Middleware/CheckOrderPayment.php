<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckOrderPayment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $order = $request->order;
        if (! $order->payments()->exists() || $order->payments->every(fn($payment) => $payment->status == 'pending'))
            return $next($request);
        return lynx()->message('Cannot update or delete order with existing payments')->status(400)->response();
    }
}
