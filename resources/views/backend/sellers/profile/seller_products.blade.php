       <div class="d-flex justify-content-between align-items-center ">
           <h5 class=" mb-0 font-weight-bold mt-2">{{ translate('Items') }}</h5>
           <!-- Bulk Delete -->
       </div>

       <div class=" mt-2">
           <form class="" id="sort_products" action="" method="GET">
               <div>

                   <table class="table aiz-table inv-table-2 mb-0">
                       <thead>
                           <tr>
                               <th class="place-th-checkbox">
                                   <div class="form-group">
                                       <div class="aiz-checkbox-inline">
                                           <label class="aiz-checkbox">
                                               <input type="checkbox" class="check-all">
                                               <span class="aiz-square-check"></span>
                                           </label>
                                       </div>
                                   </div>
                               </th>

                               <th>{{ translate('Product') }}</th>
                               <th>{{ translate('SKU') }}</th>
                               <th data-breakpoints="sm">{{ translate('Purchase Price') }}</th>
                               <th data-breakpoints="md">{{ translate('Current Stock') }}</th>
                               <th data-breakpoints="lg">{{ translate('Last Purchase Date') }}</th>
                               <th class="text-right">{{ translate('Action') }}</th>
                           </tr>
                       </thead>
                       <tbody>

                           @foreach ($sellerProducts as $item)
                               <tr class="row-item" data-id="{{ $item->id }}">
                                   <td>
                                       <div class="form-group d-inline-block mt-2">
                                           <label class="aiz-checkbox">
                                               <input type="checkbox" class="check-one" name="id[]"
                                                   value="{{ $item->id }}">
                                               <span class="aiz-square-check"></span>
                                           </label>
                                       </div>
                                   </td>
                                   <td>
                                       <a href="{{ route('product', $item->product->slug) }}"
                                           class="font-weight-bold text-primary">
                                           {{ $item->product->getTranslation('name') }}</a>
                                   </td>
                                   <td>
                                       @if (optional($item->product->stocks)->count() > 1)
                                           Variant Product
                                       @else
                                           {{ optional($item->product->stocks->first())->sku ?? 'N/A' }}
                                       @endif
                                   </td>
                                   <td>
                                       <b> {{ $item->product->seller_price }}</b>
                                   </td>
                                   <td>
                                       <b>{{ $item->stock }}</b>
                                   </td>
                                   <td>
                                       <b>
                                           @php
                                               $last_purchase = $item->product->orderDetails
                                                   ->sortByDesc('created_at')
                                                   ->first();
                                           @endphp

                                           @if ($last_purchase)
                                               {{ $last_purchase->created_at->format('d M Y') }}
                                           @else
                                               N/A
                                           @endif
                                   </td>
                                   <td>
                                       <div class="d-flex justify-content-end"> <!-- Flex to push button to right -->
                                           <div class="dropdown">
                                               <button type="button" class="btn p-0 border-0 bg-transparent"
                                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                   style="box-shadow: none;">
                                                   <i class="las la-ellipsis-v"
                                                       style="font-size: 1.5rem; color: #8c9196ff;"></i>
                                               </button>
                                               <div class="dropdown-menu dropdown-menu-right dropdown-menu-xs">
                                                   <a href="{{ route('product', $item->product->slug) }}"
                                                       class="dropdown-item fs-13">
                                                       {{ translate('View') }}
                                                   </a>
                                                   @can('product_edit')
                                                       <a href="{{ route('products.seller.edit', ['id' => $item->product->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                                           class="dropdown-item  fs-13">
                                                           {{ translate('Edit') }}
                                                       </a>
                                                   @endcan

                                                   @can('product_duplicate')
                                                       <a href="{{ route('products.duplicate', ['id' => $item->product->id, 'type' => $type]) }}"
                                                           class="dropdown-item  fs-13">
                                                           {{ translate('Duplicate') }}
                                                       </a>
                                                   @endcan
                                                   @can('product_delete')
                                                       <a href="javascript:void();"
                                                           class="dropdown-item confirm-delete fs-13"
                                                           data-href="{{ route('products.destroy', $item->product->id) }}">
                                                           {{ translate('Delete') }}
                                                       </a>
                                                   @endcan
                                               </div>
                                           </div>
                                       </div>
                                   </td>
                               </tr>
                           @endforeach


                       </tbody>
                   </table>
                   <div class="aiz-pagination inv-pagination mt-4">
                       {{ $sellerProducts->appends(request()->input())->links() }}
                   </div>

               </div>
           </form>
       </div>
