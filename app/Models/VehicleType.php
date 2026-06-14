<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleType extends Model
{

    protected $table = 'vehicle_types';

    protected $fillable = [
        'jenis',
        'perjam_pertama',
        'perjam_berikutnya',
        'max_perhari',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'id_jenis');
    }
}
