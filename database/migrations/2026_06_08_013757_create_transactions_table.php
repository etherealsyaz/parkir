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
    Schema::create('transactions', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->foreignId('id_lokasi')->constrained('locations');
        $table->string('no_tiket', 255)->unique();
        $table->string('no_polisi', 15)->nullable();
        $table->foreignId('id_jenis')->constrained('vehicle__types');
        $table->dateTime('masuk');
        $table->dateTime('keluar')->nullable();
        $table->integer('perjam_pertama')->default(0);
        $table->integer('perjam_berikutnya')->default(0);
        $table->integer('max_perhari')->default(0);
        $table->integer('total_jam')->nullable();
        $table->integer('total_bayar')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
