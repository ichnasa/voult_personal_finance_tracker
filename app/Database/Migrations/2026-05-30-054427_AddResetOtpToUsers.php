<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResetOtpToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'reset_otp_code' => [
                'type'       => 'VARCHAR',
                'constraint' => '6',
                'null'       => true,
            ],
            'reset_otp_expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['reset_otp_code', 'reset_otp_expires_at']);
    }
}
