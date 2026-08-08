@extends('backend.layouts.app')

@section('content')

    <div class="card">

        <div class="card-header">
            <h5 class="mb-0">Out of Stock Requests</h5>
        </div>

        <div class="card-body">

            <form id="sort_requests" method="GET">

                <div class="row mb-3">

                    <div class="col-lg-2">
                        <select class="form-control aiz-selectpicker" name="district_id" onchange="sort_requests()"
                            data-live-search="true">

                            <option value="">District</option>

                            @foreach (\App\Models\City::where('status', 1)->get() as $district)
                                <option value="{{ $district->id }}"
                                    {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-2">
                        <select class="form-control aiz-selectpicker" name="block_id" onchange="sort_requests()"
                            data-live-search="true">

                            <option value="">Block</option>

                            @foreach (\App\Models\Block::where('status', 1)->when(request('district_id'), function ($q) {
                $q->where('district_id', request('district_id'));
            })->get() as $block)
                                <option value="{{ $block->id }}"
                                    {{ request('block_id') == $block->id ? 'selected' : '' }}>
                                    {{ $block->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="col-lg-2">
                        <select class="form-control aiz-selectpicker" name="sub_district_id" onchange="sort_requests()"
                            data-live-search="true">

                            <option value="">Subdistrict</option>

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


            <div class="card-body">

                <table class="table table-bordered table-striped align-middle">

                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Failed Sellers</th>
                            <th>Location</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($requests as $key => $req)
                            <tr>

                                <td>{{ $key + 1 }}</td>

                                <!-- USER -->
                                <td>
                                    <strong>{{ $req->user->name ?? '-' }}</strong>
                                </td>

                                <!-- PRODUCT -->
                                <td>
                                    <span class="badge badge-inline badge-primary">
                                        {{ $req->product->name ?? '-' }}
                                    </span>
                                </td>

                                <!-- QTY -->
                                <td>
                                    <span class="badge badge-inline badge-danger">
                                        {{ $req->quantity }}
                                    </span>
                                </td>

                                <!-- SELLERS -->
                                <td>
                                    @php
                                        $sellers = \App\Models\User::whereIn('id', $req->seller_ids ?? [])->pluck(
                                            'name',
                                        );
                                    @endphp

                                    @foreach ($sellers as $name)
                                        <span class="badge badge-inline badge-warning mr-1 mb-1">
                                            {{ $name }}
                                        </span>
                                    @endforeach
                                </td>

                                <!-- LOCATION -->
                                <td>
                                    <a href="https://maps.google.com/?q={{ $req->lat }},{{ $req->lng }}"
                                        target="_blank" class="btn btn-sm btn-outline-info">
                                        📍 View Map
                                    </a>
                                </td>

                                <!-- DATE -->
                                <td>
                                    <small>
                                        {{ $req->created_at->format('d M Y') }} <br>
                                        {{ $req->created_at->format('h:i A') }}
                                    </small>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    No Out of Stock Requests Found
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

                <div class="mt-3">
                    {{ $requests->links() }}
                </div>

            </div>

        </div>

    @endsection


    @section('script')
        <script>
            function sort_requests() {
                $('#sort_requests').submit();
            }
        </script>
    @endsection
