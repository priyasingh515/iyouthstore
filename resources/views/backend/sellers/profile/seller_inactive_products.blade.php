<div class="card">

    <div class="card-header">
        <h5>Inactive Products</h5>
    </div>

    <div class="card-body">

        {{-- <form method="GET" class="mb-3"> --}}
            <form onsubmit="filterInactiveProducts(); return false;">

            <div class="row">

                <div class="col-md-3">
                    <label>From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
                </div>

                <div class="col-md-3">
                    <label>To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
                </div>

                <div class="col-md-2 mt-4">
                    {{-- <button type="submit" class="btn btn-primary">
                        Filter
                    </button> --}}
                    {{-- <button type="button"
        class="btn btn-primary"
        onclick="filterInactiveProducts()">
Filter
</button> --}}
<button type="submit" class="btn btn-primary">
    Filter
</button>
                </div>

            </div>

        </form>

        <div class="table-responsive">

            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Assigned Date</th>
                        <th>Last Sold</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($inactive_products as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>{{ $item->name }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($item->assigned_date)->format('d M Y') }}
                            </td>

                            <td>

                                @if ($item->last_sold_date)
                                    {{ \Carbon\Carbon::parse($item->last_sold_date)->format('d M Y') }}
                                @else
                                    <span class="badge badge-inline badge-danger">
                                        Never Sold
                                    </span>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center">
                                No inactive products found
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
    function filterInactiveProducts()
{
    var from = $('input[name="from_date"]').val();
    var to   = $('input[name="to_date"]').val();

    var url = "{{ route('sellers.profile.tab', $shop->id) }}";

    $.get(url, {
        tab: 'inactive_products',
        from_date: from,
        to_date: to
    }, function(data){
        $('#tab-content').html(data.html);
    });
}
</script>