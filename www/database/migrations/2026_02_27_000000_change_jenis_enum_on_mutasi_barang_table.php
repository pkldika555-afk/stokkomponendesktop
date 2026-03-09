<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // convert existing values into the new simplified types
        DB::table('mutasi_barang')
            ->whereIn('jenis', ['pengambilan','pembelian','retur','repair_kembali'])
            ->update(['jenis' => 'masuk']);
        DB::table('mutasi_barang')
            ->where('jenis', 'internal')
            ->update(['jenis' => 'keluar']);

        // alter the enum column itself
        // the raw statement is used because Blueprint has no enum modification helper
        DB::statement("ALTER TABLE `mutasi_barang` MODIFY `jenis` ENUM('masuk','keluar') NOT NULL DEFAULT 'masuk'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // revert some of the values back to the original set (choose a reasonable default)
        DB::table('mutasi_barang')
            ->where('jenis', 'masuk')
            ->update(['jenis' => 'pengambilan']);
        DB::table('mutasi_barang')
            ->where('jenis', 'keluar')
            ->update(['jenis' => 'internal']);

        DB::statement("ALTER TABLE `mutasi_barang` MODIFY `jenis` ENUM('pengambilan','internal','retur','repair_kembali') NOT NULL DEFAULT 'internal'");
    }
};
