@extends('layouts.admin')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="m-0 bold">Produksi Cetak Plate</h1>
    </div>
</div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.platePrint.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <form id="filterform">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label>{{ trans('cruds.bookVariant.fields.type') }}</label>
                        <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                            <option value {{ old('type', null) === null ? 'selected' : '' }}>All</option>
                            @foreach(App\Models\PlatePrint::TYPE_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label>Vendor</label>
                        <select class="form-control select2 {{ $errors->has('vendor_id') ? 'is-invalid' : '' }}" name="vendor_id" id="vendor_id">
                            @foreach($vendors as $id => $entry)
                                <option value="{{ $id }}" {{ old('vendor_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <button class="btn btn-success" type="submit">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-PlatePrint">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.platePrint.fields.no_spk') }}
                    </th>
                    <th>
                        {{ trans('cruds.platePrint.fields.date') }}
                    </th>
                    <th>
                        {{ trans('cruds.platePrint.fields.semester') }}
                    </th>
                    <th>
                        {{ trans('cruds.platePrint.fields.type') }}
                    </th>
                    <th>
                        Vendor / Customer
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
$(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: {
        url: "{{ route('admin.aquarium.index') }}",
        data: function(data) {
            data.type = $('#type').val(),
            data.vendor = $('#vendor_id').val()
        }
    },
    columns: [
        { data: 'placeholder', name: 'placeholder' },
        { data: 'no_spk', name: 'no_spk', class: 'text-center' },
        { data: 'date', name: 'date', class: 'text-center'  },
        { data: 'semester_name', name: 'semester.name', class: 'text-center'  },
        { data: 'type', name: 'type', class: 'text-center'  },
        { data: 'customer', name: 'customer', class: 'text-center'  },
        { data: 'actions', name: '{{ trans('global.actions') }}', class: 'text-center'  }
    ],
    orderCellsTop: true,
    // order: [[ 4, 'desc' ]],
    pageLength: 50,
  };
  let table = $('.datatable-PlatePrint').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

    $("#filterform").submit(function(event) {
        event.preventDefault();
        table.ajax.reload();
    });
  
});

</script>
@endsection