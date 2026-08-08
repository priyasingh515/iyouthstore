@extends('seller.layouts.app')

@section('panel_content')

<div class="aiz-titlebar mb-4">
    <h1 class="h3">Purchase Details</h1>
</div>

<div class="card shadow-sm">

    <div class="card-body">

        {{-- ORDER INFO --}}
        <div class="row">

            <div class="col-md-6">

                <div class="mb-4">
                    <small class="text-muted d-block">
                        Order Code
                    </small>

                    <h4 class="fw-700 mb-0">
                        #{{ $order->code }}
                    </h4>
                </div>

                <div class="mb-4">
                    <small class="text-muted d-block">
                        Order Date
                    </small>

                    <strong>
                        {{ date('d M Y, h:i A', $order->date) }}
                    </strong>
                </div>

                <div>
                    <small class="text-muted d-block">
                        Payment Method
                    </small>

                    <strong>
                        {{ ucfirst(str_replace('_',' ',$order->payment_type)) }}
                    </strong>
                </div>

            </div>

            <div class="col-md-6">

                <div class="row">

                    <div class="col-md-6">

                        <div class="border rounded p-4 text-center">

                            <small class="text-muted d-block mb-2">
                                Payment Status
                            </small>

                            @if($order->payment_status == 'paid')
                                <span class="badge badge-inline badge-success">
                                    Paid
                                </span>
                            @else
                                <span class="badge badge-inline badge-danger">
                                    Unpaid
                                </span>
                            @endif

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="border rounded p-4 text-center">

                            <small class="text-muted d-block mb-2">
                                Delivery Status
                            </small>

                            @if($order->delivery_status=='pending')
                                <span class="badge badge-inline badge-secondary">Pending</span>
                            @elseif($order->delivery_status=='confirmed')
                                <span class="badge badge-inline badge-primary">Confirmed</span>
                            @elseif($order->delivery_status=='on_the_way')
                                <span class="badge badge-inline badge-warning">On The Way</span>
                            @elseif($order->delivery_status=='delivered')
                                <span class="badge badge-inline badge-success">Delivered</span>
                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- PAYMENT INFO --}}
        @if($payment)

        <hr>

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h5 class="mb-0">
                Payment Information
            </h5>

            <button type="button"
                    class="btn btn-primary btn-sm"
                    data-toggle="modal"
                    data-target="#paymentProofModal">

                <i class="las la-eye"></i>
                View Payment Details

            </button>

        </div>

        <div class="border rounded p-3 mb-4">

            <div class="row">

                <div class="col-md-4">
                    <small class="text-muted d-block">
                        UTR Number
                    </small>

                    <strong>
                        {{ $payment->utr }}
                    </strong>
                </div>

                <div class="col-md-4">
                    <small class="text-muted d-block">
                        Payment Date
                    </small>

                    <strong>
                        {{ date('d M Y', strtotime($payment->payment_date)) }}
                    </strong>
                </div>

                <div class="col-md-4">

                    <small class="text-muted d-block">
                        Verification Status
                    </small>

                    @if($payment->status=='approved')
                        <span class="badge badge-inline badge-success">
                            Approved
                        </span>
                    @elseif($payment->status=='rejected')
                        <span class="badge badge-inline badge-danger">
                            Rejected
                        </span>
                    @else
                        <span class="badge badge-inline badge-warning">
                            Pending
                        </span>
                    @endif

                </div>

            </div>

        </div>

        @endif

        {{-- PRODUCTS --}}
        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="thead-light">

                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Unit Price</th>
                        <th>Qty</th>
                        <th class="text-right">
                            Total
                        </th>
                    </tr>

                </thead>

                <tbody>

                    @foreach($order->orderDetails as $detail)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ optional($detail->product)->getTranslation('name') }}
                        </td>

                        <td>
                            ₹ {{ number_format(optional($detail->product)->seller_price ?? 0,2) }}
                        </td>

                        <td>
                            {{ $detail->quantity }}
                        </td>

                        <td class="text-right">
                            ₹ {{ number_format($detail->price,2) }}
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

        {{-- SUMMARY --}}
        <div class="row justify-content-end">

            <div class="col-md-4">

                <div class="border rounded p-3">

                    <table class="table table-sm mb-0">

                        <tr>

                            <td>
                                Grand Total
                            </td>

                            <td class="text-right text-success font-weight-bold">

                                ₹ {{ number_format($order->grand_total,2) }}

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- PAYMENT MODAL --}}
@if($payment)

<div class="modal fade"
     id="paymentProofModal"
     tabindex="-1"
     role="dialog">

    <div class="modal-dialog modal-lg modal-dialog-centered"
         role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Payment Details
                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <small class="text-muted d-block">
                            Order ID
                        </small>

                        <strong>
                            #{{ $order->id }}
                        </strong>

                    </div>

                    <div class="col-md-6 mb-3">

                        <small class="text-muted d-block">
                            UTR Number
                        </small>

                        <strong>
                            {{ $payment->utr }}
                        </strong>

                    </div>

                    <div class="col-md-6 mb-3">

                        <small class="text-muted d-block">
                            Payment Date
                        </small>

                        <strong>
                            {{ date('d M Y', strtotime($payment->payment_date)) }}
                        </strong>

                    </div>

                    <div class="col-md-6 mb-3">

                        <small class="text-muted d-block">
                            Submitted At
                        </small>

                        <strong>
                            {{ $payment->created_at }}
                        </strong>

                    </div>

                    <div class="col-md-6 mb-3">

                        <small class="text-muted d-block">
                            Amount
                        </small>

                        <strong>
                            ₹ {{ number_format($order->grand_total,2) }}
                        </strong>

                    </div>

                    <div class="col-md-6 mb-3">

                        <small class="text-muted d-block">
                            Status
                        </small>

                        @if($payment->status=='approved')
                            <span class="badge badge-inline badge-success">
                                Approved
                            </span>
                        @elseif($payment->status=='rejected')
                            <span class="badge badge-inline badge-danger">
                                Rejected
                            </span>
                        @else
                            <span class="badge badge-inline badge-warning">
                                Pending
                            </span>
                        @endif

                    </div>

                </div>

                @if($payment->note)

                <hr>

                <h6>Note</h6>

                <div class="border rounded p-3 bg-light">

                    {{ $payment->note }}

                </div>

                @endif

                @if($payment->screenshot)

                <hr>

                <h6 class="mb-3">
                    Payment Proof
                </h6>

                @php
                    $extension = strtolower(pathinfo($payment->screenshot, PATHINFO_EXTENSION));
                @endphp

                @if(in_array($extension,['jpg','jpeg','png','webp']))

                    <img src="{{ asset($payment->screenshot) }}"
                         class="img-fluid rounded border">

                @else

                    <a href="{{ asset($payment->screenshot) }}"
                       target="_blank"
                       class="btn btn-danger">

                        Open Uploaded PDF

                    </a>

                @endif

                @endif

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">

                    Close

                </button>

            </div>

        </div>

    </div>

</div>

@endif

@endsection