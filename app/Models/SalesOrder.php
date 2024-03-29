<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\CreatedUpdatedBy;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrder extends Model
{
    use SoftDeletes, Auditable, HasFactory, CreatedUpdatedBy;

    public $table = 'sales_orders';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'no_order',
        'semester_id',
        'salesperson_id',
        'product_id',
        'jenjang_id',
        'kurikulum_id',
        'quantity',
        'moved',
        'retur',
        'created_by_id',
        'updated_by_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getJenKumAttribute()
    {
        $name = $this->jenjang->name. ' - '. $this->kurikulum->name;

        return $name;
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
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

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class, 'jenjang_id');
    }

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class, 'kurikulum_id');
    }

    public static function generateNoOrder($semester, $salesperson) {
        $semester = Semester::find($semester);
        $salesperson = Salesperson::find($salesperson);

        $code = 'ORD/'.strtoupper($salesperson->code ?? 'INTERNAL').'/'.$semester->code;

        return $code;
    }

    public static function generateNoOrderTemp($semester) {
        $semester = Semester::find($semester);
        $code = 'ORD/SALES/'.$semester?->code;
        return $code;
    }
}
