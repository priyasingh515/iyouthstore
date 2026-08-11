@extends('seller.layouts.app')

@section('panel_content')

<section class="mb-2 mb-md-3 mt-2 mt-md-3">
    <div class="container">

        <div class="row no-gutters">

            <div class="col-xl-12" style="background-color: #f8f9fa;">

                {{-- Heading --}}
                <div class="d-flex flex-wrap align-items-baseline
                            justify-content-between
                            px-4 px-xl-5 pt-4">

                    <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0 text-dark">
                        {{ translate('Coming Soon Product') }}
                    </h3>

                </div>

                {{-- Products --}}
                <div class="px-4 px-xl-5 pb-4 pt-4">

                    <div class="row">

                        @foreach ($coming_soon_products as $product)

                            {{-- Product --}}
                            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-4">

                                <div class="text-center h-100 mb-5">

                                    {{-- Image --}}
                                    <div class="img h-100px w-100px rounded-content
                                                overflow-hidden mx-auto">

                                        <img
                                            class="lazyload img-fit m-auto has-transition"
                                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                            data-src="{{ get_image($product->thumbnail) }}"
                                            alt="{{ $product->getTranslation('name') }}"
                                            onerror="this.onerror=null;
                                            this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">

                                    </div>

                                    {{-- Product Name --}}
                                    <div class="fs-14 mt-3">

                                        <span class="d-block text-dark fw-700">
                                            {{ $product->getTranslation('name') }}
                                        </span>

                                        {{-- Coming Soon --}}
                                        <span class="d-block text-warning fw-700 mt-1">
                                            {{ translate('Coming Soon') }}
                                        </span>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

@endsection