@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-12">
                <h1 class="h3">{{ translate('All Sub Districts') }}</h1>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- LEFT SIDE LIST --}}
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">

                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Sub District Name') }}</th>
                                <th>{{ translate('District') }}</th>
                                <th>{{ translate('Block') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subDistricts as $key => $sub)
                                <tr>
                                    <td>{{ $key + 1 + ($subDistricts->currentPage() - 1) * $subDistricts->perPage() }}</td>
                                    <td>{{ $sub->name }}</td>
                                    <td>{{ $sub->district->name ?? '' }}</td>
                                    <td>{{ $sub->block->name ?? '' }}</td>

                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input onchange="update_status(this)" value="{{ $sub->id }}"
                                                type="checkbox" {{ $sub->status ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>

                                    <td class="text-right">
                                        <a href="{{ route('subdistricts.edit', $sub->id) }}"
                                            class="btn btn-soft-primary btn-icon btn-circle btn-sm">
                                            <i class="las la-edit"></i>
                                        </a>

                                        <a href="#"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('subdistricts.destroy', $sub->id) }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="aiz-pagination">
                        {{ $subDistricts->links() }}
                    </div>

                </div>
            </div>
        </div>

        {{-- RIGHT SIDE ADD FORM --}}
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Add New Sub District') }}</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('subdistricts.store') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label>{{ translate('District') }}</label>
                            <select name="district_id" class="form-control aiz-selectpicker" data-live-search="true"
                                onchange="getBlocks(this.value)" required>
                                <option value="">{{ translate('Select District') }}</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}">
                                        {{ $district->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>{{ translate('Block') }}</label>
                            <select name="block_id" id="block_dropdown" class="form-control aiz-selectpicker"
                                data-live-search="true" required>
                                <option value="">{{ translate('Select Block') }}</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label>{{ translate('Sub District Name') }}</label>
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

            $.post('{{ route('subdistricts.status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data) {

                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Sub District status updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }

        function getBlocks(district_id) {

            $.get('{{ route('subdistricts.getBlocks') }}', {
                district_id: district_id
            }, function(data) {

                let html = '<option value="">{{ translate('Select Block') }}</option>';

                data.forEach(function(block) {
                    html += `<option value="${block.id}">${block.name}</option>`;
                });

                $('#block_dropdown').html(html);
                $('.aiz-selectpicker').selectpicker('refresh');
            });
        }
    </script>
@endsection
