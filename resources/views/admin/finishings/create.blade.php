@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <h1>Formulir SPK Finishing</h1>
    </div>

    <div class="card-body">
        <form class="form-prevent-multiple-submits" method="POST" action="{{ route("admin.finishings.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="no_spk">{{ trans('cruds.finishing.fields.no_spk') }}</label>
                        <input class="form-control {{ $errors->has('no_spk') ? 'is-invalid' : '' }}" type="text" name="no_spk" id="no_spk" value="{{ old('no_spk', $no_spk) }}" readonly>
                        @if($errors->has('no_spk'))
                            <span class="text-danger">{{ $errors->first('no_spk') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.finishing.fields.no_spk_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="date">{{ trans('cruds.finishing.fields.date') }}</label>
                        <input class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date', $today) }}" required>
                        @if($errors->has('date'))
                            <span class="text-danger">{{ $errors->first('date') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.finishing.fields.date_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required">{{ trans('cruds.salesOrder.fields.semester') }}</label>
                        <select class="form-control select2 {{ $errors->has('semester') ? 'is-invalid' : '' }}" name="semester_id" id="semester_id" required>
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
                        <label class="required" for="vendor_id">{{ trans('cruds.finishing.fields.vendor') }}</label>
                        <select class="form-control select2 {{ $errors->has('vendor') ? 'is-invalid' : '' }}" name="vendor_id" id="vendor_id" required>
                            @foreach($vendors as $id => $entry)
                                <option value="{{ $id }}" {{ old('vendor_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('vendor'))
                            <span class="text-danger">{{ $errors->first('vendor') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.finishing.fields.vendor_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="jenjang_id">{{ trans('cruds.bookVariant.fields.jenjang') }}</label>
                        <select class="form-control select2 {{ $errors->has('jenjang') ? 'is-invalid' : '' }}" name="jenjang_id" id="jenjang_id">
                            @foreach($jenjangs as $id => $entry)
                                <option value="{{ $id }}" {{ old('jenjang_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('jenjang'))
                            <span class="text-danger">{{ $errors->first('jenjang') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.bookVariant.fields.jenjang_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
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
                <div class="col-12">
                    <div class="form-group">
                        <label for="note">{{ trans('cruds.finishing.fields.note') }}</label>
                        <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{{ old('note') }}</textarea>
                        @if($errors->has('note'))
                            <span class="text-danger">{{ $errors->first('note') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.finishing.fields.note_helper') }}</span>
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
                    url: "{{ route('admin.book-variants.getCetak') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            type: 'finishing',
                            jenjang: $('#jenjang_id').val(),
                            kurikulum: $('#kurikulum_id').val(),
                            semester: $('#semester_id').val()
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
                url: "{{ route('admin.book-variants.getInfoFinishing') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    id: productId,
                },
                success: function(product) {
                    if (product.finishing_stock <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Stock Isi/Cover Kosong!',
                            showConfirmButton: false,
                            timer: 2000
                        });

                        return;
                    }

                    var formHtml = `
                        <div class="item-product" id="product-${product.id}">
                            <div class="row">
                                <div class="col-6 align-self-center">
                                    <h6 class="text-sm product-name mb-1">(${product.book_type}) ${product.short_name}
                                    </h6>
                                    <p class="mb-0 text-sm">
                                        Code : <strong>${product.code}</strong>
                                    </p>
                                    <p class="mb-0 text-sm">
                                        Jenjang - Kurikulum : <strong>${product.jenjang?.name} -
                                            ${product.kurikulum?.name}</strong>
                                    </p>
                                    <p class="mb-0 text-sm">
                                        <strong>ESTIMASI : ${product.estimasi_produksi.estimasi}</strong>
                                    </p>
                                    <p class="mb-0 text-sm">
                                        <strong>ESTIMASI BARU : ${product.estimasi_produksi.estimasi_baru}</strong>
                                    </p>`

                                    product.components.forEach(element => {
                                        if (element.type == 'C' || element.type == 'V') {
                                            var text = `<p class="mb-0 text-sm">
                                                <strong>STOCK COVER : ${element.stock}</strong>
                                            </p>`
                                        } else if (element.type == 'I' || element.type == 'S' || element.type == 'U') {
                                            var text = `<p class="mb-0 text-sm">
                                                <strong>STOCK ISI : ${element.stock}</strong>
                                            </p>`
                                        }
                                        formHtml = formHtml.concat(text);
                                    });

                formHtml = formHtml.concat(
                    `</div>
                    <div class="col offset-1 row align-items-end align-self-center">
                        <input type="hidden" name="products[]" value="${product.id}">
                        <div class="col" style="max-width: 210px">
                            <p class="mb-0 text-sm">Quantity</p>
                            <div class="form-group text-field m-0">
                                <div class="text-field-input px-2 py-0">
                                    <input class="quantity" type="hidden" name="quantities[]"
                                        data-max="${product.finishing_stock}"
                                        value="1">
                                    <input class="form-control text-center quantity_text" type="text" name="quantity_text[]"
                                        value="1" required>
                                    <label class="text-field-border"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto pl-5">
                            <button type="button" class="btn btn-danger btn-sm product-delete"
                                data-product-id="${product.id}" tabIndex="-1">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    </div>
                    <hr style="margin: 1em -15px;border-color:#ccc" />
                    </div>
                    `);
                    $('#product-form').append(formHtml);
                    $('#product-search').val(null).trigger('change');

                    var productForm = $('#product-form');
                    var productItem = productForm.find('.item-product');

                    productItem.each(function(index, item) {
                        var product = $(item);
                        var quantity = product.find('.quantity');
                        var quantityText = product.find('.quantity_text');
                        var max = quantity.data('max');

                        quantityText.on('input change', function(e) {
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

                            if (valueNum > max) {
                                el.val(max);
                                quantityText.val(max).trigger('change');
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
    });
</script>
@endsection
