@extends('layouts.admin')
@section('content')
@can('book_variant_edit')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <button class="btn btn-primary" data-toggle="modal" data-target="#importModal">
                Edit Harga
            </button>
        </div>
    </div>
@endcan

<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Edit Harga LKS Filter Tertentu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('admin.book-variants.updatePrice') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="col-md-4" for="semester_id">{{ trans('cruds.book.fields.semester') }}</label>
                                <div class="col-12">
                                    <select style="width: 100%;" class="form-control select2 {{ $errors->has('semester') ? 'is-invalid' : '' }}" name="semester_id">
                                        @foreach($semesters as $id => $entry)
                                            <option value="{{ $id }}" {{ (old('semester_id') ? old('semester_id') : setting('current_semester') ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4" for="halaman_id">{{ trans('cruds.bookVariant.fields.halaman') }}</label>
                                <div class="col-12">
                                    <select style="width: 100%;" class="form-control select2 {{ $errors->has('halaman') ? 'is-invalid' : '' }}" name="halaman_id" required>
                                        @foreach($halamen as $id => $entry)
                                            <option value="{{ $id }}" {{ old('halaman_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4" for="jenjang_id">{{ trans('cruds.bookVariant.fields.jenjang') }}</label>
                                <div class="col-12">
                                    <select style="width: 100%;" class="form-control select2 {{ $errors->has('jenjang') ? 'is-invalid' : '' }}" name="jenjang_id">
                                        @foreach($jenjangs as $id => $entry)
                                            <option value="{{ $id }}" {{ old('jenjang_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4" for="cover_id">{{ trans('cruds.book.fields.isi') }}</label>
                                <div class="col-12">
                                    <select style="width: 100%;" class="form-control select2 {{ $errors->has('isi') ? 'is-invalid' : '' }}" name="isi_id">
                                        @foreach($isis as $id => $entry)
                                            <option value="{{ $id }}" {{ old('isi_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4" for="cover_id">{{ trans('cruds.book.fields.cover') }}</label>
                                <div class="col-12">
                                    <select style="width: 100%;" class="form-control select2 {{ $errors->has('cover') ? 'is-invalid' : '' }}" name="cover_id">
                                        @foreach($covers as $id => $entry)
                                            <option value="{{ $id }}" {{ old('cover_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4" for="price">{{ trans('cruds.bookVariant.fields.price') }}</label>
                                <div class="col-12">
                                    <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" value="{{ old('price', '0') }}" step="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                Change
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.bookVariant.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <form id="filterform"  method="POST" action="{{ route("admin.book-variants.export") }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label>{{ trans('cruds.bookVariant.fields.type') }}</label>
                        <select class="form-control select2 {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                            <option value {{ old('type', null) === null ? 'selected' : '' }}>All</option>
                            @foreach(App\Models\BookVariant::TYPE_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('type', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('type'))
                            <span class="text-danger">{{ $errors->first('type') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.bookVariant.fields.type_helper') }}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="jenjang_id">{{ trans('cruds.book.fields.jenjang') }}</label>
                        <select class="form-control select2 {{ $errors->has('jenjang') ? 'is-invalid' : '' }}" name="jenjang_id" id="jenjang_id">
                            @foreach($jenjangs as $id => $entry)
                                <option value="{{ $id }}" {{ old('jenjang_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('jenjang'))
                            <span class="text-danger">{{ $errors->first('jenjang') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.book.fields.jenjang_helper') }}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="kurikulum_id">{{ trans('cruds.book.fields.kurikulum') }}</label>
                        <select class="form-control select2 {{ $errors->has('kurikulum') ? 'is-invalid' : '' }}" name="kurikulum_id" id="kurikulum_id">
                            @foreach($kurikulums as $id => $entry)
                                <option value="{{ $id }}" {{ old('kurikulum_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('kurikulum'))
                            <span class="text-danger">{{ $errors->first('kurikulum') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.book.fields.kurikulum_helper') }}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="mapel_id">{{ trans('cruds.book.fields.mapel') }}</label>
                        <select class="form-control select2 {{ $errors->has('mapel') ? 'is-invalid' : '' }}" name="mapel_id" id="mapel_id">
                            @foreach($mapels as $id => $entry)
                                <option value="{{ $id }}" {{ old('mapel_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('mapel'))
                            <span class="text-danger">{{ $errors->first('mapel') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.book.fields.mapel_helper') }}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="kelas_id">{{ trans('cruds.bookVariant.fields.kelas') }}</label>
                        <select class="form-control select2 {{ $errors->has('kelas') ? 'is-invalid' : '' }}" name="kelas_id" id="kelas_id">
                            @foreach($kelas as $id => $entry)
                                <option value="{{ $id }}" {{ old('kelas_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('kelas'))
                            <span class="text-danger">{{ $errors->first('kelas') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.bookVariant.fields.kelas_helper') }}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="semester_id">{{ trans('cruds.book.fields.semester') }}</label>
                        <select class="form-control select2 {{ $errors->has('semester') ? 'is-invalid' : '' }}" name="semester_id" id="semester_id">
                            @foreach($semesters as $id => $entry)
                                <option value="{{ $id }}" {{ old('semester_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('semester'))
                            <span class="text-danger">{{ $errors->first('semester') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.book.fields.semester_helper') }}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="cover_id">{{ trans('cruds.book.fields.isi') }}</label>
                        <select class="form-control select2 {{ $errors->has('isi') ? 'is-invalid' : '' }}" name="isi_id" id="isi_id">
                            @foreach($isis as $id => $entry)
                                <option value="{{ $id }}" {{ old('isi_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('isi'))
                            <span class="text-danger">{{ $errors->first('cover') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.book.fields.cover_helper') }}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="cover_id">{{ trans('cruds.book.fields.cover') }}</label>
                        <select class="form-control select2 {{ $errors->has('cover') ? 'is-invalid' : '' }}" name="cover_id" id="cover_id">
                            @foreach($covers as $id => $entry)
                                <option value="{{ $id }}" {{ old('cover_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('cover'))
                            <span class="text-danger">{{ $errors->first('cover') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.book.fields.cover_helper') }}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="halaman_id">{{ trans('cruds.bookVariant.fields.halaman') }}</label>
                        <select class="form-control select2 {{ $errors->has('halaman') ? 'is-invalid' : '' }}" name="halaman_id" id="halaman_id">
                            @foreach($halamen as $id => $entry)
                                <option value="{{ $id }}" {{ old('halaman_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('halaman'))
                            <span class="text-danger">{{ $errors->first('halaman') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.bookVariant.fields.halaman_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <button id="buttonFilter" class="btn btn-success">Filter</button>
                <button type="submit" value="export" name="export" class="btn btn-warning">Export</button>
            </div>
        </form>
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-BookVariant">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th width="200">
                        {{ trans('cruds.bookVariant.fields.code') }}
                    </th>
                    <th>
                        Nama
                    </th>
                    <th>
                        {{ trans('cruds.bookVariant.fields.stock') }}
                    </th>
                    <th>
                        {{ trans('cruds.bookVariant.fields.price') }}
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
    let dtOverrideGlobals = {
        processing: true,
        serverSide: true,
        retrieve: true,
        ajax: {
            url: "{{ route('admin.book-variants.index') }}",
            data: function(data) {
                data.type = $('#type').val(),
                data.semester = $('#semester_id').val(),
                data.isi = $('#isi_id').val(),
                data.cover = $('#cover_id').val(),
                data.kurikulum = $('#kurikulum_id').val(),
                data.jenjang = $('#jenjang_id').val(),
                data.kelas = $('#kelas_id').val(),
                data.mapel = $('#mapel_id').val(),
                data.halaman = $('#halaman_id').val()
            }
        },
        columns: [{
                data: 'placeholder',
                name: 'placeholder'
            },
            {
                data: 'code',
                name: 'code',
                class: 'text-center',
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'stock',
                name: 'stock',
                class: 'text-center',
            },
            {
                data: 'price',
                name: 'price',
                class: 'text-right',
                render: function(value) { return numeral(value).format('$0,0'); }
            },
            {
                data: 'actions',
                name: '{{ trans('global.actions ') }}',
                class: 'text-center'
            }
        ],
        orderCellsTop: true,
        order: [
            [1, 'desc']
        ],
        pageLength: 50,
    };
    let table = $('.datatable-BookVariant').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
    $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();
    });

    $("#buttonFilter").click(function(event) {
        event.preventDefault();
        table.ajax.reload();
    });

});

</script>
@endsection
