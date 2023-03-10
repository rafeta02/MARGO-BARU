@extends('layouts.admin')
@section('content')
@can('sales_order_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.sales-orders.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.salesOrder.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.salesOrder.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-SalesOrder">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.salesOrder.fields.semester') }}
                    </th>
                    <th>
                        {{ trans('cruds.salesOrder.fields.salesperson') }}
                    </th>
                    <th>
                        {{ trans('cruds.salesOrder.fields.product') }}
                    </th>
                    <th>
                        {{ trans('cruds.salesOrder.fields.jenjang') }}
                    </th>
                    <th>
                        {{ trans('cruds.salesOrder.fields.kurikulum') }}
                    </th>
                    <th>
                        {{ trans('cruds.salesOrder.fields.quantity') }}
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
@can('sales_order_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.sales-orders.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.sales-orders.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'semester_name', name: 'semester.name' },
{ data: 'salesperson_name', name: 'salesperson.name' },
{ data: 'product_code', name: 'product.code' },
{ data: 'jenjang_code', name: 'jenjang.code' },
{ data: 'kurikulum_code', name: 'kurikulum.code' },
{ data: 'quantity', name: 'quantity' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 2, 'desc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-SalesOrder').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection