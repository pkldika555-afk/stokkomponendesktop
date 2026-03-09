<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mutasi_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_komponen')->constrained('master_komponen')->cascadeOnDelete();
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->foreignId('id_departemen_asal')->constrained('departemen');
            $table->foreignId('id_departemen_tujuan')->constrained('departemen');
            $table->enum('jenis', ['pengambilan', 'internal', 'retur', 'repair_kembali'])->default('internal');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_barangs');
    }
};
