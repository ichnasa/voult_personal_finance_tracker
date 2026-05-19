<?php

namespace App\Controllers;

use App\Models\PemasukanModel;
use App\Models\PengeluaranModel;
use App\Models\BudgetingModel;
use App\Models\TabunganModel;
use App\Models\WishlistModel;

class Home extends BaseController
{
    public function index(): string
    {
        $userId = session()->get('user_id');

        $pemasukanModel   = new PemasukanModel();
        $pengeluaranModel = new PengeluaranModel();
        $budgetingModel   = new BudgetingModel();

        // Stats
        $totalPemasukanAll   = $pemasukanModel->getTotalAll($userId);
        $totalPengeluaranAll = $pengeluaranModel->getTotalAll($userId);
        $totalSaldo          = $totalPemasukanAll - $totalPengeluaranAll;

        $totalPemasukanBulan   = $pemasukanModel->getTotalBulanIni($userId);
        $totalPengeluaranBulan = $pengeluaranModel->getTotalBulanIni($userId);
        $totalBudget           = $budgetingModel->getTotalBudget($userId);
        $sisaBudget            = $totalBudget - $totalPengeluaranBulan;

        // Chart data
        $pemasukanBulanan   = $pemasukanModel->getMonthlyTotals($userId);
        $pengeluaranBulanan = $pengeluaranModel->getMonthlyTotals($userId);

        // Recent transactions (last 5 combined)
        $recentPemasukan = $pemasukanModel->where('user_id', $userId)
            ->orderBy('tanggal', 'DESC')->limit(5)->findAll();
        $recentPengeluaran = $pengeluaranModel
            ->select('pengeluaran.*, kategori.name as kategori_name')
            ->join('kategori', 'kategori.id = pengeluaran.kategori_id', 'left')
            ->where('pengeluaran.user_id', $userId)
            ->orderBy('pengeluaran.tanggal', 'DESC')->limit(5)->findAll();

        $data = [
            'title'                 => 'Dashboard',
            'active_menu'           => 'dashboard',
            'totalSaldo'            => $totalSaldo,
            'totalPemasukanBulan'   => $totalPemasukanBulan,
            'totalPengeluaranBulan' => $totalPengeluaranBulan,
            'sisaBudget'            => $sisaBudget,
            'totalBudget'           => $totalBudget,
            'pemasukanBulanan'      => array_values($pemasukanBulanan),
            'pengeluaranBulanan'    => array_values($pengeluaranBulanan),
            'recentPemasukan'       => $recentPemasukan,
            'recentPengeluaran'     => $recentPengeluaran,
        ];

        return view('dashboard/index', $data);
    }
}
