<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWishlistTable extends Migration
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
            'nama_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'harga_target' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'prioritas' => [
                'type'       => 'ENUM',
                'constraint' => ['rendah', 'sedang', 'tinggi'],
                'default'    => 'sedang',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['belum_mulai', 'menabung', 'tercapai'],
                'default'    => 'belum_mulai',
            ],
            'catatan' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->createTable('wishlist');
    }

    public function down()
    {
        $this->forge->dropTable('wishlist');
    }
}
