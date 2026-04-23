<?php

namespace App\Http\Controllers\Seller;

use App\Models\SellerPayments;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = SellerPayments::where('user_id', Auth::user()->id)->latest()->paginate(9);
        return view('seller.payment_history', compact('payments'));
    }

   
}
