<?php

namespace App\Controllers;

use App\Models\KategoriModel;
use App\Models\PengeluaranModel;
use App\Models\BudgetingModel;
use App\Models\UserModel;

class Pengaturan extends BaseController
{
    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
    }

    /**
     * Settings page — Kategori management
     */
    public function index()
    {
        $userId = session()->get('user_id');

        $userModel = new UserModel();
        $data = [
            'title'       => 'Pengaturan',
            'active_menu' => 'pengaturan',
            'kategoriList' => $this->kategoriModel->getByUser($userId),
            'user'         => $userModel->find($userId),
        ];

        return view('pengaturan/index', $data);
    }

    /**
     * Store new kategori
     */
    public function storeKategori()
    {
        $rules = [
            'name' => 'required|max_length[100]',
        ];

        $messages = [
            'name' => [
                'required'   => 'Nama kategori wajib diisi.',
                'max_length' => 'Nama kategori maksimal 100 karakter.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('user_id');
        $name   = $this->request->getPost('name');

        // Check duplicate name for same user
        $exists = $this->kategoriModel
            ->where('user_id', $userId)
            ->where('name', $name)
            ->first();

        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Kategori "' . $name . '" sudah ada.');
        }

        $this->kategoriModel->insert([
            'user_id' => $userId,
            'name'    => $name,
        ]);

        return redirect()->to('pengaturan')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Update existing kategori
     */
    public function updateKategori($id)
    {
        $userId = session()->get('user_id');
        $item   = $this->kategoriModel->where('id', $id)->where('user_id', $userId)->first();

        if (! $item) {
            return redirect()->to('pengaturan')->with('error', 'Kategori tidak ditemukan.');
        }

        $rules = [
            'name' => 'required|max_length[100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = $this->request->getPost('name');

        // Check duplicate name for same user (exclude current)
        $exists = $this->kategoriModel
            ->where('user_id', $userId)
            ->where('name', $name)
            ->where('id !=', $id)
            ->first();

        if ($exists) {
            return redirect()->back()->withInput()->with('error', 'Kategori "' . $name . '" sudah ada.');
        }

        $this->kategoriModel->update($id, ['name' => $name]);

        return redirect()->to('pengaturan')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Delete kategori
     */
    public function deleteKategori($id)
    {
        $userId = session()->get('user_id');
        $item   = $this->kategoriModel->where('id', $id)->where('user_id', $userId)->first();

        if (! $item) {
            return redirect()->to('pengaturan')->with('error', 'Kategori tidak ditemukan.');
        }

        // Check if kategori is in use
        $pengeluaranModel = new PengeluaranModel();
        $budgetingModel   = new BudgetingModel();

        $usedInPengeluaran = $pengeluaranModel->where('kategori_id', $id)->countAllResults(false);
        $usedInBudgeting   = $budgetingModel->where('kategori_id', $id)->countAllResults(false);

        if ($usedInPengeluaran > 0 || $usedInBudgeting > 0) {
            $msg = 'Kategori "' . $item['name'] . '" tidak bisa dihapus karena masih digunakan di ';
            $parts = [];
            if ($usedInPengeluaran > 0) $parts[] = $usedInPengeluaran . ' pengeluaran';
            if ($usedInBudgeting > 0)   $parts[] = $usedInBudgeting . ' budgeting';
            $msg .= implode(' dan ', $parts) . '.';

            return redirect()->to('pengaturan')->with('error', $msg);
        }

        $this->kategoriModel->delete($id);

        return redirect()->to('pengaturan')->with('success', 'Kategori berhasil dihapus.');
    }

    /**
     * Update user default settings
     */
    public function updateDefaults()
    {
        $userId = session()->get('user_id');
        $userModel = new UserModel();
        
        $kategoriId = $this->request->getPost('default_kategori_id');
        $metode = $this->request->getPost('default_metode_pembayaran');
        
        $userModel->update($userId, [
            'default_kategori_id' => $kategoriId ?: null,
            'default_metode_pembayaran' => $metode ?: null,
        ]);
        
        return redirect()->to('pengaturan')->with('success', 'Pengaturan default berhasil disimpan.');
    }
}
