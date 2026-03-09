<?php

namespace App\Observers;

use App\Models\MasterKomponen;
use App\Models\MutasiBarang;

class MutasiBarangObserver
{
    /**
     * Handle the MutasiBarang "created" event.
     */
    public function created(MutasiBarang $mutasi): void
    {
        $komponen = $mutasi->komponen;
        
        if ($mutasi->jenis === 'masuk') {
            // stok bertambah untuk mutasi masuk
            $komponen->increment('stok', $mutasi->jumlah);
        } else {
            // mutasi keluar mengurangi stok, pastikan tidak minus
            if ($komponen->stok >= $mutasi->jumlah) {
                $komponen->decrement('stok', $mutasi->jumlah);
            } else {
                $mutasi->delete();
                throw new \Exception("Stok tidak cukup. Stok tersedia: {$komponen->stok}, diminta: {$mutasi->jumlah}");
            }
        }
    }

    /**
     * Handle the MutasiBarang "deleted" event.
     */
    public function deleted(MutasiBarang $mutasi): void
    {
        $komponen = $mutasi->komponen;
        
        // Rollback stok
        if ($mutasi->jenis === 'masuk') {
            $komponen->decrement('stok', $mutasi->jumlah);
        } else {
            $komponen->increment('stok', $mutasi->jumlah);
        }
    }

    /**
     * Handle the MutasiBarang "restored" event.
     */
    public function restored(MutasiBarang $mutasi): void
    {
        $komponen = $mutasi->komponen;
        
        // Restore stok
        if ($mutasi->jenis === 'masuk') {
            $komponen->increment('stok', $mutasi->jumlah);
        } else {
            $komponen->decrement('stok', $mutasi->jumlah);
        }
    }
}
