@extends('backend.layouts.app')

@section('content')



<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <h1 class="h3">Seller Inventory</h1>
        </div>
    
    </div>
</div>

<div class="card">
    <form class="" id="sort_sellers" action="" method="GET">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">Seller Inventory</h5>
            </div>
        
            <div class="col-md-3">
                <div class="form-group mb-0">
                  <input type="text" class="form-control" id="search" name="search" value="" placeholder="{{ translate('Type Seller Name') }}">
                </div>
            </div>
        </div>

    
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <th>SN</th>
                    <th>Shop Name</th>
                    <th>Total Quantity</th>
                    <th>Action</th>
                
                </thead>
                <tbody>

                    @foreach ($shops as  $shop)
                    <tr>

                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $shop->shop_name }}</td>
                        <td>{{ $shop->total_stock }}</td>
                        <td>
                            <a href="{{ route('sellers.stock.detail', parameters: $shop->seller_id) }}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('View') }}">
                                <i class="las la-eye"></i>
                            </a>
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


