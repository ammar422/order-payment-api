<?php

namespace Modules\Payments\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Payments\Models\Payment;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with('order')->whereHas('order', function ($q) {
            $q->where('user_id', auth('api')->id());
        });

        if ($request->has('order_id')) {
            $query->where('order_id', $request->order_id);
        }

        return lynx()->message('get successfully')->data([$query->paginate(10)])->response();
    }
}