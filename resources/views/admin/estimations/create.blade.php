@extends('layouts.admin')
@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="m-0">Order Sales</h1>
    </div>
</div>
<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.estimation.title_singular') }}
    </div>

    <div class="card-body">
        @if (session()->has('error-message'))
            <p class="text-danger">
                {{session()->get('error-message')}}
            </p>
        @endif

        <form class="form-prevent-multiple-submits" class="form-prevent-multiple-submits" method="POST" action="{{ route("admin.estimations.store") }}" enctype="multipart/form-data" onkeypress="return event.keyCode != 13;">
            @csrf
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="no_estimasi">{{ trans('cruds.estimation.fields.no_estimasi') }}</label>
                        <input class="form-control {{ $errors->has('no_estimasi') ? 'is-invalid' : '' }}" type="text" name="no_estimasi" id="no_estimasi" value="{{ old('no_estimasi', $no_estimasi) }}" readonly>
                        @if($errors->has('no_estimasi'))
                            <span class="text-danger">{{ $errors->first('no_estimasi') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.estimation.fields.no_estimasi_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="date">{{ trans('cruds.estimation.fields.date') }}</label>
                        <input class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date', $today) }}" required>
                        @if($errors->has('date'))
                            <span class="text-danger">{{ $errors->first('date') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.estimation.fields.date_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="salesperson_id">{{ trans('cruds.estimation.fields.salesperson') }}</label>
                        <select class="form-control select2 {{ $errors->has('salesperson') ? 'is-invalid' : '' }}" name="salesperson_id" id="salesperson_id" required>
                            @foreach($salespeople as $id => $entry)
                                <option value="{{ $id }}" {{ old('salesperson_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('salesperson'))
                            <span class="text-danger">{{ $errors->first('salesperson') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.estimation.fields.salesperson_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required">{{ trans('cruds.salesOrder.fields.semester') }}</label>
                        <select class="form-control select2 {{ $errors->has('semester') ? 'is-invalid' : '' }}" name="semester" id="semester">
                            @foreach($semesters as $id => $entry)
                                <option value="{{ $id }}" {{ (old('semester') ? old('semester') : setting('current_semester') ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('semester'))
                            <span class="text-danger">{{ $errors->first('semester') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.salesOrder.fields.semester_helper') }}</span>
                    </div>
                </div>
                {{-- <div class="col-12">
                    <div class="form-group">
                        <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                            <label class="form-check-label" for="status">Apakah Akan Dimasukkan Ke Estimasi ?</label>
                            <input type="hidden" id="status" name="status" value="1">
                            <input id="switch-status" class="bootstrap-switch" type="checkbox" tabindex="-1" value="1" checked data-on-text="Iya" data-off-text="Tidak">
                        </div>
                        @if($errors->has('status'))
                            <span class="text-danger">{{ $errors->first('status') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.semester.fields.status_helper') }}</span>
                    </div>
                </div> --}}
            </div>
            <hr style="margin: .5em -15px;border-color:#ccc" />
            <h6 class="mt-2 mb-3">Filter</h6>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>{{ trans('cruds.salesOrder.fields.semester') }}</label>
                        <select class="form-control select2 {{ $errors->has('semester') ? 'is-invalid' : '' }}" name="semester_id" id="semester_id">
                            @foreach($semesters as $id => $entry)
                                <option value="{{ $id }}" {{ (old('semester_id') ? old('semester_id') : setting('current_semester') ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('semester'))
                            <span class="text-danger">{{ $errors->first('semester') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.salesOrder.fields.semester_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="jenjang_id">{{ trans('cruds.salesOrder.fields.jenjang') }}</label>
                        <select class="form-control select2 {{ $errors->has('jenjang') ? 'is-invalid' : '' }}" name="jenjang_id" id="jenjang_id">
                            @foreach($jenjangs as $id => $entry)
                                <option value="{{ $id }}" {{ old('jenjang_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('jenjang'))
                            <span class="text-danger">{{ $errors->first('jenjang') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.salesOrder.fields.jenjang_helper') }}</span>
                    </div>
                </div>
            </div>
            <hr style="margin: .5em -15px;border-color:#ccc" />
            <div class="row mb-4">
                <div class="col-12">
                    <div class="form-group">
                        <label for="product-search">Book Search</label>
                        <select id="product-search" class="form-control select2" style="width: 100%;">
                            <option></option>
                        </select>
                    </div>
                </div>
            </div>
            <div id="product-form"></div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <button class="btn btn-danger form-prevent-multiple-submits" type="submit">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('#product-search').select2({
            templateResult: formatProduct,
            templateSelection: formatProductSelection,
            ajax: {
                    url: "{{ route('admin.book-variants.getBooks') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            semester: $('#semester_id').val(),
                            jenjang: $('#jenjang_id').val()
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: false
                }
        });

        function formatProduct(product) {
            if (!product.id) {
                return product.text;
            }

            var productInfo = $('<span>' + product.text + '</span><br><small class="stock-info">' + product.name + '</small><br><small class="stock-info">Stock: ' + product.stock + '</small>');
            return productInfo;
        }

        function formatProductSelection(product) {
            return product.text;
        }

        $('#product-search').on('select2:select', function(e) {
            var productId = e.params.data.id;

            if ($('#product-' + productId).length > 0) {
                // Product is already added, show an error message using SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Buku Sudah Ditambahkan!',
                    showConfirmButton: false,
                    timer: 2000
                });

                $('#product-search').val(null).trigger('change');
                return;
            }

            $.ajax({
                url: "{{ route('admin.book-variants.getBook') }}" +  '?id=' + productId,
                type: 'GET',
                dataType: 'json',
                success: function(product) {
                    var formHtml = `
                        <div class="item-product" id="product-${product.id}">
                            <div class="row">
                                <div class="col-5 align-self-center">
                                    <h6 class="text-sm product-name mb-1">(${product.book_type}) ${product.short_name}</h6>
                                    <p class="mb-0 text-sm">
                                        Code : <strong>${product.code}</strong>
                                    </p>
                                    <p class="mb-0 text-sm">
                                        Jenjang - Kurikulum : <strong>${product.jenjang?.name} - ${product.kurikulum?.name}</strong>
                                    </p>
                                    <p class="mb-0 text-sm">
                                        Cover - Isi : <strong>${product.cover?.name} - ${product.isi?.name}</strong>
                                    </p>
                                </div>
                                <div class="col offset-1 row align-items-end align-self-center">
                                    <div class="col" style="max-width: 200px">
                                        <p class="mb-0 text-sm">Estimasi</p>
                                        <div class="form-group text-field m-0">
                                            <div class="text-field-input px-2 py-0">
                                                <input type="hidden" name="products[]" value="${product.id}">
                                                <input class="quantity" type="hidden" name="quantities[]" value="1">
                                                <input class="form-control text-center quantity_text" type="text" name="quantity_text[]" value="1" required>
                                                <label class="text-field-border"></label>
                                            </div>
                                        </div>
                                    </div>
                    `;
                    if (product.type == 'L') {
                        formHtml += `<div class="col" style="min-width: 240px">
                                        <p class="mb-0 text-sm">Pegangan Guru</p>
                                        <div class="form-group text-field m-0">
                                            <select class="form-control text-center pegeh" name="pgs[]" style="width: 100%;" tabIndex="-1" data-product="${product.id}">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col" style="max-width: 160px">
                                        <p class="mb-0 text-sm">Estimasi PG</p>
                                        <div class="form-group text-field m-0">
                                            <div class="text-field-input px-2 py-0">
                                                <input class="pg_quantity" type="hidden" name="pg_quantities[]" value="0">
                                                <input class="form-control text-center pg_quantity_text" type="text" name="pg_quantity_text[]" value="0" required>
                                                <label class="text-field-border"></label>
                                            </div>
                                        </div>
                                    </div>`;
                    }
                    formHtml += `<div class="col-auto pl-5">
                                    <button type="button" class="btn btn-danger btn-sm product-delete" data-product-id="${product.id}" tabIndex="-1">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <hr style="margin: 1em -15px;border-color:#ccc" />
                    </div>
                    `;
                    $('#product-form').append(formHtml);
                    $('#product-search').val(null).trigger('change');

                    if (!product.halaman_id) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Buku Belum Input Halaman!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }

                    sortItems();

                    $('.pegeh').select2({
                        ajax: {
                            url: "{{ route('admin.book-variants.getPg') }}",
                            data: function() {
                                return {
                                    id: $(this).data('product')
                                };
                            },
                            dataType: 'json',
                            processResults: function(data) {
                                if (data.length > 0) {
                                    // If data is not empty, return the processed results
                                    return {
                                        results: data
                                    };
                                } else {
                                    // If data is empty, show the SweetAlert alert and return empty results
                                    Swal.fire({
                                        title: 'Pegangan Guru Not Found',
                                        text: 'Pegangan Guru Not Foundk',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Okay',
                                        cancelButtonText: 'Cancel'
                                    });

                                    return {
                                        results: []
                                    };
                                }
                            }
                        }
                    });

                    var productForm = $('#product-form');
                    var productItem = productForm.find('.item-product');

                    productItem.each(function(index, item) {
                        var product = $(item);
                        var quantity = product.find('.quantity');
                        var quantityText = product.find('.quantity_text');
                        var pgQuantity = product.find('.pg_quantity');
                        var pgQuantityText = product.find('.pg_quantity_text');

                        quantityText.on('input', function(e) {
                            var value = numeral(e.target.value);

                            quantityText.val(value.format('0,0'));
                            quantity.val(value.value()).trigger('change');
                        }).trigger('change');

                        quantity.on('change', function(e) {
                            var el = $(e.currentTarget);
                            var valueNum = parseInt(el.val());
                            if (valueNum < 0) {
                                el.val(0);
                                quantityText.val(0).trigger('change');
                            }
                        }).trigger('change');

                        pgQuantityText.on('input', function(e) {
                            var value = numeral(e.target.value);

                            pgQuantityText.val(value.format('0,0'));
                            pgQuantity.val(value.value()).trigger('change');
                        }).trigger('change');

                        pgQuantity.on('change', function(e) {
                            var el = $(e.currentTarget);
                            var valueNum = parseInt(el.val());
                            if (valueNum < 0) {
                                el.val(0);
                                pgQuantityText.val(0).trigger('change');
                            }
                        }).trigger('change');
                    });
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });

        $('#product-form').on('click', '.product-delete', function() {
            var productId = $(this).data('product-id');
            $('#product-' + productId).remove();
        });

        function sortItems() {
            var productForm = $('#product-form');
            var items = productForm.find('.item-product').get();

            items.sort(function(a, b) {
                const idA = parseInt(a.id.split('-')[1]);
                const idB = parseInt(b.id.split('-')[1]);
                return idA - idB;
            });

            $("select.pegeh.select2-hidden-accessible").select2('destroy');

            productForm.empty().append(items);
        }

        // $('#switch-status').on('switchChange.bootstrapSwitch', function (event, state) {
        //     if (state) {
        //         done.val(1);
        //     } else {
        //         done.val(0);
        //     }
        // });
    });
</script>
@endsection
