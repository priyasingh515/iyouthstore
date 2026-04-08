@extends('backend.layouts.app')

@section('content')
    @if (env('MAIL_USERNAME') == null && env('MAIL_PASSWORD') == null)
        <div class="alert alert-info d-flex align-items-center">
            {{ translate('You need to configure SMTP correctly to to add Seller.') }}
            <a class="alert-link ml-2" href="{{ route('smtp_settings.index') }}">{{ translate('Configure Now') }}</a>
        </div>
    @endif

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Add New Seller') }}</h5>
    </div>

    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Seller Information') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('sellers.store') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="name">
                            {{ translate('Name') }} <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif"
                                name="name" value="{{ old('name') }}" placeholder="{{ translate('Name') }}" required>
                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="email">
                            {{ translate('Email') }} <span class="text-danger">*</span>
                        </label>
                        <div class="col-sm-10">
                            <input type="email"
                                class="form-control rounded-0 @if ($errors->has('email')) is-invalid @endif"
                                value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>


                    {{-- State --}}
                    <div class="form-group row">

                        <label class="col-sm-3 col-from-label">
                            Select State <span class="text-danger">*</span>
                        </label>

                        <div class="col-sm-9">

                            <select class="form-control state-select" name="state" required>

                                <option value="">Select State</option>

                                <option value="chhattisgarh" selected>
                                    Chhattisgarh
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- District --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">Select District *</label>
                        <div class="col-sm-9">

                            <select class="form-control district-select" name="district_id" required>

                                <option value="">Select District</option>

                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}">
                                        {{ $district->name }}
                                    </option>
                                @endforeach

                            </select>

                        </div>
                    </div>


                    {{-- Block --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">Select Block *</label>

                        <div class="col-sm-9">

                            <select class="form-control block-select" name="block_id" required>

                                <option value="">Select Block</option>

                                @foreach ($blocks as $block)
                                    <option value="{{ $block->id }}" data-district="{{ $block->district_id }}">
                                        {{ $block->name }}
                                    </option>
                                @endforeach

                            </select>

                        </div>
                    </div>


                    {{-- SubDistrict --}}
                    <div class="form-group row">

                        <label class="col-sm-3 col-from-label">Select SubDistrict *</label>

                        <div class="col-sm-9">

                            <select class="form-control subdistrict-select" name="sub_district_id" required>

                                <option value="">Select SubDistrict</option>

                                @foreach ($subDistricts as $sub)
                                    <option value="{{ $sub->id }}" data-block="{{ $sub->block_id }}">
                                        {{ $sub->name }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                    </div>


                    {{-- City --}}
                    <div class="form-group row">

                        <label class="col-sm-3 col-from-label">City / Village *</label>

                        <div class="col-sm-9">

                            <input type="text" class="form-control" name="city" placeholder="Enter City/Village"
                                required>

                        </div>

                    </div>

                    {{-- Password --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">
                            Password <span class="text-danger">*</span>
                        </label>

                        <div class="col-sm-9">
                            <div class="position-relative">

                                <input type="password"
                                    class="form-control @if ($errors->has('password')) is-invalid @endif" name="password"
                                    placeholder="Enter Password" required>

                            </div>

                            <small class="text-muted">
                                Password must contain at least 6 characters
                            </small>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>


                    {{-- Confirm Password --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label">
                            Confirm Password <span class="text-danger">*</span>
                        </label>

                        <div class="col-sm-9">
                            <input type="password" class="form-control" name="password_confirmation"
                                placeholder="Confirm Password" required>
                        </div>
                    </div>


                    {{-- <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="shop_name">{{ translate('Shop Name') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control rounded-0 @if ($errors->has('shop_name')) is-invalid @endif" value="{{ old('shop_name') }}" placeholder="{{  translate('Shop Name') }}" name="shop_name">
                        @if ($errors->has('shop_name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('shop_name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div> --}}
                    {{-- <div class="form-group row">
                    <label class="col-sm-2 col-from-label" for="address">{{ translate('Address') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control rounded-0 @if ($errors->has('address')) is-invalid @endif" value="{{ old('address') }}" placeholder="{{  translate('Address') }}" name="address">
                        @if ($errors->has('address'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('address') }}</strong>
                            </span>
                        @endif
                    </div>
                </div> --}}
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection



@section('script')
    <script>
        $(document).ready(function() {

      
            $('.block-select option').hide();
            $('.block-select option:first').show();

       
            $('.subdistrict-select option').hide();
            $('.subdistrict-select option:first').show();

            
            $('.district-select').on('change', function() {

                let district = $(this).val();

                $('.block-select').val('');
                $('.subdistrict-select').val('');

                $('.block-select option').hide();
                $('.block-select option:first').show();

                $('.subdistrict-select option').hide();
                $('.subdistrict-select option:first').show();

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
