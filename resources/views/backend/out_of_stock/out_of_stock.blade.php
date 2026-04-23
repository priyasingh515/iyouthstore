@extends('backend.layouts.app')

@section('content')

<div class="card">

    <div class="card-header">
        <h5 class="mb-0">Out of Stock Requests</h5>
    </div>

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
                                $sellers = \App\Models\User::whereIn('id', $req->seller_ids ?? [])->pluck('name');
                            @endphp

                            @foreach($sellers as $name)
                                <span class="badge badge-inline badge-warning mr-1 mb-1">
                                    {{ $name }}
                                </span>
                            @endforeach
                        </td>

                        <!-- LOCATION -->
                        <td>
                            <a href="https://maps.google.com/?q={{ $req->lat }},{{ $req->lng }}" 
                               target="_blank" 
                               class="btn btn-sm btn-outline-info">
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