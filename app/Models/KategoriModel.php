<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table         = 'kategori';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;

    protected $allowedFields = ['name'];

    /**
     * Get all kategori as dropdown options
     */
    public function getDropdown(): array
    {
        $results = $this->orderBy('name', 'ASC')->findAll();
        $options = [];
        foreach ($results as $row) {
            $options[$row['id']] = $row['name'];
        }
        return $options;
    }
}
