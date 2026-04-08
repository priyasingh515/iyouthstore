@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar mt-2 mb-3">
    <div class="row align-items-center">
        
        <div class="col-md-6">
            <h1 class="h3">
                Unsold Products
            </h1>
            <p class="text-muted mb-0">
                Seller: <strong>{{ $seller->name }}</strong> | Shop ID: <strong>{{ $seller->shop_id }}</strong>
            </p>
        </div>

        <div class="col-md-6 text-md-right">
            <a href="{{ route('admin.inactive.products') }}" 
               class="btn btn-soft-secondary">
               
                <i class="las la-arrow-left"></i>
                Back to Sellers
                
            </a>
        </div>

    </div>
</div>


<div class="card shadow-sm">

    <div class="card-header">
        <h5 class="mb-0">
            Unsold Products List
        </h5>

        <span class="badge badge-danger badge-inline ml-2">
            Total: {{ count($products) }}
        </span>
    </div>


    <div class="card-body">

        <div class="table-responsive">

            <table class="table aiz-table table-hover mb-0">

                <thead>
                    <tr>
                        <th width="10%">#</th>
                        <th width="20%">Product ID</th>
                        <th>Product Name</th>
                        <th width="15%">Status</th>
                    </tr>
                </thead>


                <tbody>

                    @forelse($products as $key => $product)

                    <tr>

                        <td>
                            {{ $key + 1 }}
                        </td>

                        <td>
                            <strong>
                                {{ $product->id }}
                            </strong>
                        </td>

                        <td>
                            {{ $product->name }}
                        </td>

                        <td>
                            <span class="badge badge-inline badge-danger">
                                Never Sold
                            </span>
                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="4" class="text-center py-5">

                            <i class="las la-check-circle la-3x text-success mb-3"></i>

                            <div class="h5 text-success">
                                All products have been sold
                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection