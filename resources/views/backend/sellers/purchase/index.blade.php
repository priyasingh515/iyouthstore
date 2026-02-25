@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <form action="{{ route('seller-purchases.index') }}" id="sort_orders" method="GET">

            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">Seller Purchases</h5>
                </div>

                <div class="col-lg-2 ml-auto">
                    <select class="form-control aiz-selectpicker" name="delivery_status">
                        <option value="">Filter by Delivery Status</option>
                        <option value="pending" @if ($delivery_status == 'pending') selected @endif>Pending</option>
                        <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>Confirmed</option>
                        <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>On The Way</option>
                        <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>Delivered</option>
                        <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>Cancelled</option>
                    </select>
                </div>

                <div class="col-lg-2 ml-auto">
                    <select class="form-control aiz-selectpicker" name="payment_status">
                        <option value="">Filter by Payment Status</option>
                        <option value="paid" @if ($payment_status == 'paid') selected @endif>Paid</option>
                        <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>Unpaid</option>
                    </select>
                </div>

                <div class="col-lg-2">
                    <input type="text" class="form-control aiz-date-range" name="date" value="{{ $date }}"
                        placeholder="Filter by date" data-format="DD-MM-Y" data-separator=" to " data-advanced-range="true">
                </div>

                <div class="col-lg-2">
                    <input type="text" class="form-control" name="search" value="{{ $sort_search }}"
                        placeholder="Type Order code & hit Enter">
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </div>

            <div class="card-body">

                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order Code</th>
                            <th>Num. of Products</th>
                            <th>Seller (Buyer)</th>
                            <th>Amount</th>
                            <th>Delivery Status</th>
                            <th>Payment Method</th>
                            <th>Payment Status</th>
                            <th width="15%" class="text-right">Options</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($orders as $key => $order)
                            <tr>
                                <td>
                                    {{ $key + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}
                                </td>

                                <td>
                                    {{ $order->code }}
                                    @if ($order->viewed == 0)
                                        <span class="badge badge-inline badge-info">New</span>
                                    @endif
                                </td>

                                <td>
                                    {{ count($order->orderDetails) }}
                                </td>

                                <td>
                                    {{ optional($order->user)->name }}
                                </td>

                                <td>
                                    {{ single_price($order->grand_total) }}
                                </td>

                                <td>
                                    {{ ucfirst(str_replace('_', ' ', $order->delivery_status)) }}
                                </td>

                                <td>
                                    {{ ucfirst(str_replace('_', ' ', $order->payment_type)) }}
                                </td>

                                <td>
                                    @if ($order->payment_status == 'paid')
                                        <span class="badge badge-inline badge-success">Paid</span>
                                    @else
                                        <span class="badge badge-inline badge-danger">Unpaid</span>
                                    @endif
                                </td>

                                <td class="text-right">

                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                        href="{{ route('seller-purchases.show', encrypt($order->id)) }}" title="View">
                                        <i class="las la-eye"></i>
                                    </a>

                                    <a class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                        href="{{ route('invoice.download', $order->id) }}" title="Download Invoice">
                                        <i class="las la-download"></i>
                                    </a>

                                    <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                        onclick="confirmDelete(event, {{ $order->id }})" title="Delete">

                                        <i class="las la-trash"></i>

                                    </a>


                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="aiz-pagination">
                    {{ $orders->appends(request()->input())->links() }}
                </div>

            </div>
        </form>
    </div>



    <form id="deleteForm" method="POST">
        @csrf
        @method('DELETE')
    </form>


    <!-- Reusable Confirmation Modal -->
    <div class="modal fade" id="reusable-confirm-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body text-center p-4">

                    <i class="las la-exclamation-circle text-warning mb-3" style="font-size:60px;"></i>

                    <h5 id="reusable-confirm-title">Confirmation</h5>

                    <p class="text-muted" id="reusable-confirm-message">Are you sure?</p>

                    <button type="button" class="btn btn-danger" id="reusable-confirm-submit">

                        Confirm

                    </button>

                    <button type="button" class="btn btn-light" data-dismiss="modal">

                        Cancel

                    </button>

                </div>

            </div>
        </div>
    </div>


    <script>
        let deleteOrderId = null;
        let confirmAction = null;

        function openReusableConfirmModal(title, message, callback) {
            $('#reusable-confirm-title').text(title || 'Confirmation');
            $('#reusable-confirm-message').text(message || 'Are you sure?');
            confirmAction = callback;
            $('#reusable-confirm-modal').modal('show');
        }

        function confirmDelete(event, id) {
            event.preventDefault();
            deleteOrderId = id;

            openReusableConfirmModal(
                'Delete Confirmation',
                'This order will be permanently deleted.',
                deleteOrder
            );
        }

        function deleteOrder() {
            let form = document.getElementById('deleteForm');

            let url = "{{ route('seller-purchases.destroy', ':id') }}";

            url = url.replace(':id', deleteOrderId);

            form.action = url;

            form.submit();
        }

        $(document).on('click', '#reusable-confirm-submit', function() {
            let action = confirmAction;
            confirmAction = null;
            $('#reusable-confirm-modal').modal('hide');

            if (typeof action === 'function') {
                action();
            }
        });

        $('#reusable-confirm-modal').on('hidden.bs.modal', function() {
            confirmAction = null;
        });
    </script>
@endsection
