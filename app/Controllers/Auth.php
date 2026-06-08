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

        // Generate OTP using Mersenne Twister Method
        $otpCode = sprintf("%06d", mt_rand(1, 999999));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // Save OTP to user
        $this->userModel->update($user['id'], [
            'otp_code' => $otpCode,
            'otp_expires_at' => $expiresAt
        ]);

        // Send OTP via Email
        if (!$this->sendOtpEmail($user['email'], $otpCode)) {
            return redirect()->back()->with('error', 'Gagal mengirimkan email OTP. Silakan periksa konfigurasi SMTP Anda.');
        }

        // Set temp session
        session()->set('temp_user_id', $user['id']);

        return redirect()->to('auth/otp')->with('success', 'Kode OTP telah dikirimkan. Silakan periksa email Anda.');
    }

    /**
     * Show OTP verification page
     */
    public function otp()
    {
        if (!session()->get('temp_user_id')) {
            return redirect()->to('auth/login');
        }

        $data = [
            'title' => 'Verifikasi OTP',
        ];

        return view('auth/otp', $data);
    }

    /**
     * Process OTP verification
     */
    public function processOtp()
    {
        if (!session()->get('temp_user_id')) {
            return redirect()->to('auth/login');
        }

        $rules = [
            'otp_code' => 'required|exact_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->with('error', 'Kode OTP harus 6 digit.');
        }

        $otpCode = $this->request->getPost('otp_code');
        $userId = session()->get('temp_user_id');

        $user = $this->userModel->find($userId);

        if (!$user || $user['otp_code'] !== $otpCode) {
            return redirect()->back()->with('error', 'Kode OTP tidak valid.');
        }

        if (strtotime($user['otp_expires_at']) < time()) {
            return redirect()->back()->with('error', 'Kode OTP sudah kadaluarsa. Silakan kirim ulang.');
        }

        // Clear OTP
        $this->userModel->update($userId, [
            'otp_code' => null,
            'otp_expires_at' => null
        ]);

        // Remove temp session
        session()->remove('temp_user_id');

        // Set full login session
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
     * Resend OTP
     */
    public function resendOtp()
    {
        if (!session()->get('temp_user_id')) {
            return redirect()->to('auth/login');
        }

        $userId = session()->get('temp_user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('auth/login');
        }

        // Generate new OTP
        $otpCode = sprintf("%06d", mt_rand(1, 999999));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // Save new OTP
        $this->userModel->update($user['id'], [
            'otp_code' => $otpCode,
            'otp_expires_at' => $expiresAt
        ]);

        // Send OTP via Email
        if (!$this->sendOtpEmail($user['email'], $otpCode)) {
            return redirect()->back()->with('error', 'Gagal mengirim ulang email OTP.');
        }

        return redirect()->back()->with('success', 'Kode OTP baru telah dikirim.');
    }

    /**
     * Send OTP via Email
     */
    private function sendOtpEmail(string $email, string $otpCode, string $context = 'login'): bool
    {
        $emailService = \Config\Services::email();

        if ($context === 'reset') {
            $subject = 'Kode OTP Reset Password Anda - PLOOM';
            $title   = 'Reset Password';
            $message = 'Gunakan kode berikut untuk me-reset password Anda:';
        } elseif ($context === 'register') {
            $subject = 'Verifikasi Akun PLOOM Anda';
            $title   = 'Verifikasi Akun';
            $message = 'Gunakan kode berikut untuk menyelesaikan pendaftaran akun Anda:';
        } else {
            $subject = 'Kode OTP Login Anda - PLOOM';
            $title   = 'Kode OTP Anda';
            $message = 'Gunakan kode berikut untuk masuk ke akun Anda:';
        }

        $emailService->setTo($email);
        $emailService->setSubject($subject);
        $emailService->setMessage("
            <h2>{$title}</h2>
            <p>{$message}</p>
            <h1 style='letter-spacing: 5px; color: #0054a6;'>{$otpCode}</h1>
            <p>Kode ini hanya berlaku selama 5 menit. Jangan berikan kode ini kepada siapapun.</p>
        ");

        // Log OTP for development/demo purposes
        log_message('info', "OTP Code for {$email}: {$otpCode}");

        if ($emailService->send()) {
            return true;
        } else {
            log_message('error', 'Email OTP failed: ' . $emailService->printDebugger(['headers']));
            return false;
        }
    }

    /**
     * Show Forgot Password page
     */
    public function forgotPassword()
    {
        return view('auth/forgot_password', ['title' => 'Lupa Password']);
    }

    /**
     * Process Forgot Password
     */
    public function processForgotPassword()
    {
        $rules = [
            'email' => 'required|valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Silakan masukkan email yang valid.');
        }

        $email = $this->request->getPost('email');
        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email tidak terdaftar.');
        }

        // Generate OTP
        $otpCode = sprintf("%06d", mt_rand(1, 999999));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // Save OTP to user
        $this->userModel->update($user['id'], [
            'reset_otp_code' => $otpCode,
            'reset_otp_expires_at' => $expiresAt
        ]);

        // Send OTP via Email (context: reset)
        if (!$this->sendOtpEmail($user['email'], $otpCode, 'reset')) {
            return redirect()->back()->with('error', 'Gagal mengirimkan email OTP.');
        }

        // Set temp reset session
        session()->set('temp_reset_user_id', $user['id']);
        session()->set('reset_otp_verified', false);

        return redirect()->to('auth/reset-otp')->with('success', 'Kode OTP untuk reset password telah dikirimkan ke email Anda.');
    }

    /**
     * Show Reset Password OTP verification page
     */
    public function resetOtp()
    {
        if (!session()->get('temp_reset_user_id')) {
            return redirect()->to('auth/login');
        }

        return view('auth/reset_otp', ['title' => 'Verifikasi OTP Reset Password']);
    }

    /**
     * Process Reset Password OTP
     */
    public function processResetOtp()
    {
        if (!session()->get('temp_reset_user_id')) {
            return redirect()->to('auth/login');
        }

        $rules = [
            'otp_code' => 'required|exact_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Kode OTP harus 6 digit.');
        }

        $otpCode = $this->request->getPost('otp_code');
        $userId = session()->get('temp_reset_user_id');
        $user = $this->userModel->find($userId);

        if (!$user || $user['reset_otp_code'] !== $otpCode) {
            return redirect()->back()->with('error', 'Kode OTP tidak valid.');
        }

        if (strtotime($user['reset_otp_expires_at']) < time()) {
            return redirect()->back()->with('error', 'Kode OTP sudah kadaluarsa. Silakan kirim ulang.');
        }

        // Clear OTP
        $this->userModel->update($userId, [
            'reset_otp_code' => null,
            'reset_otp_expires_at' => null
        ]);

        // Verified!
        session()->set('reset_otp_verified', true);

        return redirect()->to('auth/reset-password')->with('success', 'OTP Terverifikasi! Silakan masukkan password baru Anda.');
    }

    /**
     * Resend Reset OTP
     */
    public function resendResetOtp()
    {
        if (!session()->get('temp_reset_user_id')) {
            return redirect()->to('auth/login');
        }

        $userId = session()->get('temp_reset_user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('auth/login');
        }

        // Generate new OTP
        $otpCode = sprintf("%06d", mt_rand(1, 999999));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // Save new OTP
        $this->userModel->update($user['id'], [
            'reset_otp_code' => $otpCode,
            'reset_otp_expires_at' => $expiresAt
        ]);

        // Send OTP via Email (context: reset)
        if (!$this->sendOtpEmail($user['email'], $otpCode, 'reset')) {
            return redirect()->back()->with('error', 'Gagal mengirim ulang email OTP.');
        }

        return redirect()->back()->with('success', 'Kode OTP baru telah dikirim.');
    }

    /**
     * Show new password form
     */
    public function resetPassword()
    {
        if (!session()->get('temp_reset_user_id') || !session()->get('reset_otp_verified')) {
            return redirect()->to('auth/login');
        }

        return view('auth/reset_password', ['title' => 'Buat Password Baru']);
    }

    /**
     * Process new password
     */
    public function processResetPassword()
    {
        if (!session()->get('temp_reset_user_id') || !session()->get('reset_otp_verified')) {
            return redirect()->to('auth/login');
        }

        $rules = [
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        $messages = [
            'password' => [
                'required' => 'Password baru wajib diisi.',
                'min_length' => 'Password minimal 8 karakter.',
            ],
            'password_confirm' => [
                'required' => 'Konfirmasi password wajib diisi.',
                'matches' => 'Konfirmasi password tidak cocok.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('temp_reset_user_id');
        $newPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        // Update password
        $this->userModel->update($userId, [
            'password' => $newPassword
        ]);

        // Cleanup sessions
        session()->remove('temp_reset_user_id');
        session()->remove('reset_otp_verified');

        return redirect()->to('auth/login')->with('success', 'Password berhasil diubah! Silakan login dengan password baru Anda.');
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
     * Process registration — simpan ke session dulu, kirim OTP, akun dibuat setelah OTP terverifikasi
     */
    public function processRegister()
    {
        $rules = [
            'name'             => 'required|min_length[3]|max_length[100]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        $messages = [
            'name' => [
                'required'   => 'Nama wajib diisi.',
                'min_length' => 'Nama minimal 3 karakter.',
            ],
            'email' => [
                'required'    => 'Email wajib diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email sudah terdaftar.',
            ],
            'password' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password minimal 8 karakter.',
            ],
            'password_confirm' => [
                'required' => 'Konfirmasi password wajib diisi.',
                'matches'  => 'Konfirmasi password tidak cocok.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Generate OTP
        $otpCode   = sprintf("%06d", mt_rand(1, 999999));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // Simpan data pendaftaran SEMENTARA di session (belum insert ke DB)
        session()->set('temp_register_data', [
            'name'           => $this->request->getPost('name'),
            'email'          => $this->request->getPost('email'),
            'password'       => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'otp_code'       => $otpCode,
            'otp_expires_at' => $expiresAt,
        ]);

        // Kirim OTP verifikasi ke email
        if (!$this->sendOtpEmail($this->request->getPost('email'), $otpCode, 'register')) {
            session()->remove('temp_register_data');
            return redirect()->back()->withInput()->with('error', 'Gagal mengirimkan email verifikasi. Silakan periksa konfigurasi SMTP Anda.');
        }

        return redirect()->to('auth/register-otp')->with('success', 'Kode verifikasi telah dikirim ke email Anda. Silakan periksa inbox atau folder spam.');
    }

    /**
     * Tampilkan halaman OTP verifikasi pendaftaran
     */
    public function registerOtp()
    {
        if (!session()->get('temp_register_data')) {
            return redirect()->to('auth/register');
        }

        return view('auth/register_otp', ['title' => 'Verifikasi Email']);
    }

    /**
     * Proses verifikasi OTP pendaftaran — baru insert akun jika OTP valid
     */
    public function processRegisterOtp()
    {
        $registerData = session()->get('temp_register_data');

        if (!$registerData) {
            return redirect()->to('auth/register');
        }

        $rules = [
            'otp_code' => 'required|exact_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Kode OTP harus 6 digit.');
        }

        $otpCode = $this->request->getPost('otp_code');

        // Validasi OTP
        if ($registerData['otp_code'] !== $otpCode) {
            return redirect()->back()->with('error', 'Kode OTP tidak valid.');
        }

        if (strtotime($registerData['otp_expires_at']) < time()) {
            return redirect()->back()->with('error', 'Kode OTP sudah kadaluarsa. Silakan kirim ulang.');
        }

        // Cek lagi apakah email sudah dipakai orang lain selama proses OTP berlangsung
        if ($this->userModel->where('email', $registerData['email'])->first()) {
            session()->remove('temp_register_data');
            return redirect()->to('auth/register')->with('error', 'Email sudah terdaftar. Silakan gunakan email lain.');
        }

        // OTP valid — baru buat akun di database
        $this->userModel->insert([
            'name'     => $registerData['name'],
            'email'    => $registerData['email'],
            'password' => $registerData['password'],
        ]);

        $newUserId = $this->userModel->getInsertID();

        // Buat default kategori untuk user baru
        $kategoriModel = new KategoriModel();
        $kategoriModel->createDefaultsForUser($newUserId);

        // Hapus session sementara
        session()->remove('temp_register_data');

        return redirect()->to('auth/login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    /**
     * Kirim ulang OTP verifikasi pendaftaran
     */
    public function resendRegisterOtp()
    {
        $registerData = session()->get('temp_register_data');

        if (!$registerData) {
            return redirect()->to('auth/register');
        }

        // Generate OTP baru
        $otpCode   = sprintf("%06d", mt_rand(1, 999999));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // Update OTP di session
        $registerData['otp_code']       = $otpCode;
        $registerData['otp_expires_at'] = $expiresAt;
        session()->set('temp_register_data', $registerData);

        if (!$this->sendOtpEmail($registerData['email'], $otpCode, 'register')) {
            return redirect()->back()->with('error', 'Gagal mengirim ulang email verifikasi.');
        }

        return redirect()->back()->with('success', 'Kode verifikasi baru telah dikirim.');
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
