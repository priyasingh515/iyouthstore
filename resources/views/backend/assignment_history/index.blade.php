@extends('backend.layouts.app')

@section('content')
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
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
                        <th>#</th>
                        <th>Seller Name</th>
                        <th>Shop ID</th>
                        <th>History</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($sellers as $key => $seller)
                        <tr>
                            <td>{{ $key + 1 }}</td>

                            <td>{{ $seller->seller_name }}</td>

                            <td>{{ $seller->shop_id }}</td>

                            <td>
                                <a href="{{ route('assignment.history.show', $seller->seller_id) }}"
                                    class="btn btn-info btn-sm">
                                    View History
                                </a>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center">
                                No sellers found
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
            {{ $sellers->links() }}
        </div>
    </div>
@endsection
