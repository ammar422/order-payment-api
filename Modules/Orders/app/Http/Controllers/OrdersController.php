<?php

namespace Modules\Orders\Http\Controllers;

use Modules\Orders\Models\Order;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Orders\Transformers\OrderdResource;
use Modules\Orders\Http\Requests\StoreOrderdRequest;
use Modules\Orders\Http\Requests\UpdateOrderRequest;

class OrdersController extends Controller
{
    public function index()
    {
        $query = Order::with('items')->where('user_id', auth('api')->id());

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $data =  $query->paginate(10);
        return lynx()->message('data get successfully')->data(new OrderdResource($data))->response();
    }

    public function store(StoreOrderdRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $total = collect($request['items'])->sum(fn($item) => $item['price'] * $item['quantity']);
            $order = Order::create([
                'user_id' => auth('api')->id(),
                'status' => 'pending',
                'total_price' => $total,
            ]);
            foreach ($request['items'] as $item) {
                $order->items()->create($item);
            }

            return lynx()->message('stored successfully')->data([
                'id' => $order->id
            ])->response();
        });
    }

    public function show($id)
    {
        $order = Order::with('items')->where('user_id', auth('api')->id())->findOrFail($id);
        return lynx()->data(new OrderdResource($order))->response();
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        return DB::transaction(function () use ($order, $request) {
            $order->items()->delete();
            $total = collect($request['items'])->sum(fn($item) => $item['price'] * $item['quantity']);

            foreach ($request['items'] as $item) {
                $order->items()->create($item);
            }
            $order->update(['total_price' => $total]);

            return lynx()->message('updated successfully')->data([
                'id' => $order->id
            ])->response();
        });
    }

    public function destroy(Order $order)
    {
        return DB::transaction(function () use ($order) {
            $order->items()->delete();
            $order->delete();

            return lynx()->message('deleted successfully')->data([
                'id' => $order->id
            ])->response();
        });
    }

    public function confirm(Order $order)
    {
        if ($order->status !== 'pending') {
            return lynx()->message('Only pending orders can be confirmed')->status(400)->response();
        }

        $order->update(['status' => 'confirmed']);
        return lynx()->message('Order confirmed successfully')->response();
    }
}
