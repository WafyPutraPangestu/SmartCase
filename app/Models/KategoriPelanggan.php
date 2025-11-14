<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPelanggan extends Model
{
    use HasFactory;

    protected $table = 'kategori_pelanggans';
    protected $fillable = ['nama_kategori'];

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }
}