@extends('backend.layouts.app')

@section('content')
    <div class="card">

        {{-- Header --}}
        <div class="card-header d-flex justify-content-between align-items-center">

            <h5 class="mb-0">
                Inactive Sellers
            </h5>

            <span>
                Total: {{ count($sellers) }}
            </span>

        </div>


        <div class="card-body">

            {{-- Filter --}}
            <form method="GET" action="{{ route('admin.inactive.products') }}" class="mb-4">

                <div class="row align-items-end">

                    <div class="col-md-3">

                        <label class="small font-weight-bold">
                            From Date
                        </label>

                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">

                    </div>


                    <div class="col-md-3">

                        <label class="small font-weight-bold">
                            To Date
                        </label>

                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">

                    </div>


                    <div class="col-md-6">

                        <button type="submit" class="btn btn-primary mr-2">

                            <i class="las la-filter"></i>
                            Apply

                        </button>


                        <a href="{{ route('admin.inactive.products') }}" class="btn btn-outline-secondary">

                            Reset

                        </a>

                    </div>

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
