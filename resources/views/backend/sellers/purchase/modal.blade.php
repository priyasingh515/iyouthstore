<div class="p-2">

    <table class="table table-sm table-borderless mb-3">
        <tr>
            <th width="40%" class="text-muted">Payment Method</th>
            <td>{{ ucfirst($payment->payment_method) }}</td>
        </tr>

        <tr>
            <th class="text-muted">UTR Number</th>
            <td>{{ $payment->utr }}</td>
        </tr>

        <tr>
            <th class="text-muted">Payment Date</th>
            <td>{{ $payment->payment_date }}</td>
        </tr>

        <tr>
            <th class="text-muted">Status</th>
            <td>
                @if($payment->status == 'pending')
                    <span class="badge badge-inline badge-warning">Pending</span>
                @elseif($payment->status == 'approved')
                    <span class="badge badge-inline badge-success">Approved</span>
                @else
                    <span class="badge badge-inline badge-danger">Rejected</span>
                @endif
            </td>
        </tr>
    </table>

    @if($payment->note)
        <div class="mb-3">
            <label class="text-muted">Note</label>
            <div class="border rounded p-2 bg-light">
                {{ $payment->note }}
            </div>
        </div>
    @endif

    {{-- <div class="mb-3">
        <label class="text-muted d-block mb-1">Payment Screenshot</label>

        <div class="border rounded p-2 text-center bg-light">
            <img src="{{ asset('public/storage/'.$payment->screenshot) }}"
                 class="img-fluid"
                 style="max-height:300px; cursor:pointer;"
                 onclick="window.open(this.src)">
        </div>
    </div> --}}

    <div class="mb-3">
    <label class="text-muted d-block mb-1">Payment Screenshot</label>

    <div class="border rounded p-2 text-center bg-light">

        @if($payment->screenshot)
            <img src="{{ asset('public/'.$payment->screenshot) }}"
                 class="img-fluid"
                 style="max-height:300px; cursor:pointer;"
                 onclick="window.open(this.src)">
        @else
            <p class="text-muted">No screenshot found</p>
        @endif

    </div>
</div>


 @if($payment->status != 'approved')
    <div class="text-right">
        <button class="btn btn-success btn-sm approve-btn" data-id="{{ $payment->id }}">
            Approve
        </button>

        <button class="btn btn-danger btn-sm reject-btn" data-id="{{ $payment->id }}">
            Reject
        </button>
    </div>
    @endif

</div>