<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriGangguan extends Model
{
    use HasFactory;

    protected $table = 'kategori_gangguan';
    protected $fillable = ['nama_gangguan', 'deskripsi'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}