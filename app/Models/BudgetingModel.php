<?php

namespace App\Models;

use CodeIgniter\Model;

class BudgetingModel extends Model
{
    protected $table = 'budgeting';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'user_id',
        'kategori_id',
        'bulan',
        'tahun',
        'nominal_budget',
    ];

    /**
     * Get budgets with kategori name for current month
     */
    public function getByUser(int $userId, string $bulan = '', int $tahun = 0): array
    {
        $bulan = $bulan ?: date('m');
        $tahun = $tahun ?: (int) date('Y');

        return $this->select('budgeting.*, kategori.name as kategori_name')
            ->join('kategori', 'kategori.id = budgeting.kategori_id', 'left')
            ->where('budgeting.user_id', $userId)
            ->where('budgeting.bulan', $bulan)
            ->where('budgeting.tahun', $tahun)
            ->findAll();
    }

    /**
     * Total budget bulan ini
     */
    public function getTotalBudget(int $userId, string $bulan = '', int $tahun = 0): float
    {
        $bulan = $bulan ?: date('m');
        $tahun = $tahun ?: (int) date('Y');

        $result = $this->selectSum('nominal_budget')
            ->where('user_id', $userId)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->first();

        return (float) ($result['nominal_budget'] ?? 0);
    }
}
