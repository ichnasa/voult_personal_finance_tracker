<?php

namespace App\Models;

use CodeIgniter\Model;

class WishlistModel extends Model
{
    protected $table         = 'wishlist';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'user_id', 'nama_barang', 'harga_target',
        'prioritas', 'status', 'catatan',
    ];

    public function getByUser(int $userId, int $perPage = 10)
    {
        return $this->where('user_id', $userId)
            ->orderBy('FIELD(prioritas, "tinggi", "sedang", "rendah")')
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage);
    }

    public function getHighPriority(int $userId, int $limit = 3): array
    {
        return $this->where('user_id', $userId)
            ->where('status !=', 'tercapai')
            ->orderBy('FIELD(prioritas, "tinggi", "sedang", "rendah")')
            ->limit($limit)->findAll();
    }
}
