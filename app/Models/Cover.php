<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cover extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'covers';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'code',
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function book_variants()
    {
        return $this->hasMany(BookVariant::class, 'cover_id');
    }

    public function production_estimations()
    {
        return $this->hasManyThrough(
            ProductionEstimation::class,
            BookVariant::class,
            'cover_id',
            'product_id',
            'id',
            'id'
        );
    }
}
