<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceItem extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'invoice_items';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'invoice_id',
        'delivery_order_id',
        'delivery_order_item_id',
        'semester_id',
        'salesperson_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'discount',
        'total_discount',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function delivery_order()
    {
        return $this->belongsTo(DeliveryOrder::class, 'delivery_order_id');
    }

    public function delivery_order_item()
    {
        return $this->belongsTo(DeliveryOrderItem::class, 'delivery_order_item_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function salesperson()
    {
        return $this->belongsTo(Salesperson::class, 'salesperson_id');
    }

    public function product()
    {
        return $this->belongsTo(BookVariant::class, 'product_id');
    }
}
