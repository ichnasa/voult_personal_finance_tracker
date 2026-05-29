<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\KategoriModel;
use League\OAuth2\Client\Provider\Google;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Get Google OAuth provider instance
     */
    protected function getGoogleProvider(): Google
    {
        return new Google([
            'clientId' => env('GOOGLE_CLIENT_ID', ''),
            'clientSecret' => env('GOOGLE_CLIENT_SECRET', ''),
            'redirectUri' => env('GOOGLE_REDIRECT_URI', base_url('auth/google/callback')),
        ]);
    }

    /**
     * Show login page
     */
    public function login()
    {
        // Redirect if already logged in
        if (session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Login',
        ];

        return view('auth/login', $data);
    }

    /**
     * Process login
     */
    public function processLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('email', $email)->first();

        if (!$user || empty($user['password']) || !password_verify($password, $user['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email atau password salah.');
        }

        // Set session
        session()->set([
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'user_avatar' => $user['avatar'] ?? null,
            'logged_in' => true,
        ]);

        return redirect()->to('/')->with('success', 'Selamat datang, ' . $user['name'] . '!');
    }

    /**
     * Show register page
     */
    public function register()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Register',
        ];

        return view('auth/register', $data);
    }

    /**
     * Process registration
     */
    public function processRegister()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        $messages = [
            'name' => [
                'required' => 'Nama wajib diisi.',
                'min_length' => 'Nama minimal 3 karakter.',
            ],
            'email' => [
                'required' => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique' => 'Email sudah terdaftar.',
            ],
            'password' => [
                'required' => 'Password wajib diisi.',
                'min_length' => 'Password minimal 8 karakter.',
            ],
            'password_confirm' => [
                'required' => 'Konfirmasi password wajib diisi.',
                'matches' => 'Konfirmasi password tidak cocok.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->userModel->insert([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ]);

        // Create default kategori for the new user
        $newUserId = $this->userModel->getInsertID();
        $kategoriModel = new KategoriModel();
        $kategoriModel->createDefaultsForUser($newUserId);

        return redirect()->to('auth/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // ─── Google OAuth ─────────────────────────────────────────────

    /**
     * Redirect to Google OAuth consent screen
     */
    public function googleLogin()
    {
        $provider = $this->getGoogleProvider();

        $authUrl = $provider->getAuthorizationUrl([
            'scope' => ['openid', 'email', 'profile'],
        ]);

        // Store state in session for CSRF protection
        session()->set('oauth2_state', $provider->getState());

        return redirect()->to($authUrl);
    }

    /**
     * Handle Google OAuth callback
     */
    public function googleCallback()
    {
        $provider = $this->getGoogleProvider();

        // Check for errors from Google
        $error = $this->request->getGet('error');
        if ($error) {
            return redirect()->to('auth/login')->with('error', 'Login Google dibatalkan.');
        }

        $code = $this->request->getGet('code');
        $state = $this->request->getGet('state');

        // Verify state for CSRF protection
        $savedState = session()->get('oauth2_state');
        if (empty($state) || $state !== $savedState) {
            session()->remove('oauth2_state');
            return redirect()->to('auth/login')->with('error', 'State tidak valid. Silakan coba lagi.');
        }

        session()->remove('oauth2_state');

        try {
            // Exchange authorization code for access token
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $code,
            ]);

            // Get user info from Google
            $googleUser = $provider->getResourceOwner($token);
            $googleData = $googleUser->toArray();

            $googleId = $googleData['sub'] ?? $googleUser->getId();
            $googleEmail = $googleData['email'] ?? '';
            $googleName = $googleData['name'] ?? 'User';
            $googleAvatar = $googleData['picture'] ?? null;

            if (empty($googleEmail)) {
                return redirect()->to('auth/login')->with('error', 'Tidak bisa mendapatkan email dari Google.');
            }

            // Find existing user by google_id or email
            $user = $this->userModel->where('google_id', $googleId)->first();

            if (!$user) {
                // Try finding by email
                $user = $this->userModel->where('email', $googleEmail)->first();

                if ($user) {
                    // Link Google account to existing user
                    $this->userModel->update($user['id'], [
                        'google_id' => $googleId,
                    ]);
                    $user['google_id'] = $googleId;
                } else {
                    // Create new user (no password for OAuth-only)
                    $this->userModel->skipValidation(true)->insert([
                        'name' => $googleName,
                        'email' => $googleEmail,
                        'google_id' => $googleId,
                        'password' => null,
                    ]);

                    $newUserId = $this->userModel->getInsertID();

                    // Create default kategori for new user
                    $kategoriModel = new KategoriModel();
                    $kategoriModel->createDefaultsForUser($newUserId);

                    $user = $this->userModel->find($newUserId);
                }
            }

            // Download and save Google avatar if user doesn't have one yet
            if (empty($user['avatar']) && !empty($googleAvatar)) {
                $avatarFilename = $this->downloadGoogleAvatar($googleAvatar, $user['id']);
                if ($avatarFilename) {
                    $this->userModel->update($user['id'], ['avatar' => $avatarFilename]);
                    $user['avatar'] = $avatarFilename;
                }
            }

            // Set session (same as normal login)
            session()->set([
                'user_id' => $user['id'],
                'user_name' => $user['name'],
                'user_email' => $user['email'],
                'user_avatar' => $user['avatar'] ?? null,
                'logged_in' => true,
            ]);

            return redirect()->to('/')->with('success', 'Selamat datang, ' . $user['name'] . '!');

        } catch (\Exception $e) {
            log_message('error', 'Google OAuth Error: ' . $e->getMessage());
            return redirect()->to('auth/login')->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }
    }

    /**
     * Download Google avatar and save locally
     */
    protected function downloadGoogleAvatar(string $url, int $userId): ?string
    {
        try {
            $avatarDir = FCPATH . 'assets/uploads/avatars/';
            if (!is_dir($avatarDir)) {
                mkdir($avatarDir, 0755, true);
            }

            $imageData = file_get_contents($url);
            if ($imageData === false) {
                return null;
            }

            $filename = 'google_' . $userId . '_' . time() . '.jpg';
            file_put_contents($avatarDir . $filename, $imageData);

            return $filename;
        } catch (\Exception $e) {
            log_message('error', 'Failed to download Google avatar: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        session()->destroy();

        return redirect()->to('auth/login')->with('success', 'Anda telah logout.');
    }
}
