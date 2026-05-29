<?php

namespace App\Controllers;

use App\Models\BudgetingModel;
use App\Models\KategoriModel;
use App\Models\PengeluaranModel;

class Budgeting extends BaseController
{
    protected $budgetingModel;
    protected $kategoriModel;
    protected $pengeluaranModel;

    public function __construct()
    {
        $this->budgetingModel = new BudgetingModel();
        $this->kategoriModel = new KategoriModel();
        $this->pengeluaranModel = new PengeluaranModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $bulan = $this->request->getGet('bulan') ?: date('m');
        $tahun = $this->request->getGet('tahun') ?: (int) date('Y');

        $budgets = $this->budgetingModel->getByUser($userId, $bulan, $tahun);

        // Calculate usage for each budget
        foreach ($budgets as &$b) {
            $spent = $this->pengeluaranModel->getTotalByKategori($userId, (int) $b['kategori_id'], $bulan, $tahun);
            $b['spent'] = $spent;
            $b['percent'] = $b['nominal_budget'] > 0 ? round(($spent / $b['nominal_budget']) * 100) : 0;
            $b['remaining'] = $b['nominal_budget'] - $spent;
        }

        $data = [
            'title' => 'Budgeting',
            'active_menu' => 'budgeting',
            'budgets' => $budgets,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ];

        return view('budgeting/index', $data);
    }

    public function create()
    {
        $userId = session()->get('user_id');
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);

        $data = [
            'title' => 'Tambah Budget',
            'active_menu' => 'budgeting',
            'kategoriList' => $this->kategoriModel->getDropdown($userId),
            'userDefaults' => [
                'kategori_id' => $user['default_kategori_id'] ?? '',
                'metode_pembayaran' => $user['default_metode_pembayaran'] ?? '',
            ],
        ];
        return view('budgeting/create', $data);
    }

    public function store()
    {
        $rules = [
            'kategori_id' => 'required|numeric',
            'bulan' => 'required',
            'tahun' => 'required|numeric',
            'nominal_budget' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->budgetingModel->insert([
            'user_id' => session()->get('user_id'),
            'kategori_id' => $this->request->getPost('kategori_id'),
            'bulan' => $this->request->getPost('bulan'),
            'tahun' => $this->request->getPost('tahun'),
            'nominal_budget' => $this->request->getPost('nominal_budget'),
        ]);

        return redirect()->to('budgeting')->with('success', 'Budget berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $userId = session()->get('user_id');
        $item = $this->budgetingModel->where('id', $id)->where('user_id', $userId)->first();
        if (!$item)
            return redirect()->to('budgeting')->with('error', 'Data tidak ditemukan.');

        $data = [
            'title' => 'Edit Budget',
            'active_menu' => 'budgeting',
            'item' => $item,
            'kategoriList' => $this->kategoriModel->getDropdown(session()->get('user_id')),
        ];
        return view('budgeting/edit', $data);
    }

    public function update($id)
    {
        $userId = session()->get('user_id');
        $item = $this->budgetingModel->where('id', $id)->where('user_id', $userId)->first();
        if (!$item)
            return redirect()->to('budgeting')->with('error', 'Data tidak ditemukan.');

        $rules = [
            'kategori_id' => 'required|numeric',
            'nominal_budget' => 'required|numeric|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->budgetingModel->update($id, [
            'kategori_id' => $this->request->getPost('kategori_id'),
            'bulan' => $this->request->getPost('bulan'),
            'tahun' => $this->request->getPost('tahun'),
            'nominal_budget' => $this->request->getPost('nominal_budget'),
        ]);

        return redirect()->to('budgeting')->with('success', 'Budget berhasil diperbarui.');
    }

    public function delete($id)
    {
        $userId = session()->get('user_id');
        $item = $this->budgetingModel->where('id', $id)->where('user_id', $userId)->first();
        if (!$item)
            return redirect()->to('budgeting')->with('error', 'Data tidak ditemukan.');

        $this->budgetingModel->delete($id);
        return redirect()->to('budgeting')->with('success', 'Budget berhasil dihapus.');
    }
}
