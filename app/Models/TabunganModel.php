<?php

namespace App\Models;

use CodeIgniter\Model;

class TabunganModel extends Model
{
    protected $table         = 'tabungan';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'user_id', 'wishlist_id', 'nama_tabungan',
        'target_nominal', 'nominal_terkumpul', 'deadline', 'status',
    ];

    public function getByUser(int $userId, int $perPage = 10, array $filters = [])
    {
        $builder = $this->select('tabungan.*, wishlist.nama_barang as wishlist_name')
            ->join('wishlist', 'wishlist.id = tabungan.wishlist_id', 'left')
            ->where('tabungan.user_id', $userId);

        // Filter: tanggal dibuat
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $builder->where('tabungan.created_at >=', $filters['date_from'] . ' 00:00:00')
                    ->where('tabungan.created_at <=', $filters['date_to'] . ' 23:59:59');
        } elseif (!empty($filters['date_from'])) {
            $builder->where('DATE(tabungan.created_at)', $filters['date_from']);
        }

        // Filter: nominal terkumpul
        if (!empty($filters['terkumpul_min']) && !empty($filters['terkumpul_max'])) {
            $builder->where('tabungan.nominal_terkumpul >=', (float)$filters['terkumpul_min'])
                    ->where('tabungan.nominal_terkumpul <=', (float)$filters['terkumpul_max']);
        } elseif (!empty($filters['terkumpul_min'])) {
            $builder->where('tabungan.nominal_terkumpul', (float)$filters['terkumpul_min']);
        }

        // Filter: target nominal
        if (!empty($filters['target_min']) && !empty($filters['target_max'])) {
            $builder->where('tabungan.target_nominal >=', (float)$filters['target_min'])
                    ->where('tabungan.target_nominal <=', (float)$filters['target_max']);
        } elseif (!empty($filters['target_min'])) {
            $builder->where('tabungan.target_nominal', (float)$filters['target_min']);
        }

        // Filter: status
        if (!empty($filters['status'])) {
            $builder->where('tabungan.status', $filters['status']);
        }

        // Sort
        $sortDir = (!empty($filters['sort_dir']) && $filters['sort_dir'] === 'ASC') ? 'ASC' : 'DESC';
        $builder->orderBy('tabungan.created_at', $sortDir);

        return $builder->paginate($perPage);
    }

    public function getActive(int $userId, int $limit = 5): array
    {
        return $this->where('user_id', $userId)
            ->where('status', 'proses')
            ->orderBy('deadline', 'ASC')
            ->limit($limit)->findAll();
    }
}
