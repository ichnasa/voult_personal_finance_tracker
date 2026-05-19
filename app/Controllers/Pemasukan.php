<?php

namespace App\Controllers;

use App\Models\PemasukanModel;

class Pemasukan extends BaseController
{
    protected $pemasukanModel;

    public function __construct()
    {
        $this->pemasukanModel = new PemasukanModel();
    }

    /**
     * List pemasukan
     */
    public function index()
    {
        $userId  = session()->get('user_id');
        $filters = [
            'date_from' => $this->request->getGet('date_from'),
            'date_to'   => $this->request->getGet('date_to'),
            'search'    => $this->request->getGet('search'),
        ];

        $data = [
            'title'       => 'Pemasukan',
            'active_menu' => 'pemasukan',
            'items'       => $this->pemasukanModel->getByUser($userId, 10, $filters),
            'pager'       => $this->pemasukanModel->pager,
            'filters'     => $filters,
        ];

        return view('pemasukan/index', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $data = [
            'title'       => 'Tambah Pemasukan',
            'active_menu' => 'pemasukan',
        ];

        return view('pemasukan/create', $data);
    }

    /**
     * Store new pemasukan
     */
    public function store()
    {
        $rules = [
            'tanggal' => 'required|valid_date',
            'nominal' => 'required|numeric|greater_than[0]',
            'sumber'  => 'required|max_length[100]',
        ];

        $messages = [
            'tanggal' => ['required' => 'Tanggal wajib diisi.', 'valid_date' => 'Format tanggal tidak valid.'],
            'nominal' => ['required' => 'Nominal wajib diisi.', 'numeric' => 'Nominal harus berupa angka.', 'greater_than' => 'Nominal harus lebih dari 0.'],
            'sumber'  => ['required' => 'Sumber pemasukan wajib diisi.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->pemasukanModel->insert([
            'user_id' => session()->get('user_id'),
            'tanggal' => $this->request->getPost('tanggal'),
            'nominal' => $this->request->getPost('nominal'),
            'sumber'  => $this->request->getPost('sumber'),
            'catatan' => $this->request->getPost('catatan'),
        ]);

        return redirect()->to('pemasukan')->with('success', 'Pemasukan berhasil ditambahkan.');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $userId = session()->get('user_id');
        $item   = $this->pemasukanModel->where('id', $id)->where('user_id', $userId)->first();

        if (! $item) {
            return redirect()->to('pemasukan')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title'       => 'Edit Pemasukan',
            'active_menu' => 'pemasukan',
            'item'        => $item,
        ];

        return view('pemasukan/edit', $data);
    }

    /**
     * Update pemasukan
     */
    public function update($id)
    {
        $userId = session()->get('user_id');
        $item   = $this->pemasukanModel->where('id', $id)->where('user_id', $userId)->first();

        if (! $item) {
            return redirect()->to('pemasukan')->with('error', 'Data tidak ditemukan.');
        }

        $rules = [
            'tanggal' => 'required|valid_date',
            'nominal' => 'required|numeric|greater_than[0]',
            'sumber'  => 'required|max_length[100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->pemasukanModel->update($id, [
            'tanggal' => $this->request->getPost('tanggal'),
            'nominal' => $this->request->getPost('nominal'),
            'sumber'  => $this->request->getPost('sumber'),
            'catatan' => $this->request->getPost('catatan'),
        ]);

        return redirect()->to('pemasukan')->with('success', 'Pemasukan berhasil diperbarui.');
    }

    /**
     * Delete pemasukan
     */
    public function delete($id)
    {
        $userId = session()->get('user_id');
        $item   = $this->pemasukanModel->where('id', $id)->where('user_id', $userId)->first();

        if (! $item) {
            return redirect()->to('pemasukan')->with('error', 'Data tidak ditemukan.');
        }

        $this->pemasukanModel->delete($id);

        return redirect()->to('pemasukan')->with('success', 'Pemasukan berhasil dihapus.');
    }
}
