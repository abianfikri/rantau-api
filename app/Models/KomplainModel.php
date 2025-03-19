<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomplainModel extends Model
{
    use HasFactory;
    protected $table = 'mgmt_komplain';
    protected $primaryKey = 'id_pk_komplain';

    protected $fillable = [
        'id_fk_user',
        'komplain_jenis',
        'komplain_deskripsi',
        'komplain_status',
        'komplain_tanggal_buat',
        'komplain_tanggal_proses',
        'komplain_tanggal_selesai',
    ];
}
