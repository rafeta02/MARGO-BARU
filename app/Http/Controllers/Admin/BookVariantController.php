<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyBookVariantRequest;
use App\Http\Requests\StoreBookVariantRequest;
use App\Http\Requests\UpdateBookVariantRequest;
use App\Models\Book;
use App\Models\BookVariant;
use App\Models\Halaman;
use App\Models\Jenjang;
use App\Models\Kurikulum;
use App\Models\Semester;
use App\Models\Unit;
use App\Models\Cover;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\SalesOrder;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BookVariantController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('book_variant_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = BookVariant::with(['book', 'parent', 'jenjang', 'semester', 'kurikulum', 'halaman', 'warehouse', 'unit'])->select(sprintf('%s.*', (new BookVariant)->table));

            if (!empty($request->type)) {
                $query->where('type', $request->type);
            }
            if (!empty($request->semester)) {
                $query->where('semester_id', $request->semester);
            }
            if (!empty($request->cover)) {
                $query->where('cover_id', $request->cover);
            }
            if (!empty($request->kurikulum)) {
                $query->where('kurikulum_id', $request->kurikulum);
            }
            if (!empty($request->kelas)) {
                $query->where('kelas_id', $request->kelas);
            }
            if (!empty($request->mapel)) {
                $query->where('mapel_id', $request->mapel);
            }

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'book_variant_show';
                $editGate      = 'book_variant_edit';
                $deleteGate    = 'book_variant_delete';
                $crudRoutePart = 'book-variants';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });

            $table->editColumn('type', function ($row) {
                return $row->type ? BookVariant::TYPE_SELECT[$row->type] : '';
            });

            $table->addColumn('jenjang_code', function ($row) {
                return $row->jenjang ? $row->jenjang->code : '';
            });

            $table->addColumn('semester_name', function ($row) {
                return $row->semester ? $row->semester->name : '';
            });

            $table->addColumn('kurikulum_code', function ($row) {
                return $row->kurikulum ? $row->kurikulum->code : '';
            });

            $table->addColumn('halaman_name', function ($row) {
                return $row->halaman ? $row->halaman->name : '';
            });

            $table->editColumn('stock', function ($row) {
                return $row->stock ? $row->stock : 0;
            });
            $table->editColumn('price', function ($row) {
                return $row->price ? $row->price : '';
            });
            $table->editColumn('cost', function ($row) {
                return $row->cost ? $row->cost : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'jenjang', 'semester', 'kurikulum', 'halaman', 'buku']);

            return $table->make(true);
        }

        $jenjangs = Jenjang::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $kurikulums = Kurikulum::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mapels = Mapel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $kelas = Kelas::pluck('name', 'id');

        $covers = Cover::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $semesters = Semester::where('status', 1)->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.bookVariants.index', compact('covers', 'jenjangs', 'kelas', 'kurikulums', 'mapels', 'semesters'));
    }

    public function create()
    {
        abort_if(Gate::denies('book_variant_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $books = Book::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $parents = BookVariant::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $jenjangs = Jenjang::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $kurikulums = Kurikulum::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $isis = Isi::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $covers = Cover::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mapels = Mapel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $kelas = Kela::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $halamen = Halaman::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $semesters = Semester::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $units = Unit::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.bookVariants.create', compact('books', 'covers', 'halamen', 'isis', 'jenjangs', 'kelas', 'kurikulums', 'mapels', 'parents', 'semesters', 'units'));
    }

    public function store(StoreBookVariantRequest $request)
    {
        $bookVariant = BookVariant::create($request->all());

        foreach ($request->input('photo', []) as $file) {
            $bookVariant->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photo');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $bookVariant->id]);
        }

        return redirect()->route('admin.book-variants.index');
    }

    public function edit(BookVariant $bookVariant)
    {
        abort_if(Gate::denies('book_variant_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $books = Book::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $parents = BookVariant::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $jenjangs = Jenjang::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $kurikulums = Kurikulum::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $isis = Isi::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $covers = Cover::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $mapels = Mapel::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $kelas = Kela::pluck('code', 'id')->prepend(trans('global.pleaseSelect'), '');

        $halamen = Halaman::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $semesters = Semester::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $units = Unit::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $bookVariant->load('book', 'parent', 'jenjang', 'kurikulum', 'isi', 'cover', 'mapel', 'kelas', 'halaman', 'semester', 'warehouse', 'unit');

        return view('admin.bookVariants.edit', compact('bookVariant', 'books', 'covers', 'halamen', 'isis', 'jenjangs', 'kelas', 'kurikulums', 'mapels', 'parents', 'semesters', 'units'));
    }

    public function update(UpdateBookVariantRequest $request, BookVariant $bookVariant)
    {
        $bookVariant->update($request->all());

        if (count($bookVariant->photo) > 0) {
            foreach ($bookVariant->photo as $media) {
                if (! in_array($media->file_name, $request->input('photo', []))) {
                    $media->delete();
                }
            }
        }
        $media = $bookVariant->photo->pluck('file_name')->toArray();
        foreach ($request->input('photo', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $bookVariant->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photo');
            }
        }

        return redirect()->route('admin.book-variants.index');
    }

    public function show(BookVariant $bookVariant)
    {
        abort_if(Gate::denies('book_variant_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bookVariant->load('book', 'parent', 'jenjang', 'semester', 'kurikulum', 'halaman', 'warehouse', 'unit');

        return view('admin.bookVariants.show', compact('bookVariant'));
    }

    public function destroy(BookVariant $bookVariant)
    {
        abort_if(Gate::denies('book_variant_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bookVariant->delete();

        return back();
    }

    public function massDestroy(MassDestroyBookVariantRequest $request)
    {
        $bookVariants = BookVariant::find(request('ids'));

        foreach ($bookVariants as $bookVariant) {
            $bookVariant->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('book_variant_create') && Gate::denies('book_variant_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new BookVariant();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function getProducts(Request $request)
    {
        $query = $request->input('q');
        $jenjang = $request->input('jenjang');
        $type = $request->input('type');

        $query = BookVariant::where(function($q) use ($query) {
                    $q->where('code', 'LIKE', "%{$query}%")
                    ->orWhere('name', 'LIKE', "%{$query}%");
                });

        if (!empty($jenjang)) {
            $query->where('jenjang_id', $jenjang);
        }

        if (!empty($type)) {
            if ($type == 'isi') {
                $query->whereIn('type', ['I', 'S']);
            } else if ($type == 'cover') {
                $query->whereIn('type', ['C', 'V']);
            } else {
                $query->whereIn('type', ['L', 'P']);
            }
        }

        $products = $query->orderBy('code', 'ASC')->get();

        $formattedProducts = [];

        foreach ($products as $product) {
            $formattedProducts[] = [
                'id' => $product->id,
                'text' => $product->code,
                'stock' => $product->stock,
                'name' => $product->name,
            ];
        }

        return response()->json($formattedProducts);
    }

    public function getBooks(Request $request)
    {
        $query = $request->input('q');
        $jenjang = $request->input('jenjang');

        $query = BookVariant::whereIn('type', ['L', 'P'])->where(function($q) use ($query) {
                    $q->where('code', 'LIKE', "%{$query}%")
                    ->orWhere('name', 'LIKE', "%{$query}%");
                })->orderBy('code', 'ASC');

        if (!empty($jenjang)) {
            $query->where('jenjang_id', $jenjang);
        }

        $products = $query->get();

        $formattedProducts = [];

        foreach ($products as $product) {
            $formattedProducts[] = [
                'id' => $product->id,
                'text' => $product->code,
                'stock' => $product->stock,
                'name' => $product->name,
            ];
        }

        return response()->json($formattedProducts);
    }

    public function getBook(Request $request)
    {
        $id = $request->input('id');

        $product = BookVariant::find($id);
        $product->load('book', 'jenjang', 'book.cover', 'book.kurikulum');

        return response()->json($product);
    }

    public function getEstimasi(Request $request)
    {
        $query = $request->input('q');
        $semester = $request->input('semester');
        $salesperson = $request->input('salesperson');
        $type = $request->input('type');
        $jenjang = $request->input('jenjang');

        $query = BookVariant::whereHas('estimasi', function ($q) use ($semester, $salesperson, $type) {
                    $q->where('salesperson_id', $salesperson)
                    ->where('payment_type', $type)
                    ->where('semester_id', $semester);
                })->where(function($q) use ($query) {
                    $q->where('code', 'LIKE', "%{$query}%")
                    ->orWhere('name', 'LIKE', "%{$query}%");
                })->orderBy('code', 'ASC');

        if (!empty($jenjang)) {
            $query->where('jenjang_id', $jenjang);
        }

        $products = $query->get();

        $formattedProducts = [];

        foreach ($products as $product) {
            $formattedProducts[] = [
                'id' => $product->id,
                'text' => $product->code,
                'stock' => $product->stock,
                'name' => $product->name,
            ];
        }

        return response()->json($formattedProducts);
    }

    public function getInfoEstimasi(Request $request)
    {
        $id = $request->input('id');
        $semester = $request->input('semester');
        $salesperson = $request->input('salesperson');
        $type = $request->input('type');

        $product = BookVariant::join('sales_orders', 'sales_orders.product_id', '=', 'book_variants.id')
                ->where('book_variants.id', $id)
                ->where('sales_orders.semester_id', $semester)
                ->where('sales_orders.salesperson_id', $salesperson)
                ->where('sales_orders.payment_type', $type)
                ->first(['book_variants.*', 'sales_orders.quantity as estimasi', 'sales_orders.moved as terkirim', 'sales_orders.id as order_id']);
        $product->load('book', 'jenjang', 'book.cover', 'book.kurikulum');

        return response()->json($product);
    }

    public function getDelivery(Request $request)
    {
        $query = $request->input('q');
        $delivery = $request->input('delivery');

        $products = BookVariant::whereHas('dikirim', function ($q) use ($delivery) {
                    $q->where('delivery_order_id', $delivery);
                })->where(function($q) use ($query) {
                    $q->where('code', 'LIKE', "%{$query}%")
                    ->orWhere('name', 'LIKE', "%{$query}%");
                })->orderBy('code', 'ASC')->get();

        $formattedProducts = [];

        foreach ($products as $product) {
            $formattedProducts[] = [
                'id' => $product->id,
                'text' => $product->code,
                'stock' => $product->stock,
                'name' => $product->name,
            ];
        }

        return response()->json($formattedProducts);
    }

    public function getInfoDelivery(Request $request)
    {
        $id = $request->input('id');
        $delivery = $request->input('delivery');

        $product = BookVariant::join('delivery_order_items', 'delivery_order_items.product_id', '=', 'book_variants.id')
                ->join('sales_orders', 'sales_orders.id', '=', 'delivery_order_items.sales_order_id')
                ->where('book_variants.id', $id)
                ->where('delivery_order_items.delivery_order_id', $delivery)
                ->first(['book_variants.*', 'delivery_order_items.quantity as quantity', 'delivery_order_items.id as delivery_item_id', 'sales_orders.quantity as estimasi', 'sales_orders.moved as terkirim']);
        $product->load('book', 'jenjang', 'book.cover', 'book.kurikulum');

        return response()->json($product);
    }

    public function getRetur(Request $request)
    {
        $query = $request->input('q');
        $semester = $request->input('semester');
        $salesperson = $request->input('salesperson');

        $products = BookVariant::whereHas('dikirim', function ($q) use ($semester, $salesperson) {
                    $q->where('semester_id', $semester)
                    ->where('salesperson_id', $salesperson);
                })->where(function($q) use ($query) {
                    $q->where('code', 'LIKE', "%{$query}%")
                    ->orWhere('name', 'LIKE', "%{$query}%");
                })->orderBy('code', 'ASC')->get();

        $formattedProducts = [];

        foreach ($products as $product) {
            $formattedProducts[] = [
                'id' => $product->id,
                'text' => $product->code,
                'stock' => $product->stock,
                'name' => $product->name,
            ];
        }

        return response()->json($formattedProducts);
    }

    public function getInfoRetur(Request $request)
    {
        $id = $request->input('id');
        $semester = $request->input('semester');
        $salesperson = $request->input('salesperson');

        $product = BookVariant::join('sales_orders', 'sales_orders.product_id', '=', 'book_variants.id')
                ->where('book_variants.id', $id)
                ->where('sales_orders.semester_id', $semester)
                ->where('sales_orders.salesperson_id', $salesperson)
                ->first(['book_variants.*', 'sales_orders.moved as terkirim',
                    'sales_orders.retur as retur', 'sales_orders.id as order_id'
                ]);

        $product->load('book', 'jenjang', 'book.cover', 'book.kurikulum');

        return response()->json($product);
    }

    public function getEditRetur(Request $request)
    {
        $query = $request->input('q');
        $retur = $request->input('retur');

        $products = BookVariant::whereHas('diretur', function ($q) use ($retur) {
                    $q->where('retur_id', $retur);
                })->where('code', 'like', "%{$query}%")
                ->orderBy('code', 'ASC')
                ->get();

        $formattedProducts = [];

        foreach ($products as $product) {
            $formattedProducts[] = [
                'id' => $product->id,
                'text' => $product->code,
                'stock' => $product->stock,
                'name' => $product->name,
            ];
        }

        return response()->json($formattedProducts);
    }

    public function getInfoEditRetur(Request $request)
    {
        $id = $request->input('id');
        $retur = $request->input('retur');

        $product = BookVariant::join('return_good_items', 'return_good_items.product_id', '=', 'book_variants.id')
                ->join('sales_orders', 'sales_orders.id', '=', 'return_good_items.sales_order_id')
                ->where('book_variants.id', $id)
                ->where('return_good_items.retur_id', $retur)
                ->first(['book_variants.*', 'return_good_items.quantity as quantity', 'return_good_items.id as retur_item_id',
                    'sales_orders.retur as retur', 'sales_orders.moved as terkirim']);
        $product->load('book', 'jenjang', 'book.cover', 'book.kurikulum');

        return response()->json($product);
    }

    public function getAdjustment(Request $request)
    {
        $query = $request->input('q');
        $adjustment = $request->input('adjustment');

        $products = BookVariant::whereHas('adjustment', function ($q) use ($adjustment) {
                    $q->where('stock_adjustment_id', $adjustment);
                })->where(function($q) use ($query) {
                    $q->where('code', 'LIKE', "%{$query}%")
                    ->orWhere('name', 'LIKE', "%{$query}%");
                })->orderBy('code', 'ASC')->get();

        $formattedProducts = [];

        foreach ($products as $product) {
            $formattedProducts[] = [
                'id' => $product->id,
                'text' => $product->code,
                'stock' => $product->stock,
                'name' => $product->name,
            ];
        }

        return response()->json($formattedProducts);
    }

    public function getInfoAdjustment(Request $request)
    {
        $id = $request->input('id');
        $adjustment = $request->input('adjustment');

        $product = BookVariant::join('stock_adjustment_details', 'stock_adjustment_details.product_id', '=', 'book_variants.id')
                ->where('book_variants.id', $id)
                ->where('stock_adjustment_details.stock_adjustment_id', $adjustment)
                ->first(['book_variants.*', 'stock_adjustment_details.quantity as quantity', 'stock_adjustment_details.id as adjustment_detail_id']);
        $product->load('book', 'jenjang', 'book.cover', 'book.kurikulum');

        return response()->json($product);
    }

    public function getCetak(Request $request)
    {
        $query = $request->input('q');
        $type = $request->input('type');

        $query = BookVariant::whereHas('estimasi_produksi', function ($q) {
                    $q->where('estimasi', '>', 0);
                })->where(function($q) use ($query) {
                    $q->where('code', 'LIKE', "%{$query}%")
                    ->orWhere('name', 'LIKE', "%{$query}%");
                });

        if (!empty($type)) {
            if ($type == 'isi') {
                $query->whereIn('type', ['I', 'S']);
            } else if ($type == 'cover') {
                $query->whereIn('type', ['C', 'V']);
            }
        } else {
            $query->whereIn('type', ['L', 'P']);
        }

        $products = $query->orderBy('code', 'ASC')->get();

        $formattedProducts = [];

        foreach ($products as $product) {
            $formattedProducts[] = [
                'id' => $product->id,
                'text' => $product->code,
                'stock' => $product->stock,
                'name' => $product->name,
            ];
        }

        return response()->json($formattedProducts);
    }

    public function getInfoCetak(Request $request)
    {
        $id = $request->input('id');

        $product = BookVariant::find($id);
        $product->load('book', 'jenjang', 'cover', 'kurikulum', 'estimasi_produksi');

        return response()->json($product);
    }

    public function getInfoFinishing(Request $request)
    {
        $id = $request->input('id');

        $product = BookVariant::withMin('child as finishing_stock', 'stock')->find($id);
        $product->load('book', 'jenjang', 'cover', 'kurikulum', 'estimasi_produksi');

        return response()->json($product);
    }
}
