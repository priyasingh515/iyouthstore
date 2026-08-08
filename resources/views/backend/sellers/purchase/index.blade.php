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
                            <th>Date</th>
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

                                 <td>
                                    {{ date('d-m-Y', strtotime($order->created_at)) }}
                                </td>

                                <td class="text-right">
                                    {{-- <a class="btn btn-soft-warning btn-icon btn-circle btn-sm"
                                        href="{{ route('payment.view', $order->id) }}"
                                        title="View Payment">
                                        <i class="las la-credit-card"></i>
                                    </a> --}}

                                    <button type="button"
                                        class="btn btn-soft-warning btn-icon btn-circle btn-sm view-payment-btn"
                                        data-id="{{ $order->id }}">
                                        <i class="las la-credit-card"></i>
                                    </button>

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


    {{-- <div class="modal fade" id="paymentModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Payment Details</h5>
                    <button type="button" onclick="closeModal()">×</button>
                </div>

                <div class="modal-body" id="paymentModalBody">
                    Loading...
                </div>

            </div>
        </div>
    </div> --}}

    <div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="las la-credit-card"></i> Payment Details
                </h5>

                <button type="button" class="close text-white" data-dismiss="modal">
                    &times;
                </button>
            </div>

            <!-- BODY -->
            <div class="modal-body p-4" id="paymentModalBody">

                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2 text-muted">Loading payment details...</p>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer justify-content-between">

                <small class="text-muted">
                    Verify payment before approving
                </small>

                <button type="button" class="btn btn-light" data-dismiss="modal">
                    Close
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
    <script>
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.view-payment-btn');

            if (!btn) {
                return;
            }

            e.preventDefault();
            e.stopPropagation();

            const orderId = btn.dataset.id;
            const modalBody = document.getElementById('paymentModalBody');
            let paymentUrl = "{{ route('payment.view', ':id') }}";
            paymentUrl = paymentUrl.replace(':id', orderId);

            modalBody.innerHTML = 'Loading...';
            $('#paymentModal').modal('show');

            fetch(paymentUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to load payment details');
                    }

                    return response.text();
                })
                .then(data => {
                    modalBody.innerHTML = data;
                })
                .catch(() => {
                    modalBody.innerHTML = 'Error loading data';
                });
        });

        function closeModal() {
            $('#paymentModal').modal('hide');
        }
    </script>

    <script>
        document.addEventListener('click', function(e) {

            // APPROVE
            if (e.target.closest('.approve-btn')) {

                let id = e.target.closest('.approve-btn').dataset.id;

                let url = "{{ url('admin/payment') }}/" + id + "/approve";

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(async res => {
                        if (!res.ok) {
                            let text = await res.text();
                            console.error(text);
                            throw new Error('Server error');
                        }
                        return res.json();
                    })
                    .then(data => {
                        alert(data.message);
                        location.reload();
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Something went wrong');
                    });
            }

            // REJECT
            if (e.target.closest('.reject-btn')) {

                let id = e.target.closest('.reject-btn').dataset.id;

                let url = "{{ url('admin/payment') }}/" + id + "/reject";

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(async res => {
                        if (!res.ok) {
                            let text = await res.text();
                            console.error(text);
                            throw new Error('Server error');
                        }
                        return res.json();
                    })
                    .then(data => {
                        alert(data.message);
                        location.reload();
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Something went wrong');
                    });
            }

        });
    </script>
@endsection
