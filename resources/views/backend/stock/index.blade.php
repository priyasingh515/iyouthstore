@extends('backend.layouts.app')

@section('content')

<div class="card">

    <div class="card-header">
        <h5>Low Seller Stock</h5>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>#</th>
                    <th>Seller</th>
                    <th>Shop ID</th>
                    <th>Product</th>
                    <th>Stock</th>
                </tr>
            </thead>

            <tbody>

                @forelse($lowStocks as $key => $item)

                <tr>

                    <td>{{ $key+1 }}</td>

                    <td>{{ $item->seller_name }}</td>

                    <td>{{ $item->shop_id }}</td>

                    <td>{{ $item->product_name }}</td>

                    <td>
                        <span class="badge badge-danger">
                            {{ $item->stock }}
                        </span>
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="5" class="text-center">
                        No low stock found
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection