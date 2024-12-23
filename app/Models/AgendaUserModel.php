<?php

namespace App\Models;

use CodeIgniter\Model;

class AgendaUserModel extends Model
{
    protected $table = 'agenda_users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['agenda_id', 'user_id'];

    // Fungsi untuk mendapatkan data personil berdasarkan ID
    public function getPersonilById($id)
    {
        return $this->where('id', $id)->first();
    }
}
