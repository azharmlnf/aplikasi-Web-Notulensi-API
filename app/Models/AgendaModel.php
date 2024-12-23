<?php

namespace App\Models;

use CodeIgniter\Model;

class AgendaModel extends Model
{
    protected $table = 'agendas';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'judul', 
        'nama_pelanggan', 
        'lokasi', 
        'tanggal_kegiatan', 
        'jam_kegiatan', 
        'lampiran', 
        'link', 
        'tgl_reminder', 
 
    ];
    protected $useTimestamps = true;
}
