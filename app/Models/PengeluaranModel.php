<?php

namespace App\Models;

use CodeIgniter\Model;

class PengeluaranModel extends Model
{
    protected $table         = 'pengeluaran';
    protected $primaryKey    = 'id';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'user_id',
        'kategori_id',
        'tanggal',
        'nominal',
        'metode_pembayaran',
        'catatan',
        'nota',
    ];

    /**
     * Get pengeluaran by user with kategori join and pagination
     */
    public function getByUser(int $userId, int $perPage = 10, array $filters = [])
    {
        $builder = $this->select('pengeluaran.*, kategori.name as kategori_name')
            ->join('kategori', 'kategori.id = pengeluaran.kategori_id', 'left')
            ->where('pengeluaran.user_id', $userId);

        if (! empty($filters['date_from'])) {
            $builder->where('pengeluaran.tanggal >=', $filters['date_from']);
        }
        if (! empty($filters['date_to'])) {
            $builder->where('pengeluaran.tanggal <=', $filters['date_to']);
        }
        if (! empty($filters['kategori_id'])) {
            $builder->where('pengeluaran.kategori_id', $filters['kategori_id']);
        }
        if (! empty($filters['metode'])) {
            $builder->where('pengeluaran.metode_pembayaran', $filters['metode']);
        }
        if (! empty($filters['nominal_min'])) {
            $builder->where('pengeluaran.nominal >=', (float) $filters['nominal_min']);
        }
        if (! empty($filters['nominal_max'])) {
            $builder->where('pengeluaran.nominal <=', (float) $filters['nominal_max']);
        }
        if (! empty($filters['search'])) {
            $builder->groupStart()
                ->like('pengeluaran.catatan', $filters['search'])
                ->orLike('kategori.name', $filters['search'])
                ->orLike('pengeluaran.metode_pembayaran', $filters['search'])
                ->groupEnd();
        }

        $sortDir = ($filters['sort_dir'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';
        return $builder->orderBy('pengeluaran.tanggal', $sortDir)->paginate($perPage);
    }

    /**
     * Total pengeluaran user (bulan ini)
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
     * Total pengeluaran user (all time)
     */
    public function getTotalAll(int $userId): float
    {
        $result = $this->selectSum('nominal')
            ->where('user_id', $userId)
            ->first();

        return (float) ($result['nominal'] ?? 0);
    }

    /**
     * Monthly totals for chart
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

    /**
     * Total per kategori (bulan ini) for pie chart
     */
    public function getTotalPerKategori(int $userId): array
    {
        return $this->select('kategori.name as kategori_name, SUM(pengeluaran.nominal) as total')
            ->join('kategori', 'kategori.id = pengeluaran.kategori_id', 'left')
            ->where('pengeluaran.user_id', $userId)
            ->where('MONTH(pengeluaran.tanggal)', date('m'))
            ->where('YEAR(pengeluaran.tanggal)', date('Y'))
            ->groupBy('pengeluaran.kategori_id')
            ->findAll();
    }

    /**
     * Total pengeluaran per kategori for budget monitoring
     */
    public function getTotalByKategori(int $userId, int $kategoriId, string $bulan, int $tahun): float
    {
        $result = $this->selectSum('nominal')
            ->where('user_id', $userId)
            ->where('kategori_id', $kategoriId)
            ->where('MONTH(tanggal)', $bulan)
            ->where('YEAR(tanggal)', $tahun)
            ->first();

        return (float) ($result['nominal'] ?? 0);
    }
}
