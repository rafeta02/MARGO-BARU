@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.deliveryOrder.title_singular') }}
    </div>

    <div class="card-body">

        @if (session()->has('error-message'))
            <p class="text-danger">
                {{session()->get('error-message')}}
            </p>
        @endif

        <form method="POST" action="{{ route("admin.delivery-orders.update", [$deliveryOrder->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <input type="hidden" name="delivery_id" id="delivery_id" value="{{$deliveryOrder->id}}">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="no_suratjalan">{{ trans('cruds.deliveryOrder.fields.no_suratjalan') }}</label>
                        <input class="form-control {{ $errors->has('no_suratjalan') ? 'is-invalid' : '' }}" type="text" name="no_suratjalan" id="no_suratjalan" value="{{ old('no_suratjalan', $deliveryOrder->no_suratjalan) }}" readonly>
                        @if($errors->has('no_suratjalan'))
                            <span class="text-danger">{{ $errors->first('no_suratjalan') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.deliveryOrder.fields.no_suratjalan_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="date">{{ trans('cruds.deliveryOrder.fields.date') }}</label>
                        <input class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date', $deliveryOrder->date) }}" required>
                        @if($errors->has('date'))
                            <span class="text-danger">{{ $errors->first('date') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.deliveryOrder.fields.date_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="semester_id">{{ trans('cruds.deliveryOrder.fields.semester') }}</label>
                        <select class="form-control select2 {{ $errors->has('semester') ? 'is-invalid' : '' }}" name="semester_id" id="semester_id" disabled>
                            @foreach($semesters as $id => $entry)
                                <option value="{{ $id }}" {{ (old('semester_id') ? old('semester_id') : $deliveryOrder->semester->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('semester'))
                            <span class="text-danger">{{ $errors->first('semester') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.deliveryOrder.fields.semester_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="salesperson_id">{{ trans('cruds.deliveryOrder.fields.salesperson') }}</label>
                        <select class="form-control select2 {{ $errors->has('salesperson') ? 'is-invalid' : '' }}" name="salesperson_id" id="salesperson_id" disabled>
                            @foreach($salespeople as $id => $entry)
                                <option value="{{ $id }}" {{ (old('salesperson_id') ? old('salesperson_id') : $deliveryOrder->salesperson->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('salesperson'))
                            <span class="text-danger">{{ $errors->first('salesperson') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.deliveryOrder.fields.salesperson_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required">{{ trans('cruds.deliveryOrder.fields.payment_type') }}</label>
                        <select class="form-control {{ $errors->has('payment_type') ? 'is-invalid' : '' }}" name="payment_type" id="payment_type" disabled>
                            <option value disabled {{ old('payment_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\DeliveryOrder::PAYMENT_TYPE_SELECT as $key => $label)
                            <option value="{{ $key }}" {{ old('payment_type', $deliveryOrder->payment_type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('payment_type'))
                            <span class="text-danger">{{ $errors->first('payment_type') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.deliveryOrder.fields.payment_type_helper') }}</span>
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
                        <button class="btn btn-danger" type="submit">
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
                    url: "{{ route('admin.book-variants.getDelivery') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            delivery: $('#delivery_id').val(),
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
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
                url: "{{ route('admin.book-variants.getInfoDelivery') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    id: productId,
                    delivery: $('#delivery_id').val(),
                },
                success: function(product) {
                    var formHtml = `
                        <div class="item-product" id="product-${product.id}">
                            <div class="row">
                                <div class="col-8 align-self-center">
                                    <h6 class="text-sm product-name mb-1">(${product.book_type}) ${product.short_name}</h6>
                                    <p class="mb-0 text-sm">
                                        Code : <strong>${product.code}</strong>
                                    </p>
                                    <p class="mb-0 text-sm">
                                        Jenjang - Cover - Isi : <strong>${product.jenjang.name} - ${product.book.cover.name} - ${product.book.kurikulum.name}</strong>
                                    </p>
                                    <p class="mb-0 text-sm">
                                        <strong>ESTIMASI : ${product.estimasi}</strong>
                                    </p>
                                    <p class="mb-0 text-sm">
                                        <strong>TERKIRIM : ${product.terkirim - product.quantity}</strong>
                                    </p>
                                </div>
                                <div class="col offset-1 row align-items-end align-self-center">
                                    <div class="col" style="max-width: 160px">
                                        <p class="mb-0 text-sm">Dikirim</p>
                                        <div class="form-group text-field m-0">
                                            <div class="text-field-input px-2 py-0">
                                                <input type="hidden" name="products[]" value="${product.id}">
                                                <input type="hidden" name="delivery_items[]" value="${product.delivery_item_id}">
                                                <input class="quantity" type="hidden" name="quantities[]" data-max="${product.estimasi - product.terkirim}" value="${product.quantity}">
                                                <input class="form-control text-center quantity_text" type="text" name="quantity_text[]" value="${product.quantity}" required>
                                                <label class="text-field-border"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto pl-5">
                                        <button type="button" class="btn btn-danger btn-sm product-delete" data-product-id="${product.id}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <hr style="margin: 1em -15px;border-color:#ccc" />
                        </div>
                    `;
                    $('#product-form').prepend(formHtml);
                    $('#product-search').val(null).trigger('change');

                    var productForm = $('#product-form');
                    var productItem = productForm.find('.item-product');

                    productItem.each(function(index, item) {
                        var product = $(item);
                        var quantity = product.find('.quantity');
                        var quantityText = product.find('.quantity_text');

                        quantityText.on('input change', function(e) {
                            var value = numeral(e.target.value);

                            quantityText.val(value.format('0,0'));
                            quantity.val(value.value()).trigger('change');
                        }).trigger('change');

                        quantity.on('change', function(e) {
                            var el = $(e.currentTarget);
                            var valueNum = parseInt(el.val());
                            if (valueNum < 1) {
                                el.val(1);
                                quantityText.val(1).trigger('change');
                            }
                        }).trigger('change');
                    });
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
    });

    $('#product-form').on('click', '.product-delete', function() {
        var productId = $(this).data('product-id');
        $('#product-' + productId).remove();
    });
</script>
@endsection
