<?php

namespace App\Controllers;

use App\Models\PemasukanModel;
use App\Models\PengeluaranModel;
use App\Models\KategoriModel;

class Laporan extends BaseController
{
    protected $pemasukanModel;
    protected $pengeluaranModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->pemasukanModel   = new PemasukanModel();
        $this->pengeluaranModel = new PengeluaranModel();
        $this->kategoriModel    = new KategoriModel();
    }

    public function index()
    {
        $userId   = session()->get('user_id');
        $dateFrom = $this->request->getGet('date_from') ?: date('Y-m-01');
        $dateTo   = $this->request->getGet('date_to') ?: date('Y-m-t');

        // Pemasukan in range
        $pemasukan = $this->pemasukanModel
            ->where('user_id', $userId)
            ->where('tanggal >=', $dateFrom)
            ->where('tanggal <=', $dateTo)
            ->orderBy('tanggal', 'ASC')->findAll();

        $totalPemasukan = array_sum(array_column($pemasukan, 'nominal'));

        // Pengeluaran in range
        $pengeluaran = $this->pengeluaranModel
            ->select('pengeluaran.*, kategori.name as kategori_name')
            ->join('kategori', 'kategori.id = pengeluaran.kategori_id', 'left')
            ->where('pengeluaran.user_id', $userId)
            ->where('pengeluaran.tanggal >=', $dateFrom)
            ->where('pengeluaran.tanggal <=', $dateTo)
            ->orderBy('pengeluaran.tanggal', 'ASC')->findAll();

        $totalPengeluaran = array_sum(array_column($pengeluaran, 'nominal'));

        // Pengeluaran per kategori
        $perKategori = $this->pengeluaranModel
            ->select('kategori.name as kategori_name, SUM(pengeluaran.nominal) as total')
            ->join('kategori', 'kategori.id = pengeluaran.kategori_id', 'left')
            ->where('pengeluaran.user_id', $userId)
            ->where('pengeluaran.tanggal >=', $dateFrom)
            ->where('pengeluaran.tanggal <=', $dateTo)
            ->groupBy('pengeluaran.kategori_id')
            ->findAll();

        $data = [
            'title'            => 'Laporan',
            'active_menu'      => 'laporan',
            'dateFrom'         => $dateFrom,
            'dateTo'           => $dateTo,
            'pemasukan'        => $pemasukan,
            'pengeluaran'      => $pengeluaran,
            'totalPemasukan'   => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'selisih'          => $totalPemasukan - $totalPengeluaran,
            'perKategori'      => $perKategori,
        ];

        return view('laporan/index', $data);
    }

    public function export()
    {
        $userId   = session()->get('user_id');
        $dateFrom = $this->request->getGet('date_from') ?: date('Y-m-01');
        $dateTo   = $this->request->getGet('date_to') ?: date('Y-m-t');

        $pemasukan = $this->pemasukanModel
            ->where('user_id', $userId)
            ->where('tanggal >=', $dateFrom)
            ->where('tanggal <=', $dateTo)
            ->orderBy('tanggal', 'ASC')->findAll();

        $pengeluaran = $this->pengeluaranModel
            ->select('pengeluaran.*, kategori.name as kategori_name')
            ->join('kategori', 'kategori.id = pengeluaran.kategori_id', 'left')
            ->where('pengeluaran.user_id', $userId)
            ->where('pengeluaran.tanggal >=', $dateFrom)
            ->where('pengeluaran.tanggal <=', $dateTo)
            ->orderBy('pengeluaran.tanggal', 'ASC')->findAll();

        $totalPemasukan   = array_sum(array_column($pemasukan, 'nominal'));
        $totalPengeluaran = array_sum(array_column($pengeluaran, 'nominal'));

        $data = [
            'dateFrom'         => $dateFrom,
            'dateTo'           => $dateTo,
            'pemasukan'        => $pemasukan,
            'pengeluaran'      => $pengeluaran,
            'totalPemasukan'   => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'selisih'          => $totalPemasukan - $totalPengeluaran,
            'userName'         => session()->get('user_name'),
        ];

        return view('laporan/print', $data);
    }
}
