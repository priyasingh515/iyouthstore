@extends('backend.layouts.app')

@section('content')
<div class="card">

    {{-- Header --}}
    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Inactive Seller Products
        </h5>

        <span>
            Total: {{ count($sellers) }}
        </span>

    </div>


    <div class="card-body">

        {{-- Filter --}}
        <form method="GET" action="{{ route('admin.inactive.products') }}" class="mb-4">

            <div class="row align-items-end">

                <div class="col-md-2">

                    <label class="small font-weight-bold">
                        From Date
                    </label>

                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">

                </div>


                <div class="col-md-2">

                    <label class="small font-weight-bold">
                        To Date
                    </label>

                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">

                </div>

                {{--
                    <div class="col-md-6"> --}}
                <div class="col-md-2 d-flex align-items-center">

                    <button type="submit" class="btn btn-primary mr-2">

                        <i class="las la-filter">Apply</i>

                    </button>


                    <a href="{{ route('admin.inactive.products') }}" class="btn btn-outline-secondary">

                        Reset

                    </a>

                </div>

                {{-- DISTRICT --}}
                <div class="col-md-2">

                    <select name="district_id" id="district_id" class="form-control aiz-selectpicker" data-live-search="true">

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


                {{-- BLOCK --}}
                <div class="col-md-2">

                    <select name="block_id" id="block_id" class="form-control aiz-selectpicker" data-live-search="true">

                        <option value="">
                            Block
                        </option>

                        @foreach(
                        \App\Models\Block::where('status',1)
                        ->when(request('district_id'), function($q){
                        $q->where('district_id', request('district_id'));
                        })
                        ->get() as $block
                        )
                        <option value="{{ $block->id }}" {{ request('block_id') == $block->id ? 'selected' : '' }}>

                            {{ $block->name }}

                        </option>
                        @endforeach

                    </select>

                </div>


                {{-- SUBDISTRICT --}}
                <!-- <div class="col-md-2">

                        <select name="sub_district_id" class="form-control aiz-selectpicker" data-live-search="true">

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

                    </div> -->

            </div>

        </form>



        {{-- Table --}}
        <div class="table-responsive">

            <table class="table table-bordered table-hover w-100">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Seller Name</th>

                        <th>Shop ID</th>

                        <th>Unsold Products</th>

                        <th class="text-center">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($sellers as $key => $seller)
                    <tr>

                        <td>
                            {{ $key + 1 }}
                        </td>

                        <td>
                            <strong>
                                {{ $seller->seller_name }}
                            </strong>
                        </td>

                        <td>
                            {{ $seller->shop_id }}
                        </td>

                        <td>

                            <span class="badge badge-danger">

                                {{ $seller->unsold_products_count }}

                            </span>

                        </td>

                        <td class="text-center">

                            <a href="{{ route('inactive_products.show', $seller->seller_id) }}"
                                class="btn btn-info btn-sm">

                                <i class="las la-eye"></i>

                            </a>

                        </td>

                    </tr>

                    @empty


                    <tr>

                        <td colspan="5" class="text-center">

                            No inactive products found

                        </td>

                    </tr>
                    @endforelse


                </tbody>

            </table>

        </div>


    </div>

</div>
@endsection

@section('script')
<script>
    $(document).on('changed.bs.select', '#district_id', function() {
        $(this).closest('form').submit();
    });

    $(document).on('changed.bs.select', '#block_id', function() {
        $(this).closest('form').submit();
    });
</script>
@endsection