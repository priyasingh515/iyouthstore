@extends('backend.layouts.app')

@section('content')



<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3"> {{ $seller->name }} - Seller Inventory</h1>
        </div>
    
    </div>
</div>

<div class="card">
    <form class="" id="sort_sellers" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6"> {{ $seller->name }} - Seller Inventory</h5>
            </div>
        
            <div class="col-md-3">
                <div class="form-group mb-0">
                  <input type="text" class="form-control" id="search" name="search" value="" placeholder="{{ translate('Type Product Name') }}">
                </div>
            </div>
        </div>

    
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <th>SN</th>
                    <th>Product Name</th>
                    <th>Total Quantity</th>
                </thead>
                <tbody>
            
                    @foreach ($sellerProducts as $key => $product)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $product->product->name }}</td>
                            <td>{{ $product->stock }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
             
            </div>
        </div>
    </form>
</div>

@endsection


