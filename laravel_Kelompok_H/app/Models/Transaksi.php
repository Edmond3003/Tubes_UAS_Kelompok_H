<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_buku',
        'id_user',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku');
    }
}
