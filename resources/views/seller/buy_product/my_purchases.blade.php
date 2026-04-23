@extends('seller.layouts.app')

@section('panel_content')

    <div class="aiz-titlebar mt-2 mb-4">
        <h1 class="h3">My Purchases</h1>
    </div>

    <div class="card">
        <div class="card-body">

            @if ($orders->count() == 0)
                <div class="text-center p-5">
                    <h5>No purchases found</h5>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Order Code</th>
                                <th>Total</th>
                                <th>Payment Status</th>
                                <th>Delivery Status</th>
                                <th>Date</th>
                                <th width="100">Options</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->code }}</td>
                                    <td>₹ {{ number_format($order->grand_total, 2) }}</td>
                                    <td>
                                        <span class="badge badge-inline badge-info">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-inline badge-warning">
                                            {{ ucfirst($order->delivery_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ date('d-m-Y', $order->date) }}
                                    </td>
                                    <td>

                                        <a href="{{ route('seller.my-purchases.show', encrypt($order->id)) }}"
                                            class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="View">

                                            <i class="las la-eye"></i>

                                        </a>
                                        <a class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                            href="{{ route('invoice.download', $order->id) }}" title="Download Invoice">
                                            <i class="las la-download"></i>
                                        </a>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            @endif

        </div>
    </div>

@endsection
