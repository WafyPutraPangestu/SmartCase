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
        'kategori_gangguan_nama',    
        'kategori_pelanggan_nama', 
        'kode_tiket',  
        'judul',
        'deskripsi',
        'prioritas',
        'ml_confidence',              
        'ml_features',                
        'ml_predicted_at',            
        'status',
    ];
    protected $casts = [
        'ml_features' => 'array',
        'ml_predicted_at' => 'datetime',
        'ml_confidence' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategoriGangguan()
    {
        return $this->belongsTo(KategoriGangguan::class);
    }
    // Di dalam class Ticket, tambahkan method ini:

protected static function boot()
{
    parent::boot();
    
    static::creating(function ($ticket) {
        if (empty($ticket->kode_tiket)) {
            $ticket->kode_tiket = self::generateKodeTiket();
        }
    });
}

private static function generateKodeTiket()
{
    $lastTicket = self::orderBy('id', 'desc')->first();
    $nextId = $lastTicket ? $lastTicket->id + 1 : 1;
    
    return 'TKT-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
}
}
