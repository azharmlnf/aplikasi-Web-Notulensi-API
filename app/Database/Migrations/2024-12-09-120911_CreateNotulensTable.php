<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotulensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'isi_notulens' => [
                'type' => 'LONGTEXT',
                'null' => false,
            ],
            'agenda_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => false,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);

        $this->forge->addKey('id', true); // Primary Key
        $this->forge->addForeignKey('agenda_id', 'agendas', 'id', 'CASCADE', 'CASCADE'); // Foreign Key
        $this->forge->createTable('notulens');
    }

    public function down()
    {
        $this->forge->dropTable('notulens');
    }
}
