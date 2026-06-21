<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriPelaporan extends Model
{
    protected $table = 'kategori_pelaporan';

    protected $fillable = ['nama_kategori'];

    public function laporan(): HasMany
    {
        return $this->hasMany(Laporan::class, 'kategori_pelaporan_id');
    }
}
