@if (isset($coming_soon_products) && count($coming_soon_products) > 0)

    <section class="mb-2 mb-md-3 mt-2 mt-md-3">

        <div class="container">

            <div class="row no-gutters">

                <div class="col-xl-12" style="background-color: #f8f9fa;">

                    {{-- Heading --}}
                    <div class="d-flex flex-wrap align-items-baseline justify-content-between px-4 px-xl-5 pt-4">

                        <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0 text-dark">
                            {{ translate('Coming Soon Product') }}
                        </h3>

                    </div>


                    {{-- Products --}}
                    <div class="c-scrollbar-light overflow-hidden px-4 px-md-5 pb-3 pt-3 pt-md-3 pb-md-5">

                        <div class="h-100 d-flex flex-column justify-content-center">

                            <div class="coming-soon aiz-carousel" data-items="3" data-xxl-items="3" data-xl-items="3"
                                data-lg-items="3" data-md-items="3" data-sm-items="3" data-xs-items="2"
                                data-arrows="true" data-dots="false" data-autoplay="true" data-infinite="true">

                                @foreach ($coming_soon_products as $product)
                                    <div class="carousel-box h-100 px-3 px-lg-0">

                                        {{-- Image --}}
                                        <div class="img h-80px w-80px rounded-content overflow-hidden mx-auto">

                                            <img class="lazyload img-fit m-auto has-transition"
                                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                data-src="{{ get_image($product->thumbnail) }}"
                                                alt="{{ $product->getTranslation('name') }}"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">

                                        </div>


                                        {{-- Product Name --}}
                                        <div class="fs-14 mt-3 text-center">

                                            <span class="d-block text-dark fw-700">
                                                {{ $product->getTranslation('name') }}
                                            </span>

                                            <span class="d-block text-warning fw-700 mt-1">
                                                {{ translate('Coming Soon') }}
                                            </span>

                                        </div>

                                    </div>
                                @endforeach

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

@endif
