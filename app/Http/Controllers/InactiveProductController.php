<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class InactiveProductController extends Controller
{

    public function index(Request $request)
    {
        $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
        ]);

        // Default range: last 2 months to today
        $fromDate = $request->input('from_date', now()->subMonths(2)->toDateString());
        $toDate = $request->input('to_date', now()->toDateString());

        if ($fromDate > $toDate) {
            [$fromDate, $toDate] = [$toDate, $fromDate];
        }

        $sellers = DB::select("
        SELECT 
            sp.seller_id,
            u.name AS seller_name,
            s.shop_id,
            COUNT(DISTINCT sp.product_id) AS unsold_products_count

        FROM seller_products sp

        JOIN users u 
            ON u.id = sp.seller_id

        JOIN products p
            ON p.id = sp.product_id

        JOIN shops s
            ON s.user_id = sp.seller_id

        LEFT JOIN
        (
            SELECT 
                seller_id,
                product_id,
                MAX(created_at) AS last_sold_date
            FROM order_details
            WHERE DATE(created_at) <= ?
            GROUP BY seller_id, product_id
        ) AS last_sales
            ON last_sales.seller_id = sp.seller_id
            AND last_sales.product_id = sp.product_id

        WHERE
            (
                last_sales.last_sold_date IS NULL
                AND DATE(sp.created_at) <= ?
            )
            OR
            (
                DATE(last_sales.last_sold_date) < ?
            )

        GROUP BY
            sp.seller_id,
            u.name,
            s.shop_id

        ORDER BY u.name
        ", [$toDate, $toDate, $fromDate]);

        return view('backend.inactive_products.index', compact('sellers', 'fromDate', 'toDate'));
    }


    public function show(Request $request, $sellerId)
    {
        $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
        ]);

        // Keep same range behavior as index
        $fromDate = $request->input('from_date', now()->subMonths(2)->toDateString());
        $toDate = $request->input('to_date', now()->toDateString());

        if ($fromDate > $toDate) {
            [$fromDate, $toDate] = [$toDate, $fromDate];
        }

        $products = DB::select("
        SELECT 
            p.id,
            p.name,
            sp.created_at AS assigned_date,
            last_sales.last_sold_date

        FROM seller_products sp

        JOIN products p 
            ON p.id = sp.product_id

        LEFT JOIN
        (
            SELECT 
                seller_id,
                product_id,
                MAX(created_at) AS last_sold_date
            FROM order_details
            WHERE DATE(created_at) <= ?
            GROUP BY seller_id, product_id
        ) AS last_sales
            ON last_sales.product_id = sp.product_id
            AND last_sales.seller_id = sp.seller_id

        WHERE sp.seller_id = ?

        AND
        (
            (last_sales.last_sold_date IS NULL AND DATE(sp.created_at) <= ?)
            OR DATE(last_sales.last_sold_date) < ?
        )

        ORDER BY sp.created_at ASC
        ", [$toDate, $sellerId, $toDate, $fromDate]);


        $seller = DB::selectOne("
        SELECT u.name, s.shop_id
        FROM users u
        JOIN shops s ON s.user_id = u.id
        WHERE u.id = ?
        ", [$sellerId]);


        return view('backend.inactive_products.show', compact(
            'products',
            'seller',
            'fromDate',
            'toDate'
        ));
    }
}
