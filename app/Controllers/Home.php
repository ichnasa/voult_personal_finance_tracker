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
        $tabunganModel    = new TabunganModel();
        $wishlistModel    = new WishlistModel();

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

        // ── Financial Health Score ──
        $spendingRatio = $totalPemasukanBulan > 0
            ? min(round(($totalPengeluaranBulan / $totalPemasukanBulan) * 100), 100)
            : 0;
        $savingRatio = $totalPemasukanBulan > 0
            ? max(round((($totalPemasukanBulan - $totalPengeluaranBulan) / $totalPemasukanBulan) * 100), 0)
            : 0;
        $budgetDiscipline = $totalBudget > 0
            ? max(min(round((1 - ($totalPengeluaranBulan / $totalBudget)) * 100), 100), 0)
            : 100;

        $healthScore = round(
            ((100 - $spendingRatio) * 0.35) +
            ($savingRatio * 0.35) +
            ($budgetDiscipline * 0.30)
        );
        $healthScore = max(0, min(100, $healthScore));

        if ($healthScore >= 80) {
            $healthLabel = 'Excellent';
            $healthColor = '#2fb344';
        } elseif ($healthScore >= 60) {
            $healthLabel = 'Good';
            $healthColor = '#4299e1';
        } elseif ($healthScore >= 40) {
            $healthLabel = 'Fair';
            $healthColor = '#f59f00';
        } else {
            $healthLabel = 'Poor';
            $healthColor = '#d63939';
        }

        // ── Saving Goals (tabungan aktif) ──
        $savingGoals = $tabunganModel->getActive($userId, 3);

        // ── Wishlist Priority ──
        $wishlistPriority = $wishlistModel->getHighPriority($userId, 3);

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
            // Financial health
            'healthScore'           => $healthScore,
            'healthLabel'           => $healthLabel,
            'healthColor'           => $healthColor,
            'spendingRatio'         => $spendingRatio,
            'savingRatio'           => $savingRatio,
            'budgetDiscipline'      => $budgetDiscipline,
            // Saving goals
            'savingGoals'           => $savingGoals,
            // Wishlist priority
            'wishlistPriority'      => $wishlistPriority,
        ];

        return view('dashboard/index', $data);
    }
}
