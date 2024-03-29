@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.marketingArea.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.marketing-areas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.marketingArea.fields.name') }}
                        </th>
                        <td>
                            {{ $marketingArea->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.marketingArea.fields.description') }}
                        </th>
                        <td>
                            {{ $marketingArea->description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.marketingArea.fields.group_area') }}
                        </th>
                        <td>
                            {{ $marketingArea->group_area->code ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.marketing-areas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection