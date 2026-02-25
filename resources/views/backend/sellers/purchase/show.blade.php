@extends('backend.layouts.app')

@section('content')
    <div class="card shadow-sm">

        ```
        {{-- Header --}}
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                {{ translate('Seller Purchase Details') }}
            </h5>

            <a href="{{ route('seller-purchases.index') }}" class="btn btn-light btn-sm">
                <i class="las la-arrow-left"></i>
                {{ translate('Back') }}
            </a>
        </div>


        <div class="card-body">

            {{-- Top Info Row --}}
            <div class="row">

                {{-- Left Column --}}
                <div class="col-md-6">

                    <div class="mb-3">
                        <small class="text-muted d-block">
                            {{ translate('Order Code') }}
                        </small>

                        <h5 class="mb-0">
                            #{{ $order->code }}
                        </h5>
                    </div>


                    <div class="mb-3">
                        <small class="text-muted d-block">
                            {{ translate('Order Date') }}
                        </small>

                        <strong>
                            {{ date('d M Y, h:i A', $order->date) }}
                        </strong>
                    </div>


                    <div class="mb-3">
                        <small class="text-muted d-block">
                            {{ translate('Payment Method') }}
                        </small>

                        <strong>
                            {{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}
                        </strong>
                    </div>

                </div>


                {{-- Right Column --}}
                <div class="col-md-6">

                    <div class="row">

                        {{-- Payment Status Card --}}
                        <div class="col-md-6">

                            <div class="border rounded p-3 text-center">

                                <small class="text-muted d-block mb-1">
                                    {{ translate('Payment Status') }}
                                </small>

                                <span id="payment_status_badge"
                                    class="badge badge-inline mb-2
                              {{ $order->payment_status == 'paid' ? 'badge-success' : 'badge-danger' }}">

                                    {{ ucfirst($order->payment_status) }}

                                </span>

                                <select id="payment_status" class="form-control form-control-sm"
                                    onchange="updatePaymentStatus({{ $order->id }})">

                                    <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>
                                        Unpaid
                                    </option>

                                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>
                                        Paid
                                    </option>

                                </select>

                            </div>

                        </div>


                        {{-- Delivery Status Card --}}
                        <div class="col-md-6">

                            <div class="border rounded p-3 text-center">

                                <small class="text-muted d-block mb-1">
                                    {{ translate('Delivery Status') }}
                                </small>

                                <span id="delivery_status_badge"
                                    class="badge badge-inline mb-2
                              @if ($order->delivery_status == 'pending') badge-secondary
                              @elseif($order->delivery_status == 'confirmed') badge-primary
                              @elseif($order->delivery_status == 'on_the_way') badge-warning
                              @elseif($order->delivery_status == 'delivered') badge-success @endif">

                                    {{ ucfirst(str_replace('_', ' ', $order->delivery_status)) }}

                                </span>

                                {{-- <select id="delivery_status" class="form-control form-control-sm"
                                    onchange="updateDeliveryStatus({{ $order->id }})"> --}}

                                <select id="delivery_status" class="form-control form-control-sm"
                                    onchange="updateDeliveryStatus({{ $order->id }})"
                                    {{ $order->delivery_status == 'delivered' ? 'disabled' : '' }}>

                                    <option value="pending" {{ $order->delivery_status == 'pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>

                                    <option value="confirmed"
                                        {{ $order->delivery_status == 'confirmed' ? 'selected' : '' }}>
                                        Confirmed
                                    </option>

                                    <option value="on_the_way"
                                        {{ $order->delivery_status == 'on_the_way' ? 'selected' : '' }}>
                                        On The Way
                                    </option>

                                    <option value="delivered"
                                        {{ $order->delivery_status == 'delivered' ? 'selected' : '' }}>
                                        Delivered
                                    </option>

                                </select>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Seller Info --}}
            <hr>

            <div class="mb-4">

                <small class="text-muted d-block">
                    {{ translate('Seller') }}
                </small>

                <h6 class="mb-0">
                    {{ $order->shop ? $order->shop->name : translate('Admin') }}
                </h6>

            </div>


            {{-- Products Table --}}
            <div class="table-responsive">

                <table class="table table-hover table-bordered">

                    <thead class="light">
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Product') }}</th>
                            <th width="120">{{ translate('Qty') }}</th>
                            <th width="150">{{ translate('Unit Price') }}</th>
                            <th width="150" class="text-right">
                                {{ translate('Total') }}
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @php
                            $subTotal = 0;
                            $taxTotal = 0;
                            $shippingTotal = 0;
                        @endphp

                        @foreach ($order->orderDetails as $key => $detail)
                            @php
                                $total = $detail->price * $detail->quantity;
                                $subTotal += $total;
                                $taxTotal += $detail->tax;
                                $shippingTotal += $detail->shipping_cost;
                            @endphp

                            <tr>

                                <td>{{ $key + 1 }}</td>

                                <td>
                                    {{ $detail->product ? $detail->product->getTranslation('name') : translate('Product Deleted') }}
                                </td>

                                <td>
                                    {{ $detail->quantity }}
                                </td>

                                <td>
                                    {{ single_price($detail->price) }}
                                </td>

                                <td class="text-right font-weight-bold">
                                    {{ single_price($total) }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- Summary --}}
            <div class="row justify-content-end">

                <div class="col-md-4">

                    <div class="border rounded p-3">

                        <table class="table table-sm mb-0">

                            <tr>
                                <td>{{ translate('Sub Total') }}</td>
                                <td class="text-right">
                                    {{ single_price($subTotal) }}
                                </td>
                            </tr>

                            <tr>
                                <td>{{ translate('Tax') }}</td>
                                <td class="text-right">
                                    {{ single_price($taxTotal) }}
                                </td>
                            </tr>

                            <tr>
                                <td>{{ translate('Shipping') }}</td>
                                <td class="text-right">
                                    {{ single_price($shippingTotal) }}
                                </td>
                            </tr>

                            <tr>
                                <td>{{ translate('Coupon') }}</td>
                                <td class="text-right text-danger">
                                    - {{ single_price($order->coupon_discount) }}
                                </td>
                            </tr>

                            <tr class="border-top font-weight-bold">
                                <td>{{ translate('Grand Total') }}</td>
                                <td class="text-right text-success">
                                    {{ single_price($subTotal + $taxTotal + $shippingTotal - $order->coupon_discount) }}
                                </td>
                            </tr>

                        </table>

                    </div>

                </div>

            </div>


        </div>
        ```

    </div>


    <!-- Success Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">

                <div class="modal-body text-center p-4">

                    <div id="modal_icon" class="mb-3">
                        <i class="las la-check-circle text-success" style="font-size: 60px;"></i>
                    </div>

                    <h5 id="modal_title" class="mb-2">
                        Success
                    </h5>

                    <p id="modal_message" class="text-muted mb-3">
                        Status updated successfully
                    </p>

                    <button type="button" class="btn btn-success px-4" data-dismiss="modal">
                        OK
                    </button>

                </div>

            </div>
        </div>
    </div>



    <script>
        // function updateDeliveryStatus(orderId) {
        //     let status = document.getElementById('delivery_status').value;

        //     fetch("{{ route('seller.update.delivery.status') }}", {
        //             method: "POST",
        //             headers: {
        //                 "Content-Type": "application/json",
        //                 "X-CSRF-TOKEN": "{{ csrf_token() }}"
        //             },
        //             body: JSON.stringify({
        //                 order_id: orderId,
        //                 status: status
        //             })
        //         })
        //         .then(res => res.json())
        //         .then(data => {

        //             showStatusModal(data.status, data.message);

        //         });

        // }

        function updateDeliveryStatus(orderId) {

            let select = document.getElementById('delivery_status');
            let status = select.value;

            fetch("{{ route('seller.update.delivery.status') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        status: status
                    })
                })
                .then(res => res.json())
                .then(data => {

                    showStatusModal(data.status, data.message);

                    let badge = document.getElementById('delivery_status_badge');

                    badge.innerText = status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());

                    badge.className = "badge badge-inline mb-2 " + getDeliveryBadgeClass(status);

                    if (status === 'delivered') {

                        select.disabled = true;

                    }

                });
        }


        function updatePaymentStatus(orderId) {
            let status = document.getElementById('payment_status').value;

            fetch("{{ route('seller.update.payment.status') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        status: status
                    })
                })
                .then(res => res.json())
                .then(data => {

                    // show modal instead of alert
                    showStatusModal(data.status, data.message);

                    let badge = document.getElementById('payment_status_badge');

                    badge.innerText = status;

                    badge.className = "badge badge-inline mr-2 " +
                        (status == 'paid' ? 'badge-success' : 'badge-danger');

                });

        }


        function getDeliveryBadgeClass(status) {
            switch (status) {
                case 'pending':
                    return 'badge-inline badge-secondary';
                case 'confirmed':
                    return 'badge-inline badge-primary';
                case 'on_the_way':
                    return 'badge-inline badge-warning';
                case 'delivered':
                    return 'badge-inline badge-success';
            }
        }



        function showStatusModal(success, message) {
            let icon = document.getElementById('modal_icon');
            let title = document.getElementById('modal_title');
            let msg = document.getElementById('modal_message');

            if (success) {
                icon.innerHTML = '<i class="las la-check-circle text-success" style="font-size:60px;"></i>';
                title.innerText = "Success";
            } else {
                icon.innerHTML = '<i class="las la-times-circle text-danger" style="font-size:60px;"></i>';
                title.innerText = "Error";
            }

            msg.innerText = message;

            $('#statusModal').modal('show');
        }
    </script>
@endsection
