<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;

class Cetak extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'cetaks';

    public const BULAN_ROMAWI = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");

    public const TYPE_SELECT = [
        'isi'   => 'Isi',
        'cover' => 'Cover',
    ];

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'no_spc',
        'date',
        'semester_id',
        'vendor_id',
        'type',
        'total_cost',
        'estimasi_oplah',
        'total_oplah',
        'note',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public static function generateNoSPC($semester, $vendor, $type) {
        $data = self::where('semester_id', $semester)->count();
        $semester = Semester::find($semester);
        $vendor = Vendor::find($vendor);

        $no = !$data ? 1 : ($data + 1);

        $prefix = 'SPC.'. strtoupper($type) .'/'. $vendor->code .'/'.self::BULAN_ROMAWI[Date::now()->format('n')].'/'.strtoupper($semester->code).'/';
        $code = $prefix.sprintf("%06d", $no);

        return $code;
    }

    public function cetak_items()
    {
        return $this->hasMany(CetakItem::class, 'cetak_id');
    }
}
