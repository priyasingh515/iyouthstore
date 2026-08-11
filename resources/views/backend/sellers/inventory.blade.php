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
                <div class="col-lg-2 my-2">
                    <select class="form-control aiz-selectpicker" name="district_id" onchange="sort_sellers()"
                        data-live-search="true">

                        <option value="">{{ translate('District') }}</option>

                        @foreach (\App\Models\City::where('status', 1)->get() as $district)
                            <option value="{{ $district->id }}"
                                {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                {{ $district->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-2 my-2">
                    <select class="form-control aiz-selectpicker" name="block_id" onchange="sort_sellers()"
                        data-live-search="true">

                        <option value="">{{ translate('Block') }}</option>

                        @foreach (\App\Models\Block::where('status', 1)->get() as $block)
                            <option value="{{ $block->id }}" {{ request('block_id') == $block->id ? 'selected' : '' }}>
                                {{ $block->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- <div class="col-lg-2 my-2">
                    <select class="form-control aiz-selectpicker" name="sub_district_id" onchange="sort_sellers()"
                        data-live-search="true">

                        <option value="">{{ translate('Subdistrict') }}</option>

                        @foreach (\App\Models\SubDistrict::where('status', 1)->get() as $sub)
                            <option value="{{ $sub->id }}"
                                {{ request('sub_district_id') == $sub->id ? 'selected' : '' }}>
                                {{ $sub->name }}
                            </option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="col-md-3">
                    <div class="form-group mb-0">
                        <input type="text" class="form-control" id="search" name="search" value=""
                            placeholder="{{ translate('Type Seller Name') }}">
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

                        @foreach ($shops as $shop)
                            <tr>

                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $shop->shop_name }}</td>
                                <td>{{ $shop->total_stock }}</td>
                                <td>
                                    <a href="{{ route('sellers.stock.detail', parameters: $shop->seller_id) }}"
                                        class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                        title="{{ translate('View') }}">
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

@section('script')
<script>
    function sort_sellers(){
        $('#sort_sellers').submit();
    }
</script>
@endsection
