<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PemasukanModel;
use App\Models\PengeluaranModel;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Show profile page
     */
    public function index()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('auth/login')->with('error', 'Session tidak valid.');
        }

        // Sync session data to ensure navbar updates instantly
        session()->set([
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'user_avatar' => $user['avatar'] ?? null,
        ]);

        // Hitung total transaksi untuk ringkasan akun
        $pemasukanModel = new PemasukanModel();
        $pengeluaranModel = new PengeluaranModel();

        $totalPemasukan = $pemasukanModel->where('user_id', $userId)->countAllResults(false);
        $totalPengeluaran = $pengeluaranModel->where('user_id', $userId)->countAllResults(false);

        $data = [
            'title' => 'Profile',
            'active_menu' => 'profile',
            'user' => $user,
            'total_pemasukan' => $totalPemasukan,
            'total_pengeluaran' => $totalPengeluaran,
            'total_transaksi' => $totalPemasukan + $totalPengeluaran,
        ];

        return view('profile/index', $data);
    }

    /**
     * Update profile data
     */
    public function update()
    {
        $userId = session()->get('user_id');

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
        ];

        $messages = [
            'name' => [
                'required' => 'Nama wajib diisi.',
                'min_length' => 'Nama minimal 3 karakter.',
                'max_length' => 'Nama maksimal 100 karakter.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->userModel->update($userId, [
            'name' => $this->request->getPost('name'),
        ]);

        // Update session data
        session()->set([
            'user_name' => $this->request->getPost('name'),
        ]);

        return redirect()->to('profile')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update password
     */
    public function updatePassword()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (!empty($user['google_id'])) {
            return redirect()->back()->with('error', 'Akun yang tertaut dengan Google tidak dapat mengubah password.');
        }

        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        $messages = [
            'current_password' => [
                'required' => 'Password lama wajib diisi.',
            ],
            'new_password' => [
                'required' => 'Password baru wajib diisi.',
                'min_length' => 'Password baru minimal 8 karakter.',
            ],
            'confirm_password' => [
                'required' => 'Konfirmasi password wajib diisi.',
                'matches' => 'Konfirmasi password tidak cocok.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Verifikasi password lama
        if (empty($user['password']) || !password_verify($this->request->getPost('current_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Password lama tidak sesuai.');
        }

        $this->userModel->update($userId, [
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT),
        ]);

        return redirect()->to('profile')->with('success', 'Password berhasil diubah.');
    }

    /**
     * Update avatar
     */
    public function updateAvatar()
    {
        $userId = session()->get('user_id');

        $rules = [
            'avatar' => 'uploaded[avatar]|max_size[avatar,2048]|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png]',
        ];

        $messages = [
            'avatar' => [
                'uploaded' => 'Pilih file foto terlebih dahulu.',
                'max_size' => 'Ukuran foto maksimal 2MB.',
                'is_image' => 'File harus berupa gambar.',
                'mime_in' => 'Format file harus JPG, JPEG, atau PNG.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->with('errors', $this->validator->getErrors());
        }

        $avatar = $this->request->getFile('avatar');

        if ($avatar->isValid() && !$avatar->hasMoved()) {
            // Hapus avatar lama jika ada
            $user = $this->userModel->find($userId);
            if (!empty($user['avatar']) && file_exists(FCPATH . 'assets/uploads/avatars/' . $user['avatar'])) {
                unlink(FCPATH . 'assets/uploads/avatars/' . $user['avatar']);
            }

            // Upload avatar baru
            $newName = $avatar->getRandomName();
            $avatar->move(FCPATH . 'assets/uploads/avatars/', $newName);

            $this->userModel->update($userId, [
                'avatar' => $newName,
            ]);

            // Update session data
            session()->set('user_avatar', $newName);

            return redirect()->to('profile')->with('success', 'Foto profil berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah foto.');
    }
}
