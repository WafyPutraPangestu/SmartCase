<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kategori_pelanggan_id',
        'alamat',
        'no_telepon',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategoriPelanggan()
    {
        return $this->belongsTo(KategoriPelanggan::class);
    }
}
