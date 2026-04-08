@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Edit Seller') }}</h5>
    </div>

    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Seller Information') }}</h5>
            </div>

            <div class="card-body">

                <form action="{{ route('sellers.update', $shop->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    {{-- Name --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">
                            Name <span class="text-danger">*</span>
                        </label>

                        <div class="col-sm-9">
                            <input type="text" name="name" class="form-control" value="{{ $shop->user->name }}"
                                required>
                        </div>
                    </div>


                    {{-- Email --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">
                            Email <span class="text-danger">*</span>
                        </label>

                        <div class="col-sm-9">
                            <input type="email" name="email" class="form-control" value="{{ $shop->user->email }}"
                                required>
                        </div>
                    </div>


                    {{-- State --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">
                            State
                        </label>

                        <div class="col-sm-9">
                            <select class="form-control" name="state">

                                <option value="chhattisgarh" {{ $shop->user->state == 'chhattisgarh' ? 'selected' : '' }}>
                                    Chhattisgarh
                                </option>

                            </select>
                        </div>
                    </div>



                    {{-- District --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">
                            District
                        </label>

                        <div class="col-sm-9">

                            <select class="form-control district-select" name="district_id">

                                <option value="">Select District</option>

                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}"
                                        {{ $shop->user->district == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}
                                    </option>
                                @endforeach

                            </select>

                        </div>
                    </div>



                    {{-- Block --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">
                            Block
                        </label>

                        <div class="col-sm-9">

                            <select class="form-control block-select" name="block_id">

                                <option value="">Select Block</option>

                                @foreach ($blocks as $block)
                                    <option value="{{ $block->id }}" data-district="{{ $block->district_id }}"
                                        {{ $shop->user->block == $block->id ? 'selected' : '' }}>

                                        {{ $block->name }}

                                    </option>
                                @endforeach

                            </select>

                        </div>
                    </div>



                    {{-- SubDistrict --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">
                            SubDistrict
                        </label>

                        <div class="col-sm-9">

                            <select class="form-control subdistrict-select" name="sub_district_id">

                                <option value="">Select SubDistrict</option>

                                @foreach ($subDistricts as $sub)
                                    <option value="{{ $sub->id }}" data-block="{{ $sub->block_id }}"
                                        {{ $shop->user->sub_district == $sub->id ? 'selected' : '' }}>

                                        {{ $sub->name }}

                                    </option>
                                @endforeach

                            </select>

                        </div>
                    </div>



                    {{-- City --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">
                            City / Village
                        </label>

                        <div class="col-sm-9">

                            <input type="text" name="city" class="form-control" value="{{ $shop->user->city }}">

                        </div>
                    </div>



                    {{-- Submit --}}
                    <div class="form-group mb-0 text-right">

                        <button type="submit" class="btn btn-primary">

                            Update Seller

                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {

            let selectedDistrict = $('.district-select').val();
            let selectedBlock = $('.block-select').val();
            let selectedSub = $('.subdistrict-select').val();

     
            $('.block-select option').hide();
            $('.block-select option:first').show();

            $('.block-select option').each(function() {
                if ($(this).data('district') == selectedDistrict) {
                    $(this).show();
                }
            });

            $('.block-select').val(selectedBlock);

            $('.subdistrict-select option').hide();
            $('.subdistrict-select option:first').show();

            $('.subdistrict-select option').each(function() {
                if ($(this).data('block') == selectedBlock) {
                    $(this).show();
                }
            });

            $('.subdistrict-select').val(selectedSub);


            $('.district-select').on('change', function() {

                let district = $(this).val();

                $('.block-select').val('');
                $('.subdistrict-select').val('');

                $('.block-select option').hide();
                $('.block-select option:first').show();

                $('.block-select option').each(function() {
                    if ($(this).data('district') == district) {
                        $(this).show();
                    }
                });

            });

            $('.block-select').on('change', function() {

                let block = $(this).val();

                $('.subdistrict-select').val('');

                $('.subdistrict-select option').hide();
                $('.subdistrict-select option:first').show();

                $('.subdistrict-select option').each(function() {
                    if ($(this).data('block') == block) {
                        $(this).show();
                    }
                });

            });

        });
    </script>
@endsection
