@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-12">
                <h1 class="h3">{{ translate('All Blocks') }}</h1>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Block Name') }}</th>
                                <th>{{ translate('District') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($blocks as $key => $block)
                                <tr>
                                    <td>{{ $key + 1 + ($blocks->currentPage() - 1) * $blocks->perPage() }}</td>
                                    <td>{{ $block->name }}</td>
                                    <td>{{ $block->district->name ?? '' }}</td>

                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input onchange="update_status(this)" value="{{ $block->id }}"
                                                type="checkbox" {{ $block->status ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>

                                    <td class="text-right">
                                        <a href="{{ route('blocks.edit', $block->id) }}"
                                            class="btn btn-soft-primary btn-icon btn-circle btn-sm">
                                            <i class="las la-edit"></i>
                                        </a>

                                        <a href="#"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('blocks.destroy', $block->id) }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="aiz-pagination">
                        {{ $blocks->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Add New Block') }}</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('blocks.store') }}" method="POST">
                        @csrf
                        {{-- <div class="form-group">
                            <label for="country">{{ translate('Country') }}</label>
                            <select class="select2 form-control aiz-selectpicker" name="country_id" data-toggle="select2"
                                data-placeholder="Choose ..." data-live-search="true" required>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        {{-- <div class="form-group">
                            <label for="country">{{ translate('State') }}</label>
                            <select class="select2 form-control aiz-selectpicker" name="state_id" data-toggle="select2"
                                data-placeholder="Choose ..." data-live-search="true" required>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="form-group mb-3">
                            <label>{{ translate('District') }}</label>
                            <select name="district_id" class="form-control aiz-selectpicker" data-live-search="true"
                                required>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}">
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>{{ translate('Block Name') }}</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">
                                {{ translate('Save') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')
    <script>
        function update_status(el) {

            if ('{{ env('DEMO_MODE') }}' == 'On') {
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                return;
            }

            let status = el.checked ? 1 : 0;

            $.post('{{ route('blocks.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {

                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Block status updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
