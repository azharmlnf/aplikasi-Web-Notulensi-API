<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartemenModel extends Model
{
    protected $table = 'departemens';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_departemen', 'deskripsi'];
}
