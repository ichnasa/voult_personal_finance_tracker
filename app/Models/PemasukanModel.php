<?php

namespace App\Models;

use CodeIgniter\Model;

class PemasukanModel extends Model
{
    protected $table         = 'pemasukan';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'user_id',
        'tanggal',
        'nominal',
        'sumber',
        'deskripsi',
    ];

    /**
     * Get pemasukan by user with pagination
     */
    public function getByUser(int $userId, int $perPage = 10, array $filters = [])
    {
        $builder = $this->where('user_id', $userId);

        if (! empty($filters['date_from'])) {
            $builder->where('tanggal >=', $filters['date_from']);
        }
        if (! empty($filters['date_to'])) {
            $builder->where('tanggal <=', $filters['date_to']);
        }
        if (! empty($filters['nominal_min'])) {
            $builder->where('nominal >=', (float) $filters['nominal_min']);
        }
        if (! empty($filters['nominal_max'])) {
            $builder->where('nominal <=', (float) $filters['nominal_max']);
        }
        if (! empty($filters['search'])) {
            $builder->groupStart()
                ->like('sumber', $filters['search'])
                ->orLike('deskripsi', $filters['search'])
                ->groupEnd();
        }

        $sortDir = ($filters['sort_dir'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';
        return $builder->orderBy('tanggal', $sortDir)->paginate($perPage);
    }

    /**
     * Total pemasukan user (bulan ini)
     */
    public function getTotalBulanIni(int $userId): float
    {
        $result = $this->selectSum('nominal')
            ->where('user_id', $userId)
            ->where('MONTH(tanggal)', date('m'))
            ->where('YEAR(tanggal)', date('Y'))
            ->first();

        return (float) ($result['nominal'] ?? 0);
    }

    /**
     * Total pemasukan user (all time)
     */
    public function getTotalAll(int $userId): float
    {
        $result = $this->selectSum('nominal')
            ->where('user_id', $userId)
            ->first();

        return (float) ($result['nominal'] ?? 0);
    }

    /**
     * Monthly totals for chart (current year)
     */
    public function getMonthlyTotals(int $userId, int $year = 0): array
    {
        $year = $year ?: (int) date('Y');
        $results = $this->select('MONTH(tanggal) as bulan, SUM(nominal) as total')
            ->where('user_id', $userId)
            ->where('YEAR(tanggal)', $year)
            ->groupBy('MONTH(tanggal)')
            ->findAll();

        $monthly = array_fill(1, 12, 0);
        foreach ($results as $row) {
            $monthly[(int) $row['bulan']] = (float) $row['total'];
        }

        return $monthly;
    }
}
