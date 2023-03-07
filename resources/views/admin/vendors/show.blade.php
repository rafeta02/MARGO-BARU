@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.vendor.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.vendors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.vendor.fields.code') }}
                        </th>
                        <td>
                            {{ $vendor->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.vendor.fields.name') }}
                        </th>
                        <td>
                            {{ $vendor->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.vendor.fields.type') }}
                        </th>
                        <td>
                            {{ App\Models\Vendor::TYPE_SELECT[$vendor->type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.vendor.fields.contact') }}
                        </th>
                        <td>
                            {{ $vendor->contact }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.vendor.fields.address') }}
                        </th>
                        <td>
                            {{ $vendor->address }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.vendor.fields.company') }}
                        </th>
                        <td>
                            {{ $vendor->company }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.vendors.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection