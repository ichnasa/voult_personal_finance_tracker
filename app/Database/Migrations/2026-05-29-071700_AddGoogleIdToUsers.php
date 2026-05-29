<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGoogleIdToUsers extends Migration
{
    public function up()
    {
        // Add google_id column
        $this->forge->addColumn('users', [
            'google_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'email',
            ],
        ]);

        // Make password nullable (for OAuth-only users)
        $this->forge->modifyColumn('users', [
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);

        // Add unique index on google_id
        $this->db->query('ALTER TABLE users ADD UNIQUE INDEX idx_users_google_id (google_id)');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE users DROP INDEX idx_users_google_id');
        $this->forge->dropColumn('users', 'google_id');

        // Revert password to NOT NULL
        $this->forge->modifyColumn('users', [
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
        ]);
    }
}
