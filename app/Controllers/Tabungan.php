<?php

namespace App\Controllers;

use App\Models\TabunganModel;
use App\Models\WishlistModel;

class Tabungan extends BaseController
{
    protected $tabunganModel;
    protected $wishlistModel;

    public function __construct()
    {
        $this->tabunganModel = new TabunganModel();
        $this->wishlistModel = new WishlistModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $items  = $this->tabunganModel->getByUser($userId, 10);

        // Calculate progress for each
        foreach ($items as &$item) {
            $item['progress'] = $item['target_nominal'] > 0
                ? round(($item['nominal_terkumpul'] / $item['target_nominal']) * 100)
                : 0;
        }

        $data = [
            'title'       => 'Tabungan',
            'active_menu' => 'tabungan',
            'items'       => $items,
            'pager'       => $this->tabunganModel->pager,
        ];
        return view('tabungan/index', $data);
    }

    public function create()
    {
        $userId = session()->get('user_id');
        $wishlistItems = $this->wishlistModel
            ->where('user_id', $userId)
            ->where('status !=', 'tercapai')
            ->findAll();

        return view('tabungan/create', [
            'title'         => 'Tambah Tabungan',
            'active_menu'   => 'tabungan',
            'wishlistItems' => $wishlistItems,
        ]);
    }

    public function store()
    {
        $rules = [
            'nama_tabungan'  => 'required|max_length[150]',
            'target_nominal' => 'required|numeric|greater_than[0]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->tabunganModel->insert([
            'user_id'           => session()->get('user_id'),
            'wishlist_id'       => $this->request->getPost('wishlist_id') ?: null,
            'nama_tabungan'     => $this->request->getPost('nama_tabungan'),
            'target_nominal'    => $this->request->getPost('target_nominal'),
            'nominal_terkumpul' => $this->request->getPost('nominal_terkumpul') ?: 0,
            'deadline'          => $this->request->getPost('deadline') ?: null,
            'status'            => 'proses',
        ]);

        return redirect()->to('tabungan')->with('success', 'Target tabungan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $userId = session()->get('user_id');
        $item = $this->tabunganModel->where('id', $id)->where('user_id', $userId)->first();
        if (! $item) return redirect()->to('tabungan')->with('error', 'Data tidak ditemukan.');

        $wishlistItems = $this->wishlistModel
            ->where('user_id', $userId)
            ->where('status !=', 'tercapai')
            ->findAll();

        return view('tabungan/edit', [
            'title'         => 'Edit Tabungan',
            'active_menu'   => 'tabungan',
            'item'          => $item,
            'wishlistItems' => $wishlistItems,
        ]);
    }

    public function update($id)
    {
        $userId = session()->get('user_id');
        $item = $this->tabunganModel->where('id', $id)->where('user_id', $userId)->first();
        if (! $item) return redirect()->to('tabungan')->with('error', 'Data tidak ditemukan.');

        $rules = [
            'nama_tabungan'     => 'required|max_length[150]',
            'target_nominal'    => 'required|numeric|greater_than[0]',
            'nominal_terkumpul' => 'required|numeric',
            'status'            => 'required|in_list[proses,tercapai]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->tabunganModel->update($id, [
            'wishlist_id'       => $this->request->getPost('wishlist_id') ?: null,
            'nama_tabungan'     => $this->request->getPost('nama_tabungan'),
            'target_nominal'    => $this->request->getPost('target_nominal'),
            'nominal_terkumpul' => $this->request->getPost('nominal_terkumpul'),
            'deadline'          => $this->request->getPost('deadline') ?: null,
            'status'            => $this->request->getPost('status'),
        ]);

        return redirect()->to('tabungan')->with('success', 'Tabungan berhasil diperbarui.');
    }

    public function delete($id)
    {
        $userId = session()->get('user_id');
        $item = $this->tabunganModel->where('id', $id)->where('user_id', $userId)->first();
        if (! $item) return redirect()->to('tabungan')->with('error', 'Data tidak ditemukan.');

        $this->tabunganModel->delete($id);
        return redirect()->to('tabungan')->with('success', 'Tabungan berhasil dihapus.');
    }
}
