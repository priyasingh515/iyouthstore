@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{ translate('Block Information') }}</h5>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-4">

                <form action="{{ route('blocks.update', $block->id) }}" method="POST">
                    @csrf
                    @method('POST')

                    {{-- Name --}}
                    <div class="form-group mb-3">
                        <label>{{ translate('Name') }}</label>
                        <input type="text"
                               name="name"
                               value="{{ $block->name }}"
                               class="form-control"
                               required>
                    </div>

                    <div class="form-group mb-3">
                        <label>{{ translate('District') }}</label>
                        <select name="district_id"
                                class="form-control aiz-selectpicker"
                                data-live-search="true"
                                required>

                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}"
                                    {{ $block->district_id == $district->id ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    @if(isset($block->cost))
                    <div class="form-group mb-3">
                        <label>{{ translate('Cost') }}</label>
                        <input type="number"
                               name="cost"
                               value="{{ $block->cost }}"
                               class="form-control"
                               min="0"
                               step="0.01">
                    </div>
                    @endif

                    <div class="form-group mb-3">
                        <label>{{ translate('Status') }}</label>
                        <select name="status" class="form-control">
                            <option value="1" {{ $block->status == 1 ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="0" {{ $block->status == 0 ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>
                    </div>

                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">
                            {{ translate('Update') }}
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection