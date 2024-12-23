<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAgendasTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 5, 'unsigned' => true, 'auto_increment' => true],
            'judul'            => ['type' => 'VARCHAR', 'constraint' => 255],
            'nama_pelanggan'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'lokasi'           => ['type' => 'VARCHAR', 'constraint' => 255],
            'tanggal_kegiatan' => ['type' => 'DATE'],
            'jam_kegiatan'     => ['type' => 'TIME'],
            'lampiran'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'link'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'tgl_reminder'     => ['type' => 'DATE'],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true); // Primary key
        $this->forge->createTable('agendas');
    }

    public function down()
    {
        $this->forge->dropTable('agendas');
    }
}
