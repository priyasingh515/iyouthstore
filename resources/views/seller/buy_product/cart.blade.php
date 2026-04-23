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

                                                {{-- <input type="number" name="qty" id="cart-qty-{{ $cart->id }}"
                                                    value="{{ $cart->quantity }}" min="1"
                                                    class="form-control text-center mx-2" style="width:70px;"> --}}

                                                @php
                                                    $product = $cart->product;
                                                    // $stock = $product->stocks->sum('qty');
                                                    // $limit = $product->seller_purchase_limit;
                                                    $limit = $product->seller_purchase_limit ?? 9999;
                                                    $maxAllowed = $limit;
                                                    // $maxAllowed = $limit ? min($limit, $stock) : $stock;
                                                    $minLimit = $product->seller_min_purchase_limit ?? 1;
                                                @endphp

                                                <input type="number" name="qty" id="cart-qty-{{ $cart->id }}"
                                                    value="{{ $cart->quantity }}" min="{{ $minLimit }}"
                                                    max="{{ $maxAllowed }}" class="form-control text-center mx-2"
                                                    style="width:70px;">

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
                    <button type="button" class="btn btn-success" id="checkout-btn">
                        Proceed To Checkout
                    </button>

                </div>
            @endif

        </div>
    </div>

@endsection

@section('modal')
    <div id="cart-confirm-modal" class="modal fade">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1 fs-14" id="cart-confirm-message">Are you sure?</p>
                    <button type="button" class="btn btn-secondary rounded-0 mt-2" data-dismiss="modal">Cancel</button>
                    <button type="button" id="cart-confirm-submit" class="btn btn-primary rounded-0 mt-2">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div id="cart-success-modal" class="modal fade">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">Success</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1 fs-14" id="cart-success-message">Order placed successfully</p>
                    <button type="button" id="cart-success-ok" class="btn btn-primary rounded-0 mt-2">OK</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        let pendingAction = null;
        let checkoutRedirectUrl = "{{ route('seller.my-purchases') }}";
        let shouldRedirectAfterSuccessClose = false;
        let isCheckoutRedirecting = false;

        function openCartConfirmModal(message, actionCallback) {
            $('#cart-confirm-message').text(message);
            pendingAction = actionCallback;
            $('#cart-confirm-modal').modal('show');
        }

        function openCartSuccessModal(message) {
            $('#cart-success-message').text(message);
            shouldRedirectAfterSuccessClose = true;
            $('#cart-success-modal').modal('show');
        }

        function redirectToMyPurchases() {
            if (isCheckoutRedirecting) return;
            isCheckoutRedirecting = true;
            window.location.href = checkoutRedirectUrl;
        }

        $(document).on('click', '#cart-confirm-submit', function() {
            let action = pendingAction;
            pendingAction = null;
            $('#cart-confirm-modal').modal('hide');

            if (typeof action === 'function') {
                action();
            }
        });

        $('#cart-confirm-modal').on('hidden.bs.modal', function() {
            pendingAction = null;
        });

        $(document).on('click', '#cart-success-ok', function() {
            shouldRedirectAfterSuccessClose = false;
            redirectToMyPurchases();
        });

        $('#cart-success-modal').on('hidden.bs.modal', function() {
            if (shouldRedirectAfterSuccessClose) {
                shouldRedirectAfterSuccessClose = false;
                redirectToMyPurchases();
            }
        });

        // $(document).on('click', '.qty-plus', function() {

        //     let id = $(this).data('id');
        //     let input = $('#cart-qty-' + id);
        //     input.val(parseInt(input.val()) + 1);

        // });

        $(document).on('click', '.qty-plus', function() {

            let id = $(this).data('id');
            let input = $('#cart-qty-' + id);

            let current = parseInt(input.val()) || 1;
            let max = parseInt(input.attr('max')) || 9999;

            if (current >= max) {
                AIZ.plugins.notify('warning', 'Maximum purchase limit is ' + max);
                return;
            }

            input.val(current + 1);
        });

        $(document).on('click', '.qty-minus', function() {

            let id = $(this).data('id');
            let input = $('#cart-qty-' + id);

            let min = parseInt(input.attr('min')) || 1;
            let current = parseInt(input.val()) || 1;

            if (current <= min) {
                AIZ.plugins.notify('warning', 'Minimum purchase limit is ' + min);
                return;
            }

            input.val(current - 1);

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
            let input = $('#cart-qty-' + cartId);

            let min = parseInt(input.attr('min')) || 1;
            let max = parseInt(input.attr('max')) || 9999;
            let qty = parseInt(input.val());

            // ✅ VALIDATION
            if (qty < min) {
                AIZ.plugins.notify('danger', 'Minimum quantity is ' + min);
                return;
            }

            if (qty > max) {
                AIZ.plugins.notify('danger', 'Maximum quantity is ' + max);
                return;
            }

            // ✅ AJAX CALL
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
                        location.reload();
                    } else {
                        AIZ.plugins.notify('danger', res.message);
                    }

                }
            });

        });
        /* DELETE CART */
        $(document).on('click', '.delete-cart', function() {

            let cartId = $(this).data('id');

            openCartConfirmModal('Remove this item from cart?', function() {
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

        });
    </script>

    {{-- chcekout --}}


    <script>
        // $(document).on('click', '#checkout-btn', function() {

        //     openCartConfirmModal('Confirm place order?', function() {
        //         $.ajax({

        //             url: "{{ route('seller.cart.checkout') }}",

        //             type: "POST",

        //             data: {
        //                 _token: "{{ csrf_token() }}"
        //             },

        //             success: function(res) {

        //                 if (res.status) {

        //                     openCartSuccessModal(res.message || 'Order placed successfully');

        //                 } else {

        //                     AIZ.plugins.notify('danger', res.message);

        //                 }

        //             },

        //             error: function() {

        //                 AIZ.plugins.notify('danger', 'Something went wrong');

        //             }

        //         });
        //     });

        // });
    </script>

    <script>
        $(document).on('click', '#checkout-btn', function() {

            openCartConfirmModal('Confirm place order?', function() {

                $.ajax({
                    url: "{{ route('seller.cart.checkout') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },

                    success: function(res) {

                        if (res.status) {

                            // ✅ redirect with order_id
                            window.location.href =
                                "{{ route('seller.payment.page') }}?order_id=" + res.order_id;

                        } else {

                            AIZ.plugins.notify('danger', res.message);

                        }

                    },

                    error: function() {

                        AIZ.plugins.notify('danger', 'Something went wrong');

                    }

                });

            });

        });
    </script>
@endsection
