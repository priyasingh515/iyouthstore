@extends('auth.layouts.authentication')


@section('css')

<!-- CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


@endsection

@section('content')
    <!-- aiz-main-wrapper -->
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
                                                    class="fs-12 fw-700 text-soft-dark">{{ translate('Your Name') }} <span class="text-danger">*</span> </label>
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
                                                <label>{{ translate('Your Email') }} <span class="text-danger">*</span></label>
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
                                                <label>{{ translate('Your Phone') }} <span class="text-danger">*</span></label>
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
                                                <label class="form-label">Select State <span class="text-danger">*</span></label>

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
                                                <label class="form-label">Select District <span class="text-danger">*</span></label>

                                                <select required class="form-control district" name="district"
                                                    onchange="selectBilaspurArea()" id="">
                                                    <option disabled selected>Select District</option>
                                                    @foreach ($districts as $district)
                                                        <option value="{{ $district->name }}">{{ $district->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('district'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('district') }}</strong>
                                                    </span>
                                                @endif
                                            </div>


                                            {{-- block --}}
                                            <div class="form-group">
                                                <label class="form-label">Select Block <span class="text-danger">*</span></label>

                                                <select required class="form-control block" name="block" id="">

                                                    <option selected disabled>Select Block</option>

                                                </select>
                                                @if ($errors->has('block'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('block') }}</strong>
                                                    </span>
                                                @endif
                                            </div>


                                            {{-- sub district  --}}
                                            <div class="form-group">
                                                <label class="form-label">Select Sub District <span class="text-danger">*</span></label>

                                                <select required class="form-control sub_district" name="sub_district" id="">
                                                    <option selected disabled>Select Sub District</option>

                                                </select>
                                                @if ($errors->has('sub_district'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('sub_district') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- enter village/city name optional --}}
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
                                                    class="fs-12 fw-700 text-soft-dark">{{ translate('Password') }} <span class="text-danger">*</span></label>
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
                                                    class="fs-12 fw-700 text-soft-dark">{{ translate('Confirm Password') }} <span class="text-danger">*</span></label>
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
    $('.state').select2({
        placeholder: "Search State...",
        allowClear: true
    });

       $('.district').select2({
        placeholder: "Search District...",
        allowClear: true
    });

       $('.block').select2({
        placeholder: "Search Block...",
        allowClear: true
    });
    
       $('.sub_district').select2({
        placeholder: "Search Sub District...",
        allowClear: true
    });
});
</script>
    <script>
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                },
                function(error) {
                    alert('Location allow karna zaruri hai seller registration ke liye');
                }
            );
        } else {
            alert("Geolocation is not supported by this browser.");
        }


        function selectBilaspurArea() {
            var districtSelect = document.querySelector('select[name="district"]');
            var blockSelect = document.querySelector('select[name="block"]');
            var subDistrictSelect = document.querySelector('select[name="sub_district"]');
            console.log('working')

            if (districtSelect.value === 'Bilaspur') {
                blockSelect.innerHTML = `
                    <option value="">Select Block</option>
                      <option value="बिल्हा">बिल्हा (Bilha)</option>
                                                    <option value="कोटा">कोटा (Kota)</option>
                                                    <option value="तखतपुर">तखतपुर (Takhatpur)</option>
                                                    <option value="मस्तूरी">मस्तूरी (Masturi) </option>

                `;

                subDistrictSelect.innerHTML = `
                   <option value="">Select Sub District</option>
     <option value="बिलासपुर">बिलासपुर (Bilaspur)</option>
                                                    <option value="बिल्हा">बिल्हा (Bilha)</option>
                                                    <option value="मस्तूरी">मस्तूरी (Masturi)</option>
                                                    <option value="तखतपुर">तखतपुर (Takhatpur)</option>
                                                    <option value="कोटा">कोटा (Kota)</option>
                                                    <option value="बेलगहना">बेलगहना (Belgahna)</option>
                                                    <option value="रतनपुर">रतनपुर (Ratanpur)</option>
                                                    <option value="सकरी">सकरी (Sakari)</option>
                                                    <option value="सीपत">सीपत (Sipat)</option>
                                                    <option value="बोदरी">बोदरी (Bodri)</option>
                                                    <option value="बेल्टारा">बेल्टारा (Beltara)</option>

                `;
            } else {
                blockSelect.innerHTML = `
                    <option value="">Select Block</option>
                `;
                subDistrictSelect.innerHTML = `
                    <option value="">Select Sub District</option>
                `;

            }



        }

        function getCities() {

            var district = document.querySelector('select[name="district"]').value;

            fetch("{{ route('getCities') }}?district=" + district)
                .then(response => response.json())
                .then(data => {

                    var citySelect = document.querySelector('select[name="city"]');
                    citySelect.innerHTML = '<option value="">Select City</option>';

                    data.forEach(function(city) {
                        var option = document.createElement('option');
                        option.value = city.city;
                        option.textContent = city.city;
                        citySelect.appendChild(option);
                    });

                })
                .catch(error => console.error(error));
        }
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
