@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar mt-2 mb-3">
    <h5 class="h6">Import Sellers</h5>
</div>

{{-- STEP 1 --}}
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">Instructions</h5>
    </div>

    <div class="card-body">

        <div class="alert alert-info">
            <strong>Step 1:</strong>
            <p>1. Download the demo file and fill all required fields.</p>
            <p>2. Do not change column order.</p>
            <p>3. State, District, and Janpat Panchayat must match existing data in system.</p>
            <p>4. Gram Panchayat will be stored directly.</p>
            <p>5. Mobile number will be used as password.</p>
        </div>

        <a href="{{ static_asset('download/iYouth_Store_Parteners.xlsx') }}" class="btn btn-info mb-3">
            Download Demo CSV
        </a>

        <div class="alert alert-info mt-3">
            <strong>Step 2:</strong>
            <p>1. State → must exist in system (e.g., Chhattisgarh).</p>
            <p>2. District → must belong to selected state.</p>
            <p>3. Janpat Panchayat → must exist in SubDistrict table.</p>
            <p>4. If any mismatch found, row will be skipped.</p>
        </div>

    </div>
</div>

{{-- UPLOAD SECTION --}}
<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6"><strong>Upload Seller File</strong></h5>
    </div>

    <div class="card-body">

        {{-- Skip Reasons --}}
        @if (session('bulk_import_skip_reasons'))
            <div class="alert alert-warning">
                <strong>Skipped rows:</strong>
                <ul class="mb-0 mt-2">
                    @foreach (session('bulk_import_skip_reasons') as $reason)
                        <li>{{ $reason }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Upload Form --}}
        <form action="{{ route('sellers.bulk_store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Select File</label>
                <input type="file" name="file" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">
                Upload File
            </button>
        </form>

    </div>
</div>

@endsection