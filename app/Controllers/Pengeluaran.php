<?php

namespace App\Controllers;

use App\Models\PengeluaranModel;
use App\Models\KategoriModel;

class Pengeluaran extends BaseController
{
    protected $pengeluaranModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->pengeluaranModel = new PengeluaranModel();
        $this->kategoriModel   = new KategoriModel();
    }

    public function index()
    {
        $userId  = session()->get('user_id');
        $filters = [
            'date_from'   => $this->request->getGet('date_from'),
            'date_to'     => $this->request->getGet('date_to'),
            'kategori_id' => $this->request->getGet('kategori_id'),
            'metode'      => $this->request->getGet('metode'),
            'nominal_min' => $this->request->getGet('nominal_min'),
            'nominal_max' => $this->request->getGet('nominal_max'),
            'search'      => $this->request->getGet('search'),
            'sort_dir'    => $this->request->getGet('sort_dir'),
        ];

        $data = [
            'title'       => 'Pengeluaran',
            'active_menu' => 'pengeluaran',
            'items'       => $this->pengeluaranModel->getByUser($userId, 10, $filters),
            'pager'       => $this->pengeluaranModel->pager,
            'filters'     => $filters,
            'kategoriList' => $this->kategoriModel->getDropdown($userId),
        ];

        return view('pengeluaran/index', $data);
    }

    public function create()
    {
        $data = [
            'title'        => 'Tambah Pengeluaran',
            'active_menu'  => 'pengeluaran',
            'kategoriList' => $this->kategoriModel->getDropdown(session()->get('user_id')),
        ];

        return view('pengeluaran/create', $data);
    }

    public function store()
    {
        $rules = [
            'tanggal'     => 'required|valid_date',
            'nominal'     => 'required|numeric|greater_than[0]',
            'kategori_id' => 'required|numeric',
        ];

        $messages = [
            'tanggal'     => ['required' => 'Tanggal wajib diisi.'],
            'nominal'     => ['required' => 'Nominal wajib diisi.', 'numeric' => 'Nominal harus angka.', 'greater_than' => 'Nominal harus lebih dari 0.'],
            'kategori_id' => ['required' => 'Kategori wajib dipilih.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $notaPath = null;
        $nota = $this->request->getFile('nota');
        if ($nota && $nota->isValid() && ! $nota->hasMoved()) {
            $notaName = $nota->getRandomName();
            $nota->move('assets/uploads', $notaName);
            $notaPath = $notaName;
        }

        $this->pengeluaranModel->insert([
            'user_id'           => session()->get('user_id'),
            'kategori_id'       => $this->request->getPost('kategori_id'),
            'tanggal'           => $this->request->getPost('tanggal'),
            'nominal'           => $this->request->getPost('nominal'),
            'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
            'catatan'           => $this->request->getPost('catatan'),
            'nota'              => $notaPath,
        ]);

        return redirect()->to('pengeluaran')->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $userId = session()->get('user_id');
        $item   = $this->pengeluaranModel->where('id', $id)->where('user_id', $userId)->first();

        if (! $item) {
            return redirect()->to('pengeluaran')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title'        => 'Edit Pengeluaran',
            'active_menu'  => 'pengeluaran',
            'item'         => $item,
            'kategoriList' => $this->kategoriModel->getDropdown($userId),
        ];

        return view('pengeluaran/edit', $data);
    }

    public function update($id)
    {
        $userId = session()->get('user_id');
        $item   = $this->pengeluaranModel->where('id', $id)->where('user_id', $userId)->first();

        if (! $item) {
            return redirect()->to('pengeluaran')->with('error', 'Data tidak ditemukan.');
        }

        $rules = [
            'tanggal'     => 'required|valid_date',
            'nominal'     => 'required|numeric|greater_than[0]',
            'kategori_id' => 'required|numeric',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $updateData = [
            'kategori_id'       => $this->request->getPost('kategori_id'),
            'tanggal'           => $this->request->getPost('tanggal'),
            'nominal'           => $this->request->getPost('nominal'),
            'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
            'catatan'           => $this->request->getPost('catatan'),
        ];

        $nota = $this->request->getFile('nota');
        if ($nota && $nota->isValid() && ! $nota->hasMoved()) {
            // Delete old nota
            if (! empty($item['nota']) && file_exists('assets/uploads/' . $item['nota'])) {
                unlink('assets/uploads/' . $item['nota']);
            }
            $notaName = $nota->getRandomName();
            $nota->move('assets/uploads', $notaName);
            $updateData['nota'] = $notaName;
        }

        $this->pengeluaranModel->update($id, $updateData);

        return redirect()->to('pengeluaran')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function delete($id)
    {
        $userId = session()->get('user_id');
        $item   = $this->pengeluaranModel->where('id', $id)->where('user_id', $userId)->first();

        if (! $item) {
            return redirect()->to('pengeluaran')->with('error', 'Data tidak ditemukan.');
        }

        // Delete nota file
        if (! empty($item['nota']) && file_exists('assets/uploads/' . $item['nota'])) {
            unlink('assets/uploads/' . $item['nota']);
        }

        $this->pengeluaranModel->delete($id);

        return redirect()->to('pengeluaran')->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
