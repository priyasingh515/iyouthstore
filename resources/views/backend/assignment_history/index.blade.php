@extends('backend.layouts.app')

@section('content')
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
<<<<<<< Updated upstream
            <h5>Seller Assignment History</h5>

            <form method="GET" action="">
                <input type="text" name="search" class="form-control" placeholder="Search seller..."
                    value="{{ request('search') }}">
            </form>
        </div>

        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
=======

            <h5>Seller Assignment History</h5>

            {{-- SEARCH --}}
            <form method="GET" action="">
                <input type="text" name="search" class="form-control" placeholder="Search seller..."
                    value="{{ request('search') }}">
            </form>

        </div>

        {{-- FILTERS --}}
        <div class="px-3 pt-2">

            <form id="sort_sellers" method="GET">

                {{-- retain search --}}
                <input type="hidden" name="search" value="{{ request('search') }}">

                <div class="row">

                    <div class="col-md-2">

                        <select class="form-control aiz-selectpicker" name="district_id" onchange="sort_sellers()">

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

                    <div class="col-md-2">

                        <select class="form-control aiz-selectpicker" name="block_id" onchange="sort_sellers()">

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

                    <div class="col-md-2">

                        <select class="form-control aiz-selectpicker" name="sub_district_id" onchange="sort_sellers()">

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

                    </div>

                </div>

            </form>

        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>

                    <tr>

>>>>>>> Stashed changes
                        <th>#</th>
                        <th>Seller Name</th>
                        <th>Shop ID</th>
                        <th>History</th>
<<<<<<< Updated upstream
                    </tr>
=======

                    </tr>

>>>>>>> Stashed changes
                </thead>

                <tbody>

                    @forelse($sellers as $key => $seller)
                        <tr>
<<<<<<< Updated upstream
=======

>>>>>>> Stashed changes
                            <td>{{ $key + 1 }}</td>

                            <td>{{ $seller->seller_name }}</td>

                            <td>{{ $seller->shop_id }}</td>

                            <td>
<<<<<<< Updated upstream
                                <a href="{{ route('assignment.history.show', $seller->seller_id) }}"
                                    class="btn btn-info btn-sm">
                                    View History
                                </a>
                            </td>
=======

                                <a href="{{ route('assignment.history.show', $seller->seller_id) }}"
                                    class="btn btn-info btn-sm">

                                    View History

                                </a>

                            </td>

>>>>>>> Stashed changes
                        </tr>

                    @empty

                        <tr>
<<<<<<< Updated upstream
                            <td colspan="4" class="text-center">
                                No sellers found
                            </td>
=======

                            <td colspan="4" class="text-center">

                                No sellers found

                            </td>

>>>>>>> Stashed changes
                        </tr>
                    @endforelse

                </tbody>

            </table>
<<<<<<< Updated upstream
            {{ $sellers->links() }}
        </div>
    </div>
@endsection
=======

        </div>

    </div>
@endsection

@section('script')
    <script>
        function sort_sellers() {

            $('#sort_sellers').submit();

        }
    </script>
@endsection
>>>>>>> Stashed changes
