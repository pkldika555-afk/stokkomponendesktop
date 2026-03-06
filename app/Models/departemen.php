<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    protected $table = 'departemen';

    protected $fillable = [
        'nama_departemen'
    ];

    public function mutasiAsal()
    {
        return $this->hasMany(MutasiBarang::class, 'id_departemen_asal');
    }
    public function mutasiTujuan()
    {
        return $this->hasMany(MutasiBarang::class, 'id_departemen_tujuan');
    }
    public function komponen()
    {
        return $this->hasMany(MasterKomponen::class, 'departemen_id');
    }
}