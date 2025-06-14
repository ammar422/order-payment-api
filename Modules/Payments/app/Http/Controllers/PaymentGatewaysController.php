<?php

namespace Modules\Payments\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Orders\Models\Order;
use App\Http\Controllers\Controller;
use Modules\Payments\Models\Payment;
use Modules\Payments\PaymentGatewayResolver;

class PaymentGatewaysController extends Controller
{

    public  $order;
    public function __construct()
    {
        $this->order = $this->order(request('order_id'));
    }

    public function order($id)
    {
        return Order::whereStatus('confirmed')->findOrfail($id);
    }

    public function getGateway($segment)
    {
        $resolver = new PaymentGatewayResolver();
        $gateway = $resolver->resolve($segment);
        return $gateway;
    }

    public function pay($segment)
    {
        $result = $this->getGateway($segment)->pay(
            $this->order->total_price,
            'USD',
            [
                'order_id'     => $this->order->id,
            ]
        );
        $data = [
            'url' => $result->data['url'],
            'order_id' => $this->order->id,
            'reference_id' => $result->data['reference_id']
        ];
        return lynx()->message('created successfully')->data($data)->response();
    }

    public function handleCallback(Request $request, $gateway)
    {
        $paymentGateway = $this->getGateway($gateway);
        $request->merge([
            'token' => request('token')
        ]);
        $payment = Payment::find($request->payment_id);
        $request['payment_token'] =  $payment->payment_token;

        $result = $paymentGateway->handleCallback($request->all());
        $payment->status = $result->success ? 'successful' : 'pending';
        $payment->transaction_details = $result->data['transaction_json'];
        $payment->save();

        return lynx()->data([
            'status' => $result->success ? 'successful' : 'pending',
        ])->message($result->message)->response();
    }
}
