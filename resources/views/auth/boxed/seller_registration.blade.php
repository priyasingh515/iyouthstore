@extends('auth.layouts.authentication')


@section('css')
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection

@section('content')

    <div class="aiz-main-wrapper d-flex flex-column justify-content-md-center bg-white">
        <section class="bg-white overflow-hidden">
            <div class="row">
                <div class="col-xxl-6 col-xl-9 col-lg-10 col-md-7 mx-auto py-lg-4">
                    <div class="card shadow-none rounded-0 border-0">
                        <div class="row no-gutters">
                            <!-- Left Side Image-->
                            <div class="col-lg-6">
                                <img src="{{ uploaded_asset(get_setting('seller_register_page_image')) }}" alt=""
                                    class="img-fit h-100">
                            </div>

                            <!-- Right Side -->
                            <div class="col-lg-6 p-4 p-lg-5 d-flex flex-column justify-content-center border right-content"
                                style="height: auto;">
                                <!-- Site Icon -->
                                <div class="size-48px mb-3 mx-auto mx-lg-0">
                                    <img src="{{ uploaded_asset(get_setting('site_icon')) }}"
                                        alt="{{ translate('Site Icon') }}" class="img-fit h-100">
                                </div>

                                <!-- Titles -->
                                <div class="text-center text-lg-left">
                                    <h1 class="fs-20 fs-md-24 fw-700 text-primary" style="text-transform: uppercase;">
                                        {{ translate('Register your shop') }}</h1>

                                    <p>
                                        <span class="text-danger">*</span> Fields are required. Please fill all the required
                                        fields to register your shop. <br>
                                    </p>
                                </div>
                                <!-- Register form -->
                                <div class="pt-3 pt-lg-4">
                                    <div class="">
                                        <form id="reg-form" class="form-default" role="form"
                                            action="{{ route('shops.store') }}" method="POST">
                                            @csrf

                                            <div class="fs-15 fw-600 pb-2">{{ translate('Personal Info') }}</div>
                                            <!-- Name -->
                                            <div class="form-group">
                                                <label for="name"
                                                    class="fs-12 fw-700 text-soft-dark">{{ translate('Your Name') }} <span
                                                        class="text-danger">*</span> </label>
                                                <input type="text"
                                                    class="form-control rounded-0{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                    value="{{ old('name') }}" placeholder="{{ translate('Full Name') }}"
                                                    name="name" required>
                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label>{{ translate('Your Email') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="email"
                                                    class="form-control rounded-0{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                    value="{{ $email ?? old('email') }}"
                                                    placeholder="{{ translate('Email') }}" name="email" required
                                                    {{ $email ? 'readonly' : '' }}>
                                                @if ($errors->has('email'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label>{{ translate('Your Phone') }} <span
                                                        class="text-danger">*</span></label>
                                                <input type="tel"
                                                    class="form-control rounded-0{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                                    value="{{ $phone ?? old('phone') }}"
                                                    placeholder="{{ translate('Phone') }}" name="phone" required
                                                    {{ $phone ? 'readonly' : '' }}>
                                                @if ($errors->has('phone'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('phone') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Select State <span
                                                        class="text-danger">*</span></label>

                                                <select required class="form-control state" name="state" id="">
                                                    <option selected value="chhattisgarh">Chhattisgarh</option>
                                                </select>
                                                @if ($errors->has('state'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('state') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label>Select District <span class="text-danger">*</span></label>
                                                <select class="form-control district-select" name="district"
                                                    id="districtSelect" required>
                                                    <option value="">Select District</option>

                                                    @foreach ($districts as $district)
                                                        <option value="{{ $district->id }}">
                                                            {{ $district->name }}
                                                        </option>
                                                    @endforeach

                                                    <option value="other">Other (Type Manually)</option>
                                                </select>

                                                <input type="text" class="form-control mt-2 d-none" id="districtManual"
                                                    name="district_manual" placeholder="Enter District Name">
                                            </div>

                                            <div class="form-group">
                                                <label>Select Block <span class="text-danger">*</span></label>
                                                <select class="form-control block-select" name="block" id="blockSelect"
                                                    required>
                                                    <option value="">Select Block</option>

                                                    @foreach ($blocks as $block)
                                                        <option value="{{ $block->id }}"
                                                            data-district="{{ $block->district_id }}">
                                                            {{ $block->name }}
                                                        </option>
                                                    @endforeach

                                                    <option value="other">Other (Type Manually)</option>
                                                </select>

                                                <input type="text" class="form-control mt-2 d-none" id="blockManual"
                                                    name="block_manual" placeholder="Enter Block Name">
                                            </div>

                                            <div class="form-group">
                                                <label>Select Sub District <span class="text-danger">*</span></label>
                                                <select class="form-control subdistrict-select" name="sub_district"
                                                    id="subSelect" required>
                                                    <option value="">Select Sub District</option>

                                                    @foreach ($subDistricts as $sub)
                                                        <option value="{{ $sub->id }}"
                                                            data-block="{{ $sub->block_id }}">
                                                            {{ $sub->name }}
                                                        </option>
                                                    @endforeach

                                                    <option value="other">Other (Type Manually)</option>
                                                </select>

                                                <input type="text" class="form-control mt-2 d-none" id="subManual"
                                                    name="sub_district_manual" placeholder="Enter Sub District Name">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Enter City/Village</label>
                                                <input type="text" class="form-control" name="city"
                                                    value="{{ old('city') }}" placeholder="Enter City/Village">
                                                @if ($errors->has('city'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('city') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- password -->
                                            <div class="form-group mb-0">
                                                <label for="password"
                                                    class="fs-12 fw-700 text-soft-dark">{{ translate('Password') }} <span
                                                        class="text-danger">*</span></label>
                                                <div class="position-relative">
                                                    <input type="password"
                                                        class="form-control rounded-0{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                        placeholder="{{ translate('Password') }}" name="password"
                                                        required>
                                                    <i class="password-toggle las la-2x la-eye"></i>
                                                </div>
                                                <div class="text-right mt-1">
                                                    <span
                                                        class="fs-12 fw-400 text-gray-dark">{{ translate('Password must contain at least 6 digits') }}</span>
                                                </div>
                                                @if ($errors->has('password'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- password Confirm -->
                                            <div class="form-group">
                                                <label for="password_confirmation"
                                                    class="fs-12 fw-700 text-soft-dark">{{ translate('Confirm Password') }}
                                                    <span class="text-danger">*</span></label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control rounded-0"
                                                        placeholder="{{ translate('Confirm Password') }}"
                                                        name="password_confirmation" required>
                                                    <i class="password-toggle las la-2x la-eye"></i>
                                                </div>
                                            </div>


                                            {{-- <div class="fs-15 fw-600 py-2">{{ translate('Basic Info') }}</div> --}}



                                            <input type="hidden" name="latitude" id="latitude">
                                            <input type="hidden" name="longitude" id="longitude">


                                            <!-- Recaptcha -->
                                            @if (get_setting('google_recaptcha') == 1 && get_setting('recaptcha_seller_register') == 1)
                                                @if ($errors->has('g-recaptcha-response'))
                                                    <span
                                                        class="border invalid-feedback rounded p-2 mb-3 bg-danger text-white"
                                                        role="alert" style="display: block;">
                                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                    </span>
                                                @endif
                                            @endif

                                            <!-- Submit Button -->
                                            <div class="mb-4 mt-4">
                                                <button type="submit"
                                                    class="btn btn-primary btn-block fw-600 rounded-0">{{ translate('Register Your Shop') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- Log In -->
                                    <p class="fs-12 text-gray mb-0">
                                        {{ translate('Already have an account?') }}
                                        <a href="{{ route('seller.login') }}"
                                            class="ml-2 fs-14 fw-700 animate-underline-primary">{{ translate('Log In') }}</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Go Back -->
                    <div class="mt-3 mr-4 mr-md-0">
                        <a href="{{ url()->previous() }}"
                            class="ml-auto fs-14 fw-700 d-flex align-items-center text-primary"
                            style="max-width: fit-content;">
                            <i class="las la-arrow-left fs-20 mr-1"></i>
                            {{ translate('Back to Previous Page') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $('.block-select option').hide();
            $('.block-select option:first').show();
            $('.block-select option[value="other"]').show();

            $('.subdistrict-select option').hide();
            $('.subdistrict-select option:first').show();
            $('.subdistrict-select option[value="other"]').show();

            $('.district-select').on('change', function() {

                let district_id = $(this).val();

                $('.block-select').val('');
                $('.subdistrict-select').val('');

                $('.block-select option').hide();
                $('.block-select option:first').show();
                $('.block-select option[value="other"]').show();

                $('.subdistrict-select option').hide();
                $('.subdistrict-select option:first').show();
                $('.subdistrict-select option[value="other"]').show();

                $('.block-select option').each(function() {
                    if ($(this).data('district') == district_id) {
                        $(this).show();
                    }
                });

                toggleManualField(this, '#districtManual');
            });

            $('.block-select').on('change', function() {

                let block_id = $(this).val();

                $('.subdistrict-select').val('');

                $('.subdistrict-select option').hide();
                $('.subdistrict-select option:first').show();
                $('.subdistrict-select option[value="other"]').show();

                $('.subdistrict-select option').each(function() {
                    if ($(this).data('block') == block_id) {
                        $(this).show();
                    }
                });

                toggleManualField(this, '#blockManual');
            });

            $('.subdistrict-select').on('change', function() {
                toggleManualField(this, '#subManual');
            });

            function toggleManualField(selectElement, manualInputId) {
                if ($(selectElement).val() === 'other') {
                    $(manualInputId).removeClass('d-none').prop('required', true);
                } else {
                    $(manualInputId).addClass('d-none')
                        .prop('required', false)
                        .val('');
                }
            }

        });
    </script>
    @if (get_setting('google_recaptcha') == 1 && get_setting('recaptcha_seller_register') == 1)
        <script src="https://www.google.com/recaptcha/api.js?render={{ env('CAPTCHA_KEY') }}"></script>

        <script type="text/javascript">
            document.getElementById('reg-form').addEventListener('submit', function(e) {
                e.preventDefault();
                grecaptcha.ready(function() {
                    grecaptcha.execute(`{{ env('CAPTCHA_KEY') }}`, {
                        action: 'selller_registration'
                    }).then(function(token) {
                        var input = document.createElement('input');
                        input.setAttribute('type', 'hidden');
                        input.setAttribute('name', 'g-recaptcha-response');
                        input.setAttribute('value', token);
                        e.target.appendChild(input);

                        e.target.submit();
                    });
                });
            });
        </script>
    @endif
@endsection
