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
                    <div class="col-md-3">
                        <select name="seller_id" class="form-control aiz-selectpicker" data-live-search="true"
                            onchange="this.form.submit()">
                            <option value="">All Sellers</option>
                            @foreach ($sellers as $id => $name)
                                <option value="{{ $id }}" {{ request('seller_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- PRODUCT DROPDOWN -->
                    <div class="col-md-3">
                        <select name="product_id" class="form-control aiz-selectpicker" data-live-search="true"
                            onchange="this.form.submit()">
                            <option value="">All Products</option>
                            @foreach ($products as $id => $name)
                                <option value="{{ $id }}" {{ request('product_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- DISTRICT -->
                    <div class="col-md-2">

                        <select name="district_id" class="form-control aiz-selectpicker" data-live-search="true"
                            onchange="this.form.submit()">

                            <option value="">
                                District
                            </option>

                            @foreach (\App\Models\City::where('status', 1)->get() as $district)
                                <option value="{{ $district->id }}"
                                    {{ request('district_id') == $district->id ? 'selected' : '' }}>

                                    {{ $district->name }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <!-- BLOCK -->
                    <div class="col-md-2">

                        <select name="block_id" class="form-control aiz-selectpicker" data-live-search="true"
                            onchange="this.form.submit()">

                            <option value="">
                                Block
                            </option>

                            @foreach (\App\Models\Block::where('status', 1)->get() as $block)
                                <option value="{{ $block->id }}" {{ request('block_id') == $block->id ? 'selected' : '' }}>

                                    {{ $block->name }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <!-- SUBDISTRICT -->
                    {{-- <div class="col-md-2">

                        <select name="sub_district_id" class="form-control aiz-selectpicker" data-live-search="true"
                            onchange="this.form.submit()">

                            <option value="">
                                Subdistrict
                            </option>

                            @foreach (\App\Models\SubDistrict::where('status', 1)->get() as $sub)
                                <option value="{{ $sub->id }}"
                                    {{ request('sub_district_id') == $sub->id ? 'selected' : '' }}>

                                    {{ $sub->name }}

                                </option>
                            @endforeach

                        </select>

                    </div> --}}



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
                        <th>Low Stock</th> 
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
                                <strong>Total low stock:</strong>
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
