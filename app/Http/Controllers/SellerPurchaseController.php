<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\SellerProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $payment_status = $request->payment_status;
        $delivery_status = $request->delivery_status;
        $sort_search = $request->search;
        $date = $request->date;

        $orders = Order::where('order_from', 'seller_panel')
            ->orderBy('id', 'desc');

        // Filter payment status
        if ($payment_status != null) {
            $orders->where('payment_status', $payment_status);
        }

        // Filter delivery status
        if ($delivery_status != null) {
            $orders->where('delivery_status', $delivery_status);
        }

        // Search by order code
        if ($sort_search != null) {
            $orders->where('code', 'like', '%' . $sort_search . '%');
        }

        // Filter by date range
        if ($date != null) {
            $dates = explode(" to ", $date);
            if (count($dates) == 2) {
                $orders->whereBetween('date', [
                    strtotime($dates[0]),
                    strtotime($dates[1])
                ]);
            }
        }

        $orders = $orders->paginate(15);


        return view('backend.sellers.purchase.index', compact(
            'orders',
            'payment_status',
            'delivery_status',
            'sort_search',
            'date'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */


    public function show($id)
    {
        $order = Order::findOrFail(decrypt($id));

        // mark viewed
        if ($order->viewed == 0) {
            $order->viewed = 1;
            $order->save();
        }

        return view('backend.sellers.purchase.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return back()->with('error', 'Order not found');
        }

        // delete order details first
        OrderDetail::where('order_id', $order->id)->delete();

        // delete order
        $order->delete();

        return redirect()
            ->route('seller-purchases.index')
            ->with('success', 'Order deleted successfully');
    }


    // public function updateDeliveryStatus(Request $request)
    // {
    //     $order = Order::find($request->order_id);

    //     if (!$order) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Order not found'
    //         ]);
    //     }
    //     $order->delivery_status = $request->status;

    //     if ($request->status == 'delivered') {
    //         $order->delivered_date = now();
    //     }

    //     $order->save();

    //     foreach ($order->orderDetails as $detail) {
    //         $detail->delivery_status = $request->status;
    //         $detail->save();
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Delivery status updated successfully'
    //     ]);
    // }

    public function updateDeliveryStatus(Request $request)
    {
        $order = Order::with('orderDetails')->find($request->order_id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ]);
        }

        $oldStatus = $order->delivery_status;

        DB::transaction(function () use ($request, $order, $oldStatus) {

            // Update order status
            $order->delivery_status = $request->status;

            if ($request->status == 'delivered') {
                $order->delivered_date = now();
            }

            $order->save();

            // Update orderDetails status
            foreach ($order->orderDetails as $detail) {
                $detail->delivery_status = $request->status;
                $detail->save();
            }

            /*
        |--------------------------------------------------------------------------
        | Add stock to seller_products when delivered
        |--------------------------------------------------------------------------
        */

            if ($request->status == 'delivered' && $oldStatus != 'delivered') {

                foreach ($order->orderDetails as $detail) {

                    $sellerProduct = SellerProduct::firstOrCreate(
                        [
                            'seller_id' => $order->user_id,
                            'product_id' => $detail->product_id,
                        ],
                        [
                            'stock' => 0
                        ]
                    );

                    // Add stock
                    $sellerProduct->increment('stock', $detail->quantity);
                }
            }
        });

        return response()->json([
            'status' => true,
            'message' => 'Delivery status updated and stock added successfully'
        ]);
    }

    public function updatePaymentStatus(Request $request)
    {
        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ]);
        }

        $order->payment_status = $request->status;
        $order->save();

        foreach ($order->orderDetails as $detail) {
            $detail->payment_status = $request->status;
            $detail->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Payment status updated successfully'
        ]);
    }
}
