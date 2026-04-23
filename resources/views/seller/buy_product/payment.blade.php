@extends('seller.layouts.app')

@section('panel_content')

<div class="aiz-titlebar mt-2 mb-4">
    <h1 class="h3">Manual Payment</h1>
</div>

<!-- ✅ ORDER + AMOUNT BOX -->
<div class="card mb-3 border-left border-success">
    <div class="card-body d-flex justify-content-between align-items-center">

        <div>
            <small class="text-muted">Order ID</small><br>
            <strong>#{{ $order->id }}</strong>
        </div>

        <div class="text-right">
            <small class="text-muted">Amount to Pay</small><br>
            <h3 class="text-success mb-0">
                ₹ {{ number_format($order->grand_total, 2) }}
            </h3>
        </div>

    </div>
</div>

<div class="row">

    <!-- BANK DETAILS -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">

                <h5 class="mb-3">Bank Transfer Details</h5>

                <table class="table table-sm table-borderless">
                    <tr>
                        <th class="text-muted">Account Name</th>
                        <td>Admin Name</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Account Number</th>
                        <td>1234567890</td>
                    </tr>
                    <tr>
                        <th class="text-muted">IFSC Code</th>
                        <td>SBIN0001234</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Bank</th>
                        <td>SBI</td>
                    </tr>
                </table>

                <!-- ⚠ WARNING -->
                <small class="text-danger">
                    ⚠ Please pay the exact amount ₹ {{ number_format($order->grand_total, 2) }}
                </small>

            </div>
        </div>
    </div>

    <!-- QR CODE -->
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body">

                <h5 class="mb-3">Scan & Pay</h5>

                <img src="{{ asset('public/assets/img/qr-code.png') }}"
                     style="width:200px;height:200px;object-fit:contain;">

                <p class="mt-2 text-muted">UPI / PhonePe / Paytm</p>

            </div>
        </div>
    </div>

</div>

<!-- PAYMENT FORM -->
<div class="card mt-4">
    <div class="card-body">

        <h5 class="mb-3">Submit Payment Details</h5>

        <form action="{{ route('seller.payment.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- ✅ ORDER ID -->
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>UTR / Transaction ID</label>
                    <input type="text" name="utr" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Payment Date</label>
                    <input type="date" name="payment_date" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Upload Screenshot</label>
                    <input type="file" name="screenshot" class="form-control" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label>Note (Optional)</label>
                    <textarea name="note" class="form-control"></textarea>
                </div>

            </div>

            <div class="text-right">
                <button class="btn btn-success px-4">
                    Submit Payment
                </button>
            </div>

        </form>

    </div>
</div>

@endsection