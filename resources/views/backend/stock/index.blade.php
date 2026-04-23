@extends('backend.layouts.app')

@section('content')
    <div class="card">

        <div class="card-header">
            <h5>Low Seller Stock</h5>
        </div>

        <div class="card-body">

            <form method="GET" action="{{ route('seller.low.stock') }}" class="mb-3">
                <div class="row">

                    <!-- SELLER DROPDOWN -->
                    <div class="col-md-4">
                        <select name="seller_id" class="form-control aiz-selectpicker" data-live-search="true">
                            <option value="">All Sellers</option>
                            @foreach ($sellers as $id => $name)
                                <option value="{{ $id }}" {{ request('seller_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- PRODUCT DROPDOWN -->
                    <div class="col-md-4">
                        <select name="product_id" class="form-control aiz-selectpicker" data-live-search="true">
                            <option value="">All Products</option>
                            @foreach ($products as $id => $name)
                                <option value="{{ $id }}" {{ request('product_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- BUTTONS -->
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Filter</button>
                    </div>

                    <div class="col-md-2">
                        <a href="{{ route('seller.low.stock') }}" class="btn btn-secondary w-100">
                            Reset
                        </a>
                    </div>

                </div>
            </form>

            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Seller</th>
                        <th>Shop ID</th>
                        <th>Product</th>
                        <th>Stock</th>
                        <th>Remaining</th> <!-- ✅ NEW -->
                    </tr>
                </thead>

                <tbody>

                    @forelse($lowStocks as $key => $item)
                        <tr>

                            <td>{{ $key + 1 }}</td>

                            <td>{{ $item->seller_name }}</td>

                            <td>{{ $item->shop_id }}</td>

                            <td>{{ $item->product_name }}</td>

                            <td>
                                <span class="badge badge-danger">
                                    {{ $item->stock }}
                                </span>
                            </td>

                            <td>
                                @if ($item->remaining <= 2)
                                    <span class="badge badge-danger">
                                        {{ $item->remaining }}
                                    </span>
                                @else
                                    <span class="badge badge-success">
                                        {{ $item->remaining }}
                                    </span>
                                @endif
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center">
                                No low stock found
                            </td>
                        </tr>
                    @endforelse

                </tbody>
                @if (request('product_id') && $lowStocks->count())
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right">
                                <strong>Total Remaining:</strong>
                            </td>
                            <td>
                                <span class="badge badge-primary">
                                    {{ $totalRemaining }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                @endif

            </table>

        </div>

    </div>
@endsection
