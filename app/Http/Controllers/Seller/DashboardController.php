<?php

namespace App\Http\Controllers\Seller;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Auth;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $authUserId = auth()->user()->id;
        $data['this_month_pending_orders'] = OrderDetail::whereSellerId($authUserId)
                                    ->whereDeliveryStatus('pending')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
        $data['this_month_cancelled_orders'] = OrderDetail::whereSellerId($authUserId)
                                    ->whereDeliveryStatus('cancelled')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
        $data['this_month_on_the_way_orders'] = OrderDetail::whereSellerId($authUserId)
                                    ->whereDeliveryStatus('on_the_way')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
        $data['this_month_delivered_orders'] = OrderDetail::whereSellerId($authUserId)
                                    ->whereDeliveryStatus('delivered')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->count();
                                    
        $data['this_month_sold_amount'] = Order::where('seller_id', Auth::user()->id)
                                    ->wherePaymentStatus('paid')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->sum('grand_total');
        $data['previous_month_sold_amount'] = Order::where('seller_id', Auth::user()->id)
                                    ->wherePaymentStatus('paid')
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->whereMonth('created_at', (Carbon::now()->month-1))
                                    ->sum('grand_total');
        
        $data['products'] = filter_products(Product::where('user_id', Auth::user()->id)->orderBy('num_of_sale', 'desc'))->limit(12)->get();
        $data['last_7_days_sales'] = Order::where('created_at', '>=', Carbon::now()->subDays(7))
                                ->where('seller_id', '=', Auth::user()->id)
                                ->where('delivery_status', '=', 'delivered')
                                ->select(DB::raw("sum(grand_total) as total, DATE_FORMAT(created_at, '%d %b') as date"))
                                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
                                ->get()->pluck('total', 'date');  
        $data['total_order'] = Order::where('seller_id', $authUserId)->count();
        $data['total_placed_order'] = Order::where('seller_id', $authUserId)
                                ->where('delivery_status', '!=', 'cancelled')
                                ->count();
        $data['total_confirmed_order'] = Order::where('seller_id', $authUserId)
                                ->where('delivery_status', 'confirmed')
                                ->count();
        $data['total_picked_up_order'] = Order::where('seller_id', $authUserId)
                                ->where('delivery_status', 'picked_up')
                                ->count();
        $data['total_shipped_order'] = Order::where('seller_id', $authUserId)
                                ->where('delivery_status', 'on_the_way')
                                ->count();
        $data['total_cancelled_order'] = Order::where('seller_id', $authUserId)
                                ->where('delivery_status', 'cancelled')
                                ->count();
        $data['total_products'] = Product::where('user_id', $authUserId)->count();
        $data['top_selling_products'] = Product::select(
            'products.id',
            'products.name',
            'products.slug',
            'products.thumbnail_img',
            DB::raw('SUM(order_details.quantity) as total_quantity'),
            DB::raw('SUM(order_details.price * order_details.quantity) as total_sale')
        )
            ->join('order_details', 'order_details.product_id', '=', 'products.id')
            ->join('orders', 'orders.id', '=', 'order_details.order_id')
            ->where('orders.seller_id', $authUserId)
            ->where('orders.delivery_status', 'delivered')
            ->where('products.approved', 1)
            ->where('products.published', 1)
            ->groupBy('products.id', 'products.name', 'products.slug', 'products.thumbnail_img')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();
        $latestOrderIds = OrderDetail::query()
                                ->join('orders', 'orders.id', '=', 'order_details.order_id')
                                ->where('order_details.seller_id', $authUserId)
                                ->where(function ($query) {
                                    $query->whereNull('orders.order_from')
                                        ->orWhereIn('orders.order_from', ['web', 'app', 'pos']);
                                })
                                ->orderByDesc('order_details.created_at')
                                ->distinct()
                                ->limit(5)
                                ->pluck('order_details.order_id');

        $data['latest_orders'] = $latestOrderIds->isEmpty()
            ? collect()
            : Order::with('user')
                ->whereIn('id', $latestOrderIds)
                ->orderByRaw('FIELD(id, ' . $latestOrderIds->implode(',') . ')')
                ->get();

        return view('seller.dashboard', $data);
    }
}
