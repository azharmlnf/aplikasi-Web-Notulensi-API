<?php

namespace App\Models;

use CodeIgniter\Model;

class NotulenModel extends Model
{
    protected $table            = 'notulens'; // Nama tabel
    protected $primaryKey       = 'id';       // Primary key
    protected $useAutoIncrement = true;       // Auto increment

    // Kolom-kolom yang bisa diisi (whitelist)
    protected $allowedFields    = [
        'isi_notulens',
        'agenda_id',
        'created_at',
        'updated_at'
    ];

    // Gunakan timestamp otomatis
    protected $useTimestamps = true;
}
