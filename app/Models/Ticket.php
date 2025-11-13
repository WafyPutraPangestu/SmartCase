<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kategori_gangguan_id',
        'judul',
        'deskripsi',
        'prioritas',
        'confidence',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategoriGangguan()
    {
        return $this->belongsTo(KategoriGangguan::class);
    }
}
