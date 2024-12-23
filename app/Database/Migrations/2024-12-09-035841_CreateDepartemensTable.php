<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDepartemensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'constraint'=>5,
                'unsigned'=>true,
                'auto_increment'=>true
            ],
            'nama_departemen'=>[
                'type'=>'VARCHAR',
                'constraint'=>'100',
            ],
            'deskripsi'=>[
                'type'=>'VARCHAR',
                'constraint'=>'100',
                'null' => true,  // Make the column nullable
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('departemens');
    }

    public function down()
    {
        $this->forge->dropTable('departemens');
    }
}

