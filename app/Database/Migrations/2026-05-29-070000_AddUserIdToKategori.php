<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToKategori extends Migration
{
    public function up()
    {
        $this->forge->addColumn('kategori', [
            'user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
                'after'    => 'id',
            ],
        ]);

        // Add index and foreign key
        $this->forge->processIndexes('kategori');
        $this->db->query('ALTER TABLE kategori ADD INDEX idx_kategori_user_id (user_id)');
        $this->db->query('ALTER TABLE kategori ADD CONSTRAINT fk_kategori_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down()
    {
        // Drop foreign key and column
        $this->db->query('ALTER TABLE kategori DROP FOREIGN KEY fk_kategori_user_id');
        $this->db->query('ALTER TABLE kategori DROP INDEX idx_kategori_user_id');
        $this->forge->dropColumn('kategori', 'user_id');
    }
}
