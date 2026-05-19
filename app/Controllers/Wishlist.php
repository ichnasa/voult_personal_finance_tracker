<?php

namespace App\Controllers;

use App\Models\WishlistModel;

class Wishlist extends BaseController
{
    protected $wishlistModel;

    public function __construct()
    {
        $this->wishlistModel = new WishlistModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $data = [
            'title'       => 'Wishlist',
            'active_menu' => 'wishlist',
            'items'       => $this->wishlistModel->getByUser($userId, 10),
            'pager'       => $this->wishlistModel->pager,
        ];
        return view('wishlist/index', $data);
    }

    public function create()
    {
        return view('wishlist/create', [
            'title' => 'Tambah Wishlist', 'active_menu' => 'wishlist',
        ]);
    }

    public function store()
    {
        $rules = [
            'nama_barang'  => 'required|max_length[150]',
            'harga_target' => 'required|numeric|greater_than[0]',
            'prioritas'    => 'required|in_list[rendah,sedang,tinggi]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->wishlistModel->insert([
            'user_id'      => session()->get('user_id'),
            'nama_barang'  => $this->request->getPost('nama_barang'),
            'harga_target' => $this->request->getPost('harga_target'),
            'prioritas'    => $this->request->getPost('prioritas'),
            'status'       => 'belum_mulai',
            'catatan'      => $this->request->getPost('catatan'),
        ]);

        return redirect()->to('wishlist')->with('success', 'Wishlist berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $userId = session()->get('user_id');
        $item = $this->wishlistModel->where('id', $id)->where('user_id', $userId)->first();
        if (! $item) return redirect()->to('wishlist')->with('error', 'Data tidak ditemukan.');

        return view('wishlist/edit', [
            'title' => 'Edit Wishlist', 'active_menu' => 'wishlist', 'item' => $item,
        ]);
    }

    public function update($id)
    {
        $userId = session()->get('user_id');
        $item = $this->wishlistModel->where('id', $id)->where('user_id', $userId)->first();
        if (! $item) return redirect()->to('wishlist')->with('error', 'Data tidak ditemukan.');

        $rules = [
            'nama_barang'  => 'required|max_length[150]',
            'harga_target' => 'required|numeric|greater_than[0]',
            'prioritas'    => 'required|in_list[rendah,sedang,tinggi]',
            'status'       => 'required|in_list[belum_mulai,menabung,tercapai]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->wishlistModel->update($id, [
            'nama_barang'  => $this->request->getPost('nama_barang'),
            'harga_target' => $this->request->getPost('harga_target'),
            'prioritas'    => $this->request->getPost('prioritas'),
            'status'       => $this->request->getPost('status'),
            'catatan'      => $this->request->getPost('catatan'),
        ]);

        return redirect()->to('wishlist')->with('success', 'Wishlist berhasil diperbarui.');
    }

    public function delete($id)
    {
        $userId = session()->get('user_id');
        $item = $this->wishlistModel->where('id', $id)->where('user_id', $userId)->first();
        if (! $item) return redirect()->to('wishlist')->with('error', 'Data tidak ditemukan.');

        $this->wishlistModel->delete($id);
        return redirect()->to('wishlist')->with('success', 'Wishlist berhasil dihapus.');
    }
}
