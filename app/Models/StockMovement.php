<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMovement extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'stock_movements';

    protected $dates = [
        'movement_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const TRANSACTION_TYPE_SELECT = [
        'adjustment' => 'Adjustment',
        'sale'       => 'Sale',
    ];

    public const MOVEMENT_TYPE_SELECT = [
        'adjustment' => 'Adjustment',
        'in'         => 'In',
        'out'        => 'Out',
    ];

    protected $fillable = [
        'warehouse_id',
        'movement_date',
        'movement_type',
        'product_id',
        'material_id',
        'quantity',
        'transaction_type',
        'reversal_of_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function getMovementDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setMovementDateAttribute($value)
    {
        $this->attributes['movement_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function product()
    {
        return $this->belongsTo(BookVariant::class, 'product_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function reversal_of()
    {
        return $this->belongsTo(self::class, 'reversal_of_id');
    }
}
