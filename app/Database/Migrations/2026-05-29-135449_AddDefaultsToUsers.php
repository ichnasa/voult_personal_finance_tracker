<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDefaultsToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'default_kategori_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'default_metode_pembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'default_kategori_id');
        $this->forge->dropColumn('users', 'default_metode_pembayaran');
    }
}
