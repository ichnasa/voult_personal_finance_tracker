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

    public function getByUser(int $userId, int $perPage = 10)
    {
        return $this->select('tabungan.*, wishlist.nama_barang as wishlist_name')
            ->join('wishlist', 'wishlist.id = tabungan.wishlist_id', 'left')
            ->where('tabungan.user_id', $userId)
            ->orderBy('tabungan.created_at', 'DESC')
            ->paginate($perPage);
    }

    public function getActive(int $userId, int $limit = 5): array
    {
        return $this->where('user_id', $userId)
            ->where('status', 'proses')
            ->orderBy('deadline', 'ASC')
            ->limit($limit)->findAll();
    }
}
