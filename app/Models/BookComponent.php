<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\CreatedUpdatedBy;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookComponent extends Model
{
    use SoftDeletes, Auditable, HasFactory, CreatedUpdatedBy;

    public $table = 'book_components';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const TYPE_SELECT = [
        'I' => 'Isi LKS',
        'C' => 'Cover LKS',
        'S' => 'Isi Pegangan Guru',
        'V' => 'Cover Pegangan Guru',
        'U' => 'Isi Kunci',
    ];

    public const LKS_TYPE = [
        'C' => 'Cover LKS',
        'I' => 'Isi LKS',
    ];

    public const PG_TYPE = [
        'V' => 'Cover PG',
        'S' => 'Isi PG',
    ];

    public const KUNCI_TYPE = [
        'U' => 'Isi Kunci',
    ];

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'jenjang_id',
        'kurikulum_id',
        'isi_id',
        'cover_id',
        'mapel_id',
        'kelas_id',
        'halaman_id',
        'semester_id',
        'warehouse_id',
        'stock',
        'unit_id',
        'price',
        'cost',
        'status',
        'created_by_id',
        'updated_by_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function jenjang()
    {
        return $this->belongsTo(Jenjang::class, 'jenjang_id');
    }

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class, 'kurikulum_id');
    }

    public function isi()
    {
        return $this->belongsTo(Isi::class, 'isi_id');
    }

    public function cover()
    {
        return $this->belongsTo(Cover::class, 'cover_id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function halaman()
    {
        return $this->belongsTo(Halaman::class, 'halaman_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function material_of()
    {
        return $this->belongsToMany(BookVariant::class);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updated_by()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public static function generateCode($key, $code)
    {
        $base = substr($code, 0, 14);
        $isi = substr($code, 15, 3);
        $cover = substr($code, 18, 3);

        if (!$cover) {
            $cover = $isi;
        }

        if ($key == 'I' || $key == 'S' || $key == 'K' || $key == 'U') {
            return $key. '-' . $base. '/'. $isi;
        }

        if ($key == 'C' || $key == 'V') {
            return $key. '-' . $base. '/'. $cover;
        }
    }

    public static function generateName($key, $jenjang_id, $kurikulum_id, $mapel_id, $kelas_id, $semester_id, $isi_id, $cover_id)
    {
        $jenjang = Jenjang::find($jenjang_id)->name ?? 'Tidak Ada';
        $kurikulum = Kurikulum::find($kurikulum_id)->name ?? 'Tidak Ada';
        $mapel = Mapel::find($mapel_id)->name ?? 'Tidak Ada';
        $kelas = Kelas::find($kelas_id)->name ?? 'Tidak Ada';
        $semester = Semester::find($semester_id)->name ?? 'Tidak Ada';
        $isi = Isi::find($isi_id)->name ?? 'Tidak Ada';
        $cover = Cover::find($cover_id)->name ?? 'Tidak Ada';

        if ($key == 'K') {
            return BookVariant::TYPE_SELECT[$key]. ' - '. $jenjang. ' - '. $kurikulum. ' - '. $mapel. ' - ' .$kelas. ' - '. $semester. ' - ('. $isi .') ';
        }

        if ($key == 'I' || $key == 'S' || $key == 'U') {
            return BookComponent::TYPE_SELECT[$key]. ' - '. $jenjang. ' - '. $kurikulum. ' - '. $mapel. ' - ' .$kelas. ' - '. $semester. ' - ('. $isi .') ';
        }

        if ($key == 'C' || $key == 'V') {
            return BookComponent::TYPE_SELECT[$key]. ' - '. $jenjang. ' - '. $kurikulum. ' - '. $mapel. ' - ' .$kelas. ' - '. $semester. ' - ('. $cover .') ';
        }
    }
}
