<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTabunganTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'wishlist_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'nama_tabungan' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'target_nominal' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'nominal_terkumpul' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'deadline' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['proses', 'tercapai'],
                'default'    => 'proses',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('wishlist_id', 'wishlist', 'id', '', 'SET NULL');
        $this->forge->createTable('tabungan');
    }

    public function down()
    {
        $this->forge->dropTable('tabungan');
    }
}
