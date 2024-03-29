@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.book.title_singular') }}
    </div>

    <div class="card-body">
        <form class="form-prevent-multiple-submits" method="POST" action="{{ route("admin.books.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="jenjang_id">{{ trans('cruds.book.fields.jenjang') }}</label>
                        <select class="form-control select2 {{ $errors->has('jenjang') ? 'is-invalid' : '' }}" name="jenjang_id" id="jenjang_id" required>
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
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="kurikulum_id">{{ trans('cruds.book.fields.kurikulum') }}</label>
                        <select class="form-control select2 {{ $errors->has('kurikulum') ? 'is-invalid' : '' }}" name="kurikulum_id" id="kurikulum_id" required>
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
                <div class="col-12">
                    <div class="form-group">
                        <label class="required" for="mapel_id">{{ trans('cruds.book.fields.mapel') }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('mapel') ? 'is-invalid' : '' }}" name="mapel[]" id="mapel" multiple required>
                            @foreach($mapels as $id => $entry)
                                <option value="{{ $id }}" {{ in_array($id, old('mapel', [])) ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('mapel'))
                            <span class="text-danger">{{ $errors->first('mapel') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.book.fields.mapel_helper') }}</span>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="required" for="kelas">{{ trans('cruds.book.fields.kelas') }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('kelas') ? 'is-invalid' : '' }}" name="kelas[]" id="kelas" multiple required>
                            @foreach($kelas as $id => $entry)
                                <option value="{{ $id }}" {{ in_array($id, old('kelas', [])) ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('kelas'))
                            <span class="text-danger">{{ $errors->first('kelas') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.book.fields.kelas_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="semester_id">{{ trans('cruds.book.fields.semester') }}</label>
                        <select class="form-control select2 {{ $errors->has('semester') ? 'is-invalid' : '' }}" name="semester_id" id="semester_id" required>
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
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="isi_id">{{ trans('cruds.book.fields.isi') }}</label>
                        <select class="form-control select2 {{ $errors->has('isi') ? 'is-invalid' : '' }}" name="isi_id" id="isi_id" required>
                            @foreach($isis as $id => $entry)
                                <option value="{{ $id }}" {{ old('isi_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('isi'))
                            <span class="text-danger">{{ $errors->first('isi') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.book.fields.isi_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="cover_id">{{ trans('cruds.book.fields.cover') }}</label>
                        <select class="form-control select2 {{ $errors->has('cover') ? 'is-invalid' : '' }}" name="cover_id" id="cover_id" required>
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
                {{-- <div class="col-12">
                    <div class="form-group">
                        <label for="description">{{ trans('cruds.book.fields.description') }}</label>
                        <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description') }}</textarea>
                        @if($errors->has('description'))
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.book.fields.description_helper') }}</span>
                    </div>
                </div> --}}
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="price">{{ trans('cruds.bookVariant.fields.price') }} LKS</label>
                        <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="number" name="price" id="price" value="{{ old('price', '') }}" step="1" required>
                        @if($errors->has('price'))
                            <span class="text-danger">{{ $errors->first('price') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.bookVariant.fields.price_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="stock">{{ trans('cruds.bookVariant.fields.stock') }} LKS</label>
                        <input class="form-control {{ $errors->has('stock') ? 'is-invalid' : '' }}" type="number" name="stock" id="stock" value="{{ old('stock', '0') }}" step="1" required>
                        @if($errors->has('stock'))
                            <span class="text-danger">{{ $errors->first('stock') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.bookVariant.fields.stock_helper') }}</span>
                    </div>
                </div>
                {{-- <div class="col-6">
                    <div class="form-group">
                        <label for="cost">{{ trans('cruds.bookVariant.fields.cost') }} LKS</label>
                        <input class="form-control {{ $errors->has('cost') ? 'is-invalid' : '' }}" type="number" name="cost" id="cost" value="{{ old('cost', '') }}" step="0.01">
                        @if($errors->has('cost'))
                            <span class="text-danger">{{ $errors->first('cost') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.bookVariant.fields.cost_helper') }}</span>
                    </div>
                </div> --}}
                <div class="col-6">
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="lks_status" id="lks_status" value="1" checked onchange="toggleHalamanLks()">
                            <label class="required form-check-label" for="status">Generate LKS</label>
                            <div class="form-group" id="halaman-lks-group">
                                <label class="required" for="halaman_id">{{ trans('cruds.bookVariant.fields.halaman') }} LKS</label>
                                <select class="form-control select2 {{ $errors->has('halaman') ? 'is-invalid' : '' }}" name="halaman_id" id="halaman_id" required>
                                    @foreach($halamen as $id => $entry)
                                        <option value="{{ $id }}" {{ old('halaman_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="pg_status" id="pg_status" value="1" checked onchange="toggleHalamanPg()">
                            <label class="required form-check-label" for="status">Generate PG</label>
                            <div class="form-group" id="halaman-pg-group">
                                <label class="required" for="halaman_pg_id">{{ trans('cruds.bookVariant.fields.halaman') }} PG</label>
                                <select class="form-control select2" name="halaman_pg_id" id="halaman_pg_id">
                                    @foreach($halamen as $id => $entry)
                                        <option value="{{ $id }}" {{ old('halaman_pg_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kunci_status" id="kunci_status" value="1" checked onchange="toggleHalamanKunci()">
                            <label class="required form-check-label" for="status">Generate Kunci</label>
                            <div class="form-group" id="halaman-kunci-group">
                                <label class="required" for="halaman_kunci_id">{{ trans('cruds.bookVariant.fields.halaman') }} Kunci</label>
                                <select class="form-control select2" name="halaman_kunci_id" id="halaman_kunci_id" >
                                    @foreach($halamen as $id => $entry)
                                        <option value="{{ $id }}" {{ old('halaman_kunci_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-2">
                    <button type="submit" class="btn btn-success btn-block form-prevent-multiple-submits">{{ trans('global.save') }}</a>
                </div>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    var uploadedPhotoMap = {}
Dropzone.options.photoDropzone = {
    url: '{{ route('admin.books.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="photo[]" value="' + response.name + '">')
      uploadedPhotoMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedPhotoMap[file.name]
      }
      $('form').find('input[name="photo[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($book) && $book->photo)
      var files = {!! json_encode($book->photo) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="photo[]" value="' + file.file_name + '">')
        }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>

<script>
    function toggleHalamanLks() {
        var checkbox = document.getElementById('lks_status');
        var halamanGroup = document.getElementById('halaman-lks-group');
        var halamanSelect = document.getElementById('halaman_id');

        if (checkbox.checked) {
            halamanGroup.style.display = 'block';
            halamanSelect.setAttribute('required', 'required');
        } else {
            halamanGroup.style.display = 'none';
            halamanSelect.removeAttribute('required');
        }
    }
    function toggleHalamanPg() {
        var checkbox = document.getElementById('pg_status');
        var halamanGroup = document.getElementById('halaman-pg-group');

        if (checkbox.checked) {
            halamanGroup.style.display = 'block';
        } else {
            halamanGroup.style.display = 'none';
        }
    }
    function toggleHalamanKunci() {
        var checkbox = document.getElementById('kunci_status');
        var halamanGroup = document.getElementById('halaman-kunci-group');

        if (checkbox.checked) {
            halamanGroup.style.display = 'block';
        } else {
            halamanGroup.style.display = 'none';
        }
    }
</script>
@endsection
