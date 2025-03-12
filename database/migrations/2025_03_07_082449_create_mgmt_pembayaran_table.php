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
        Schema::create('mgmt_pembayaran', function (Blueprint $table) {
            $table->id("id_pk_pembayaran");
            $table->integer("id_fk_user")->nullable();
            $table->string("pembayaran_nama", 100)->nullable();
            $table->decimal("pembayaran_nominal", 20, 0)->nullable()->default(0);
            $table->string("pembayaran_durasi", 100)->nullable();
            $table->string("pembayaran_durasi_nama", 100)->nullable();
            $table->string("pembayaran_status")->nullable()->default("Pending");
            $table->text("pembayaran_bukti_bayar")->nullable();
            $table->date("pembayaran_tanggal_buat")->nullable();
            $table->date("pembayaran_tanggal_jatuh_tempo")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mgmt_pembayaran');
    }
};
