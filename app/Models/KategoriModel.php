<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table         = 'kategori';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;

    protected $allowedFields = ['user_id', 'name'];

    /**
     * Get all kategori for a specific user as dropdown options
     */
    public function getDropdown(int $userId): array
    {
        $results = $this->where('user_id', $userId)
            ->orderBy('name', 'ASC')
            ->findAll();

        $options = [];
        foreach ($results as $row) {
            $options[$row['id']] = $row['name'];
        }
        return $options;
    }

    /**
     * Get all kategori for a specific user
     */
    public function getByUser(int $userId): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    /**
     * Create default kategori for a new user
     */
    public function createDefaultsForUser(int $userId): void
    {
        $defaults = [
            'Makanan',
            'Transportasi',
            'Hiburan',
            'Pendidikan',
            'Belanja',
            'Tagihan',
        ];

        $now = date('Y-m-d H:i:s');
        $data = [];
        foreach ($defaults as $name) {
            $data[] = [
                'user_id'    => $userId,
                'name'       => $name,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $this->insertBatch($data);
    }
}
