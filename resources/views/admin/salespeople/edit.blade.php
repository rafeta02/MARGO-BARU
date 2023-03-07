@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.salesperson.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.salespeople.update", [$salesperson->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="code">{{ trans('cruds.salesperson.fields.code') }}</label>
                <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="text" name="code" id="code" value="{{ old('code', $salesperson->code) }}" required>
                @if($errors->has('code'))
                    <span class="text-danger">{{ $errors->first('code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.salesperson.fields.code_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.salesperson.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $salesperson->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.salesperson.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="marketing_area_id">{{ trans('cruds.salesperson.fields.marketing_area') }}</label>
                <select class="form-control select2 {{ $errors->has('marketing_area') ? 'is-invalid' : '' }}" name="marketing_area_id" id="marketing_area_id" required>
                    @foreach($marketing_areas as $id => $entry)
                        <option value="{{ $id }}" {{ (old('marketing_area_id') ? old('marketing_area_id') : $salesperson->marketing_area->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('marketing_area'))
                    <span class="text-danger">{{ $errors->first('marketing_area') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.salesperson.fields.marketing_area_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="phone">{{ trans('cruds.salesperson.fields.phone') }}</label>
                <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="text" name="phone" id="phone" value="{{ old('phone', $salesperson->phone) }}">
                @if($errors->has('phone'))
                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.salesperson.fields.phone_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="company">{{ trans('cruds.salesperson.fields.company') }}</label>
                <input class="form-control {{ $errors->has('company') ? 'is-invalid' : '' }}" type="text" name="company" id="company" value="{{ old('company', $salesperson->company) }}">
                @if($errors->has('company'))
                    <span class="text-danger">{{ $errors->first('company') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.salesperson.fields.company_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="address">{{ trans('cruds.salesperson.fields.address') }}</label>
                <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address">{{ old('address', $salesperson->address) }}</textarea>
                @if($errors->has('address'))
                    <span class="text-danger">{{ $errors->first('address') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.salesperson.fields.address_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection