<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBudgetingTable extends Migration
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
            'kategori_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'bulan' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'tahun' => [
                'type'       => 'SMALLINT',
                'unsigned'   => true,
            ],
            'nominal_budget' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
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
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('kategori_id', 'kategori', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('budgeting');
    }

    public function down()
    {
        $this->forge->dropTable('budgeting');
    }
}
