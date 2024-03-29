@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <h1>Faktur Retur</h1>
    </div>

    <div class="card-body">

        @if (session()->has('error-message'))
            <p class="text-danger">
                {{session()->get('error-message')}}
            </p>
        @endif

        <form class="form-prevent-multiple-submits" method="POST" action="{{ route("admin.return-goods.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="no_retur">{{ trans('cruds.returnGood.fields.no_retur') }}</label>
                        <input class="form-control {{ $errors->has('no_retur') ? 'is-invalid' : '' }}" type="text" name="no_retur" id="no_retur" value="{{ old('no_retur', $no_retur) }}" readonly>
                        @if($errors->has('no_retur'))
                            <span class="text-danger">{{ $errors->first('no_retur') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.returnGood.fields.no_retur_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="date">{{ trans('cruds.returnGood.fields.date') }}</label>
                        <input class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}" type="text" name="date" id="date" value="{{ old('date', $today) }}" required>
                        @if($errors->has('date'))
                            <span class="text-danger">{{ $errors->first('date') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.returnGood.fields.date_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="semester_id">{{ trans('cruds.returnGood.fields.semester') }}</label>
                        <select class="form-control select2 {{ $errors->has('semester') ? 'is-invalid' : '' }}" name="semester_id" id="semester_id" required>
                            @foreach($semesters as $id => $entry)
                                <option value="{{ $id }}" {{ old('semester_id', setting('current_semester')) == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('semester'))
                            <span class="text-danger">{{ $errors->first('semester') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.returnGood.fields.semester_helper') }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="salesperson_id">{{ trans('cruds.returnGood.fields.salesperson') }}</label>
                        <select class="form-control select2 {{ $errors->has('salesperson') ? 'is-invalid' : '' }}" name="salesperson_id" id="salesperson_id" required>
                            @foreach($salespeople as $id => $entry)
                                <option value="{{ $id }}" {{ old('salesperson_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('salesperson'))
                            <span class="text-danger">{{ $errors->first('salesperson') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.returnGood.fields.salesperson_helper') }}</span>
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
            <div class="row mt-3 pt-2 ml-2" style="margin-right:140px">
                <div class="col-md-10 text-right">
                    <span class="text-sm"><b>Total Oplah Retur</b></span>
                </div>
                <div class="col-2 text-right">
                    <p class="mb-1">
                        <strong id="product-total-quantity">{{ angka(0) }}</strong>
                    </p>
                </div>
            </div>
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
                    url: "{{ route('admin.book-variants.getRetur') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            semester: $('#semester_id').val(),
                            salesperson: $('#salesperson_id').val()
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
            console.log(e.params.data.name)

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
                url: "{{ route('admin.book-variants.getInfoRetur') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    id: productId,
                    semester: $('#semester_id').val(),
                    salesperson: $('#salesperson_id').val()
                },
                success: function(product) {
                    var formHtml = `
                        <div class="item-product" id="product-${product.id}">
                            <div class="row">
                                <div class="col-6 align-self-center">
                                    <h6 class="text-sm product-name mb-1">(${product.book_type}) ${product.short_name}</h6>
                                    <p class="mb-0 text-sm">
                                        Code : <strong>${product.code}</strong>
                                    </p>
                                    <p class="mb-0 text-sm">
                                        Jenjang - Kurikulum : <strong>${product.jenjang.name} - ${product.kurikulum.name}</strong>
                                    </p>
                                    <p class="mb-0 text-sm">
                                        <strong>TERKIRIM : ${product.terkirim}</strong>
                                    </p>
                                    <p class="mb-0 text-sm">
                                        <strong>RETUR : ${product.retur}</strong>
                                    </p>
                                </div>
                                <div class="col offset-1 row align-items-end align-self-center">
                                    <div class="col" style="max-width: 160px">
                                        <p class="mb-0 text-sm">Retur</p>
                                        <div class="form-group text-field m-0">
                                            <div class="text-field-input px-2 py-0">
                                                <input type="hidden" name="products[]" value="${product.id}">
                                                <input type="hidden" name="orders[]" value="${product.order_id}">
                                                <input class="quantity" type="hidden" name="quantities[]" data-max="${product.terkirim - product.retur}" value="1">
                                                <input class="form-control text-center quantity_text" type="text" name="quantity_text[]" value="1" required>
                                                <label class="text-field-border"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col" style="max-width: 210px">
                                        <p class="mb-0 text-sm">Price</p>
                                        <div class="form-group text-field m-0">
                                            <div class="text-field-input px-2 py-0 pr-3">
                                                <span class="text-sm mr-1">Rp</span>
                                                <input class="price" type="hidden" name="prices[]" value="${product.price}">
                                                <input class="form-control text-right price_text" type="text" name="price_text[]" value="${product.price}">
                                                <label class="text-field-border"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto pl-5">
                                        <button type="button" class="btn btn-danger btn-sm product-delete" data-product-id="${product.id}" tabIndex="-1">
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

                    sortItems();

                    productItem.each(function(index, item) {
                        var product = $(item);
                        var quantity = product.find('.quantity');
                        var quantityText = product.find('.quantity_text');
                        var price = product.find('.price');
                        var priceText = product.find('.price_text');

                        quantityText.on('input change', function(e) {
                            var value = numeral(e.target.value);

                            quantityText.val(value.format('0,0'));
                            quantity.val(value.value()).trigger('change');
                            calculateTotalQuantity();
                        }).trigger('change');

                        quantity.on('change', function(e) {
                            var el = $(e.currentTarget);
                            var valueNum = parseInt(el.val());
                            if (valueNum < 0) {
                                el.val(0);
                                quantityText.val(0).trigger('change');
                            }
                        }).trigger('change');

                        priceText.on('input change', function(e) {
                            var value = numeral(e.target.value);

                            priceText.val(value.format('0,0'));
                            price.val(value.value()).trigger('change');
                        }).trigger('change');

                        price.on('change input', function(e) {
                            var el = $(e.currentTarget);
                            var max = parseInt(el.data('max'));
                            var valueNum = parseInt(el.val());

                            if (valueNum < 0) {
                                el.val(0);
                                priceText.val(0).trigger('change');
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
        calculateTotalQuantity();
    });

    function sortItems() {
        var productForm = $('#product-form');
        var items = productForm.find('.item-product').get();

        items.sort(function(a, b) {
            const idA = parseInt(a.id.split('-')[1]);
            const idB = parseInt(b.id.split('-')[1]);
            return idA - idB;
        });

        productForm.empty().append(items);
    }

    function calculateTotalQuantity() {
        var productForm = $('#product-form');
        var productTotalQuantity = $('#product-total-quantity');

        var total_quantity = 0;

        productForm.children().each(function(i, item) {
            var product = $(item);
            var quantity = parseInt(product.find('.quantity').val() || 0)
            total_quantity += quantity;
        });
        console.log(total_quantity);
        productTotalQuantity.html(numeral(total_quantity).format('0,0'));
    };
</script>
@endsection

