@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{ translate('Role Information') }}</h5>
</div>

<div class="col-lg-12 mx-auto">
    <div class="card">
        <div class="card-body p-0">

            <!-- Language Tabs -->
            <ul class="nav nav-tabs nav-fill language-bar">
                @foreach (get_all_active_language() as $key => $language)
                    <li class="nav-item">
                        <a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3"
                           href="{{ route('roles.edit', ['id'=>$role->id, 'lang'=> $language->code]) }}">
                            <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                            <span>{{ $language->name }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>

            <!-- Form -->
            <form class="p-4" action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <input type="hidden" name="lang" value="{{ $lang }}">

                <!-- Role Name -->
                <div class="form-group row">
                    <label class="col-md-3 col-from-label">
                        {{ translate('Name') }}
                        <i class="las la-language text-danger" title="{{ translate('Translatable') }}"></i>
                    </label>

                    <div class="col-md-9">
                        @php
                            $roleForTranslation = \App\Models\Role::find($role->id);
                        @endphp

                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ $roleForTranslation->getTranslation('name', $lang) }}"
                               required>
                    </div>
                </div>

                <!-- Permissions -->
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Permissions') }}</h5>
                </div>
                <br>

                @php
                    $permission_groups = \App\Models\Permission::all()->groupBy('section');

                    $hidden_sections = [
                        'club_point','affiliate_system','pos_system','notification',
                        'size_guide','system','product_warranty','setup_configurations','seller','cybersource_pg',
                    ];

                    $hidden_permissions = [
                        'show_seller_products',
                        'show_in_house_products',
                        'show_digital_products',
                        'add_digital_product',
                        'edit_digital_product',
                        'delete_digital_product',
                        'download_digital_product',
                        'set_category_wise_commission',
                        'view_seller_orders',
                        'view_pickup_point_orders',
                        'unpaid_order_payment_notification_send',
                        'view_classified_products',
                        'publish_classified_products',
                        'delete_classified_products',
                        'view_classified_packages',
                        'add_classified_packages',
                        'edit_classified_packages',
                        'delete_classified_packages',
                        'seller_product_sale_report',
                        'select_header',
                        'select_homepage',
                        'view_all_website_pages',
                        'add_website_page',
                        'edit_website_page',
                        'delete_website_page',
                        'seller_products_sale_report',
                        'commission_history_report',
                        'publish_classified_product',
                        'delete_classified_product',
                        'add_classified_package',
                        'edit_classified_package',
                        'delete_classified_package',
                        'send_newsletter',
                        'manage_email_templates',
                        'publish_custom_alerts',
                        'delete_custom_alerts',
                        'edit_custom_alerts', // fixed
                        'add_custom_alerts',
                        'view_all_custom_alerts',
                        'publish_dynamic_popups',
                        'delete_dynamic_popups',
                        'edit_dynamic_popups',
                        'add_dynamic_popups',
                        'view_all_dynamic_popups',
                        'delete_subscriber',
                        'view_all_subscribers',
                    ];

                    $addons = [
                        "offline_payment","club_point","pos_system","paytm",
                        "seller_subscription","otp_system","refund_request",
                        "affiliate_system","african_pg","delivery_boy",
                        "auction","wholesale"
                    ];
                @endphp

                @foreach ($permission_groups as $permission_group)

                    @php
                        $section = $permission_group->first()->section;
                    @endphp

                    <!-- Skip hidden sections -->
                    @if (in_array($section, $hidden_sections))
                        @continue
                    @endif

                    <!-- Skip inactive addons -->
                    @if (in_array($section, $addons) && !addon_is_activated($section))
                        @continue
                    @endif

                    <ul class="list-group mb-4">

                        <!-- Section Title -->
                        <li class="list-group-item bg-light">
                            {{ translate(Str::headline($section)) }}
                        </li>

                        <li class="list-group-item">
                            <div class="row">

                                @foreach ($permission_group as $permission)

                                    <!-- Skip hidden permissions -->
                                    @if (in_array($permission->name, $hidden_permissions))
                                        @continue
                                    @endif

                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="p-2 border mt-1 mb-2">

                                            <label class="control-label d-flex">
                                                {{ translate(Str::headline($permission->name)) }}
                                            </label>

                                            <label class="aiz-switch aiz-switch-success">
                                                <input type="checkbox"
                                                       name="permissions[]"
                                                       value="{{ $permission->id }}"
                                                       @if ($role->hasPermissionTo($permission->name)) checked @endif>

                                                <span class="slider round"></span>
                                            </label>

                                        </div>
                                    </div>

                                @endforeach

                            </div>
                        </li>

                    </ul>

                @endforeach

                <!-- Submit -->
                <div class="form-group text-right mt-3">
                    <button type="submit" class="btn btn-primary">
                        {{ translate('Update') }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection