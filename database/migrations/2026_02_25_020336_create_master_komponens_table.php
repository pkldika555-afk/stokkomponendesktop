<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_komponen', function (Blueprint $table) {
            $table->id();
            $table->string('kode_komponen')->unique();
            $table->string('nama_komponen');
            $table->string('tipe');
            $table->string('satuan')->nullable();
            $table->integer('rak');
            $table->integer('lokasi');
            $table->integer('stok')->default(0);
            $table->integer('stok_minimal')->default(0);
            $table->enum('status', ['bekas', 'baru'])->default('baru');
            $table->timestamps();
            $table->foreignId('departemen_id')->constrained('departemen')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_komponens');
    }
};
