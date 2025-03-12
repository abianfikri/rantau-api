<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranModel extends Model
{
    use HasFactory;
    protected $table = 'mgmt_pembayaran';
    protected $primaryKey = 'id_pk_pembayaran';

    protected $fillable = [
        "id_fk_user",
        "pembayaran_nama",
        "pembayaran_nominal",
        "pembayaran_status",
        "pembayaran_durasi",
        "pembayaran_bukti_bayar",
        "pembayaran_tanggal_buat",
        "pembayaran_tanggal_jatuh_tempo",
    ];
}
