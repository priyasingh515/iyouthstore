<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ translate('INVOICE') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">

    <style media="all">
        @page {
            margin: 0;
            padding: 0;
        }

        body {
            font-size: 0.875rem;
            font-family: '<?php echo $font_family; ?>';
            font-weight: normal;
            direction: <?php echo $direction; ?>;
            text-align: <?php echo $text_align; ?>;
            padding: 0;
            margin: 0;
        }

        .gry-color *,
        .gry-color {
            color: #000;
        }

        table {
            width: 100%;
        }

        table th {
            font-weight: normal;
        }

        table.padding th {
            padding: .25rem .7rem;
        }

        table.padding td {
            padding: .25rem .7rem;
        }

        table.sm-padding td {
            padding: .1rem .7rem;
        }

        .border-bottom td,
        .border-bottom th {
            border-bottom: 1px solid #eceff4;
        }

        .text-left {
            text-align: <?php echo $text_align; ?>;
        }

        .text-right {
            text-align: <?php echo $not_text_align; ?>;
        }
    </style>

</head>

<body>

    <div>

        @php

            $logo = get_setting('header_logo');

            /* default admin info */

            $seller_name = get_setting('site_name');
            $shop_id = 'ADMIN';
            $email = get_setting('contact_email') ?? '-';
            $phone = get_setting('contact_phone') ?? '-';

            /* detect seller */

            if (isset($order->orderDetails[0])) {
                $seller_id = $order->orderDetails[0]->seller_id;

                if ($seller_id != 0) {
                    $seller = \App\Models\User::find($seller_id);

                    if ($seller) {
                        $seller_name = $seller->name;
                        $email = $seller->email ?? '-';
                        $phone = $seller->phone ?? '-';

                        $shop = \App\Models\Shop::where('user_id', $seller_id)->first();

                        if ($shop) {
                            $shop_id = $shop->shop_id;
                            $seller_name = $shop->name;
                        }
                    }
                }
            }

        @endphp


        <div style="background:#eceff4;padding:1rem;">

            <table>

                <tr>

                    <td>

                        @if ($logo != null)
                            <img src="{{ uploaded_asset($logo) }}" height="30">
                        @else
                            <img src="{{ static_asset('assets/img/logo.png') }}" height="30">
                        @endif

                    </td>

                    <td style="font-size:1.5rem;" class="text-right strong">

                        {{ translate('INVOICE') }}

                    </td>

                </tr>

            </table>


            <table>

                <tr>

                    <td style="font-size: 1rem;" class="strong">
                        {{ $seller_name }}
                        <br>
                        <small>Shop ID : {{ $shop_id }}</small>
                    </td>

                    <td class="text-right"></td>

                </tr>


                <tr>

                    <td class="gry-color small">

                        {{ get_setting('contact_address') }}

                    </td>

                    <td class="text-right"></td>

                </tr>


                <tr>

                    <td class="gry-color small">

                        {{ translate('Email') }}: {{ $email }}

                    </td>

                    <td class="text-right small">

                        <span class="gry-color small">{{ translate('Order ID') }}:</span>

                        <span class="strong">{{ $order->code }}</span>

                    </td>

                </tr>


                <tr>

                    <td class="gry-color small">

                        {{ translate('Phone') }}: {{ $phone }}

                    </td>

                    <td class="text-right small">

                        <span class="gry-color small">{{ translate('Order Date') }}:</span>

                        <span class="strong">{{ date('d-m-Y', $order->date) }}</span>

                    </td>

                </tr>


                <tr>

                    <td class="gry-color small"></td>

                    <td class="text-right small">

                        <span class="gry-color small">

                            {{ translate('Payment method') }}:

                        </span>

                        <span class="strong">

                            {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}

                        </span>

                    </td>

                </tr>

            </table>

        </div>



        <div style="padding:1rem;padding-bottom:0">

            <table>

                {{-- @php
                    $shipping_address = json_decode($order->shipping_address);
                @endphp --}}

                @php
                    $shipping_address = json_decode($order->shipping_address) ?? (object) [];
                    $customer = $order->user;
                    $is_seller_panel_purchase = $order->order_from === 'seller_panel';
                    $seller_buyer = $is_seller_panel_purchase ? $order->user : null;
                    $seller_buyer_address = $is_seller_panel_purchase ? $seller_buyer?->addresses()->where('set_default', 1)->first() : null;

                    $bill_to_name = $shipping_address->name ?? '-';
                    $bill_to_email = $shipping_address->email ?? '-';
                    $bill_to_phone = $shipping_address->phone ?? '-';
                    $bill_to_address = collect([
                        $shipping_address->address ?? null,
                        $shipping_address->city ?? null,
                        isset($shipping_address->state) ? $shipping_address->state : null,
                        $shipping_address->postal_code ?? null,
                        $shipping_address->country ?? null,
                    ])->filter(fn ($value) => filled($value))->implode(', ');

                    if ($is_seller_panel_purchase) {
                        $bill_to_name = $seller_buyer->name ?? '-';
                        $bill_to_email = $seller_buyer->email ?? '-';
                        $bill_to_phone = $seller_buyer->phone ?? '-';
                        $bill_to_address = collect([
                            $seller_buyer_address->address ?? $seller_buyer->address ?? null,
                            $seller_buyer_address->city ?? $seller_buyer->city ?? null,
                            $seller_buyer_address->state ?? null,
                            $seller_buyer_address->postal_code ?? $seller_buyer->postal_code ?? null,
                            $seller_buyer_address->country ?? $seller_buyer->country ?? null,
                        ])->filter(fn ($value) => filled($value))->implode(', ');
                    }
                @endphp


                <tr>
                    <td class="strong small gry-color">
                        {{ translate('Bill to') }}:
                    </td>
                </tr>


                <tr>
                    <td class="strong">
                        {{ $bill_to_name }}
                    </td>
                </tr>


                <tr>

                    <td class="gry-color small">
                        {{ $bill_to_address ?: '-' }}
                    </td>

                </tr>


                <tr>
                    <td class="gry-color small">
                        {{ translate('Email') }}: {{ $bill_to_email }}
                    </td>
                </tr>


                <tr>
                    <td class="gry-color small">
                        {{ translate('Phone') }}: {{ $bill_to_phone }}
                    </td>
                </tr>

            </table>

        </div>



        <div style="padding:1rem;">

            <table class="padding text-left small border-bottom">

                <thead>

                    <tr class="gry-color" style="background:#eceff4;">

                        <th width="35%">{{ translate('Product Name') }}</th>

                        <th width="15%">{{ translate('Delivery Type') }}</th>

                        <th width="10%">{{ translate('Qty') }}</th>

                        <th width="15%">{{ translate('Unit Price') }}</th>

                        <th width="10%">{{ translate('Tax') }}</th>

                        <th width="15%" class="text-right">{{ translate('Total') }}</th>

                    </tr>

                </thead>


                <tbody class="strong">

                    @foreach ($order->orderDetails as $orderDetail)

                        @if ($orderDetail->product != null)
                            <tr>

                                <td>

                                    {{ $orderDetail->product->name }}

                                    @if ($orderDetail->variation != null)
                                        ({{ $orderDetail->variation }})
                                    @endif

                                </td>


                                <td>

                                    @if ($order->shipping_type == 'home_delivery')
                                        {{ translate('Home Delivery') }}
                                    @elseif ($order->shipping_type == 'pickup_point')
                                        @if ($order->pickup_point != null)
                                            {{ $order->pickup_point->getTranslation('name') }}
                                            ({{ translate('Pickup Point') }})
                                        @else
                                            {{ translate('Pickup Point') }}
                                        @endif
                                    @elseif ($order->shipping_type == 'carrier')
                                        @if ($order->carrier != null)
                                            {{ $order->carrier->name }} ({{ translate('Carrier') }})

                                            <br>

                                            {{ translate('Transit Time') . ' - ' . $order->carrier->transit_time }}
                                        @else
                                            {{ translate('Carrier') }}
                                        @endif
                                    @else
                                        {{ translate('Home Delivery') }}
                                    @endif

                                </td>


                                <td>{{ $orderDetail->quantity }}</td>

                                <td class="currency">
                                    {{ single_price($orderDetail->price / $orderDetail->quantity) }}
                                </td>

                                <td class="currency">
                                    {{ single_price($orderDetail->tax / $orderDetail->quantity) }}
                                </td>

                                <td class="text-right currency">
                                    {{ single_price($orderDetail->price + $orderDetail->tax) }}
                                </td>

                            </tr>
                        @endif

                    @endforeach

                </tbody>

            </table>

        </div>



        <div style="padding:0 1.5rem;">

            <table class="text-right sm-padding small strong">

                <thead>
                    <tr>
                        <th width="60%"></th>
                        <th width="40%"></th>
                    </tr>
                </thead>


                <tbody>

                    <tr>

                        <td class="text-left">

                            {{-- QR CODE / BARCODE 
@php
$removedXML = '<?xml version="1.0" encoding="UTF-8"?>';
@endphp

{!! str_replace($removedXML,"",QrCode::size(100)->generate($order->code)) !!}
--}}

                        </td>


                        <td>

                            <table class="text-right sm-padding small strong">

                                <tbody>

                                    <tr>
                                        <th class="gry-color text-left">
                                            {{ translate('Sub Total') }}
                                        </th>

                                        <td class="currency">
                                            {{ single_price($order->orderDetails->sum('price')) }}
                                        </td>
                                    </tr>


                                    <tr>
                                        <th class="gry-color text-left">
                                            {{ translate('Shipping Cost') }}
                                        </th>

                                        <td class="currency">
                                            {{ single_price($order->orderDetails->sum('shipping_cost')) }}
                                        </td>
                                    </tr>


                                    <tr class="border-bottom">
                                        <th class="gry-color text-left">
                                            {{ translate('Total Tax') }}
                                        </th>

                                        <td class="currency">
                                            {{ single_price($order->orderDetails->sum('tax')) }}
                                        </td>
                                    </tr>


                                    <tr class="border-bottom">
                                        <th class="gry-color text-left">
                                            {{ translate('Coupon Discount') }}
                                        </th>

                                        <td class="currency">
                                            {{ single_price($order->coupon_discount) }}
                                        </td>
                                    </tr>


                                    <tr>
                                        <th class="text-left strong">
                                            {{ translate('Grand Total') }}
                                        </th>

                                        <td class="currency">
                                            {{ single_price($order->grand_total) }}
                                        </td>
                                    </tr>

                                </tbody>

                            </table>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</body>

</html>
