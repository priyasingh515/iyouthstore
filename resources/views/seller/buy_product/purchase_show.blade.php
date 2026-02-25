@extends('seller.layouts.app')

@section('panel_content')

<div class="aiz-titlebar mb-4">
    <h1 class="h3">Purchase Details</h1>
</div>

<div class="card">
    <div class="card-body">

        <h5>Order Code: {{ $order->code }}</h5>

        <p>
            Payment Status:
            <span class="badge badge-inline badge-info">
                {{ ucfirst($order->payment_status) }}
            </span>
        </p>

        <p>
            Delivery Status:
            <span class="badge badge-inline badge-warning">
                {{ ucfirst($order->delivery_status) }}
            </span>
        </p>

        <hr>

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>

                @foreach($order->orderDetails as $detail)

                <tr>

                    <td>
                        {{ $detail->product->getTranslation('name') }}
                    </td>

                    <td>
                        ₹ {{ number_format($detail->price, 2) }}
                    </td>

                    <td>
                        {{ $detail->quantity }}
                    </td>

                    <td>
                        ₹ {{ number_format($detail->price * $detail->quantity, 2) }}
                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

        <div class="text-right">
            <h5>Total: ₹ {{ number_format($order->grand_total, 2) }}</h5>
        </div>

    </div>
</div>

@endsection
