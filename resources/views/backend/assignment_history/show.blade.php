@extends('backend.layouts.app')

@section('content')

<div class="card">
    
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Assignment History</h5>
        <span class="badge badge-inline badge-primary">
            Seller : {{ $seller->name }}
        </span>
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-striped table-bordered">

                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Shop ID</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Assigned Date</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($history as $key => $item)

                    <tr>
                        <td>{{ $key + 1 }}</td>

                        <td>{{ $item->shop_id }}</td>

                        <td>{{ $item->product_name }}</td>

                        <td>
                            <span class="badge badge-success">
                                {{ $item->quantity }}
                            </span>
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y H:i') }}
                        </td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            No products have been assigned to this seller yet.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection