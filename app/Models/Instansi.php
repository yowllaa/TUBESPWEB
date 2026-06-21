<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instansi extends Model
{
    use SoftDeletes;

    protected $table = 'instansi';

    protected $fillable = [
        'user_id',
        'nama_instansi',
        'kategori_instansi',
        'alamat',
        'nomor_telepon',
        'email',
        'latitude',
        'longitude',
        'wilayah',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tindakLanjut(): HasMany
    {
        return $this->hasMany(TindakLanjut::class, 'instansi_id');
    }
}
