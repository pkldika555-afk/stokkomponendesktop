<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MutasiBarang extends Model
{
    protected $table = 'mutasi_barang';

    protected $fillable = [
        'id_komponen',
        'tanggal',
        'jumlah',
        'id_departemen_asal',
        'id_departemen_tujuan',
        'jenis',
        'keterangan'
    ];
    // simplified types: "masuk" akan menambah stok, "keluar" akan mengurangi
    const JENIS_MASUK = ['masuk'];
    const JENIS_KELUAR = ['keluar'];
    public function komponen()
    {
        return $this->belongsTo(MasterKomponen::class, 'id_komponen');
    }

    public function departemenAsal()
    {
        return $this->belongsTo(Departemen::class, 'id_departemen_asal');
    }

    public function departemenTujuan()
    {
        return $this->belongsTo(Departemen::class, 'id_departemen_tujuan');
    }
    public function isMasuk()
    {
        // hanya "masuk" yang dianggap sebagai mutasi masuk
        return $this->jenis === 'masuk';
    }
    public function getLabelJenisAttribute()
    {
        // label sederhana berdasarkan nilai, kapitalisasi awal
        return ucfirst($this->jenis);
    }

}