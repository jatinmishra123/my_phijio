<?php
namespace App\Http\Controllers;

use App\Models\OrderDetails;
use App\Models\PaymentKitOrder;
use App\Models\PlanOrderDetails;
use App\Models\PaymentPlanOrder;

class OrderContoller extends Controller
{
    //
    public function index()
    {
        $orderDetails = OrderDetails::with('user', 'kit')->orderBy('id', 'desc')->get();

        $paymentKitOrders = PaymentKitOrder::with('order')->get()->keyBy('order_id');

        $merged = $orderDetails->map(function ($order) use ($paymentKitOrders) {
            $orderData = $order->toArray();
            $orderData['payment'] = $paymentKitOrders[$order->id] ?? null;
            return $orderData;
        });

        // return $merged;
        return view('admin.order.index', ['merged' => $merged]);


    }

    public function planOrder()
    {
        // $data['plan_order_details'] = PlanOrderDetails::with('user', 'plan')->orderBy('id', 'desc')->get();

        $orderDetails = PlanOrderDetails::with('user', 'plan')->orderBy('id', 'desc')->get();

        $paymentPlanOrders = PaymentPlanOrder::with('order')->get()->keyBy('order_id');

        $merged = $orderDetails->map(function ($order) use ($paymentPlanOrders) {
            $orderData            = $order->toArray();
            $orderData['payment'] = $paymentPlanOrders[$order->id] ?? null;
            return $orderData;
        });

        // return $merged;
        return view('admin.order.planOrder', ['merged' => $merged]);

    }

}
