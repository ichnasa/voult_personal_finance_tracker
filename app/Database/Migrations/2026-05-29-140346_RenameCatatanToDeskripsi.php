<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameCatatanToDeskripsi extends Migration
{
    public function up()
    {
        $fields = [
            'catatan' => [
                'name' => 'deskripsi',
                'type' => 'TEXT',
                'null' => true,
            ],
        ];

        $this->forge->modifyColumn('pemasukan', $fields);
        $this->forge->modifyColumn('pengeluaran', $fields);
    }

    public function down()
    {
        $fields = [
            'deskripsi' => [
                'name' => 'catatan',
                'type' => 'TEXT',
                'null' => true,
            ],
        ];

        $this->forge->modifyColumn('pemasukan', $fields);
        $this->forge->modifyColumn('pengeluaran', $fields);
    }
}
