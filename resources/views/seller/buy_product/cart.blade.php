@extends('seller.layouts.app')

@section('panel_content')

    <div class="aiz-titlebar mt-2 mb-4">
        <h1 class="h3">My Cart</h1>
    </div>

    <div class="card">
        <div class="card-body">

            @if ($carts->count() == 0)
                <div class="text-center p-5">
                    <h5>Your Cart Is Empty</h5>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">

                        <thead>
                            <tr>
                                <th>Product</th>
                                <th width="120">Price</th>
                                <th width="180">Quantity</th>
                                <th width="120">Subtotal</th>
                                <th width="80">Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @php $total = 0; @endphp

                            @foreach ($carts as $cart)
                                @php
                                    $product = $cart->product;
                                    $subtotal = $cart->price * $cart->quantity;
                                    $total += $subtotal;
                                @endphp

                                <tr>

                                    <td>
                                        <div class="d-flex align-items-center">

                                            <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                style="width:60px;height:60px;object-fit:cover;border-radius:8px;"
                                                class="mr-3">

                                            <div>
                                                <strong>{{ $product->getTranslation('name') }}</strong>
                                            </div>

                                        </div>
                                    </td>

                                    <td>
                                        ₹ {{ number_format($cart->price, 2) }}
                                    </td>

                                    <td>

                                        <form action="" method="POST">
                                            @csrf

                                            <div class="d-flex align-items-center">

                                                <button type="button" class="btn btn-light border qty-minus"
                                                    data-id="{{ $cart->id }}">
                                                    -
                                                </button>

                                                <input type="number" name="qty" id="cart-qty-{{ $cart->id }}"
                                                    value="{{ $cart->quantity }}" min="1"
                                                    class="form-control text-center mx-2" style="width:70px;">

                                                <button type="button" class="btn btn-light border qty-plus"
                                                    data-id="{{ $cart->id }}">
                                                    +
                                                </button>
                                                {{-- 
                                                <button class="btn btn-sm btn-primary ml-2">
                                                    Update
                                                </button> --}}

                                                <button type="button" class="btn btn-sm btn-primary update-cart"
                                                    data-id="{{ $cart->id }}">
                                                    Update
                                                </button>


                                            </div>

                                        </form>

                                    </td>

                                    <td>
                                        ₹ {{ number_format($subtotal, 2) }}
                                    </td>

                                    <td>

                                        {{-- <form action="" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-danger">
                                                Remove
                                            </button>
                                        </form> --}}

                                        <button type="button" class="btn btn-sm btn-danger delete-cart"
                                            data-id="{{ $cart->id }}">
                                            Remove
                                        </button>


                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>

                <hr>

                <div class="d-flex justify-content-end">
                    <h4>Total : ₹ {{ number_format($total, 2) }}</h4>
                </div>

                <div class="text-right mt-3">
                    <a href="#" class="btn btn-success">
                        Proceed To Checkout
                    </a>
                </div>
            @endif

        </div>
    </div>

@endsection


@section('script')
    <script>
        $(document).on('click', '.qty-plus', function() {

            let id = $(this).data('id');
            let input = $('#cart-qty-' + id);
            input.val(parseInt(input.val()) + 1);

        });

        $(document).on('click', '.qty-minus', function() {

            let id = $(this).data('id');
            let input = $('#cart-qty-' + id);

            if (input.val() > 1) {
                input.val(parseInt(input.val()) - 1);
            }

        });

        @if (session('success'))
            AIZ.plugins.notify('success', "{{ session('success') }}");
        @endif

        @if (session('error'))
            AIZ.plugins.notify('danger', "{{ session('error') }}");
        @endif
    </script>






    <script>
        //update and delete


        /* UPDATE CART */
        $(document).on('click', '.update-cart', function() {

            let cartId = $(this).data('id');
            let qty = $('#cart-qty-' + cartId).val();

            $.ajax({
                url: "{{ route('seller.cart.update') }}",
                type: "POST",
                data: {
                    cart_id: cartId,
                    qty: qty,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {

                    if (res.status) {
                        AIZ.plugins.notify('success', res.message);
                        location.reload(); // refresh totals
                    } else {
                        AIZ.plugins.notify('danger', res.message);
                    }

                }
            });

        });


        /* DELETE CART */
        $(document).on('click', '.delete-cart', function() {

            if (!confirm('Remove this item?')) return;

            let cartId = $(this).data('id');

            $.ajax({
                url: "{{ route('seller.cart.delete') }}",
                type: "POST",
                data: {
                    cart_id: cartId,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {

                    if (res.status) {
                        AIZ.plugins.notify('success', res.message);
                        location.reload();
                    } else {
                        AIZ.plugins.notify('danger', res.message);
                    }

                }
            });

        });
    </script>
@endsection
