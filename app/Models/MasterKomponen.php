<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKomponen extends Model
{
    protected $table = 'master_komponen';

    protected $fillable = [
        'kode_komponen',
        'nama_komponen',
        'tipe',
        'satuan',
        'stok',
        'stok_minimal',
        'rak',
        'lokasi',
        'departemen_id',
        'harga'
    ];
    // hanya dua tipe sekarang, untuk konsistensi dengan tabel mutasi
    const JENIS_MASUK = ['masuk'];
    const JENIS_KELUAR = ['keluar'];
    public function mutasi()
    {
        return $this->hasMany(MutasiBarang::class, 'id_komponen');
    }
    public function departemen()
    {
        return $this->belongsTo(Departemen::class,'departemen_id');
    }

    public function isStokRendah(): bool
    {
        return $this->stok <= $this->stok_minimal;
    }
}
