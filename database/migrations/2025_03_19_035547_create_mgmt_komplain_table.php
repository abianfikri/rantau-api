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
        Schema::create('mgmt_komplain', function (Blueprint $table) {
            $table->id("id_pk_komplain");
            $table->integer("id_fk_user")->nullable();
            $table->string("komplain_jenis", 300)->nullable();
            $table->text("komplain_deskripsi")->nullable();
            $table->string("komplain_status")->nullable()->default("Diajukan")->comment("Diajukan, Diproses, Done");
            $table->date("komplain_tanggal_buat")->nullable();
            $table->date("komplain_tanggal_proses")->nullable();
            $table->date("komplain_tanggal_selesai")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mgmt_komplain');
    }
};
