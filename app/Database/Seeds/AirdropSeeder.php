<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\UserModel;
use App\Models\KategoriModel;
use App\Models\PemasukanModel;
use App\Models\PengeluaranModel;
use App\Models\BudgetingModel;
use App\Models\WishlistModel;
use App\Models\TabunganModel;

class AirdropSeeder extends Seeder
{
    public function run()
    {
        $userId = 2;

        // 1. Bersihkan data HANYA untuk User ID 2 agar tidak menumpuk saat di-run ulang
        $this->db->table('tabungan')->where('user_id', $userId)->delete();
        $this->db->table('wishlist')->where('user_id', $userId)->delete();
        $this->db->table('budgeting')->where('user_id', $userId)->delete();
        $this->db->table('pengeluaran')->where('user_id', $userId)->delete();
        $this->db->table('pemasukan')->where('user_id', $userId)->delete();
        $this->db->table('kategori')->where('user_id', $userId)->delete();

        // 2. Buat / Update User ID 2
        $userData = [
            'id'       => $userId,
            'name'     => 'Airdrop Uno',
            'email'    => 'airdropuno108@gmail.com',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'default_metode_pembayaran' => 'BCA Transfer',
        ];

        // Gunakan query builder agar bisa force insert ID 2
        $existingUser = $this->db->table('users')->where('id', $userId)->get()->getRow();
        if (!$existingUser) {
            $this->db->table('users')->insert($userData);
        } else {
            $this->db->table('users')->where('id', $userId)->update($userData);
        }

        // 3. Buat Kategori yang Sangat Detail
        $kategoriIncome = [
            'Gaji Utama' => ['type' => 'pemasukan', 'icon' => 'ti ti-briefcase', 'color' => '#2fb344'],
            'Freelance' => ['type' => 'pemasukan', 'icon' => 'ti ti-device-laptop', 'color' => '#17a2b8'],
            'Investasi' => ['type' => 'pemasukan', 'icon' => 'ti ti-chart-arrows-vertical', 'color' => '#fd7e14'],
            'Bonus' => ['type' => 'pemasukan', 'icon' => 'ti ti-gift', 'color' => '#6f42c1'],
        ];

        $kategoriExpense = [
            'Makanan & Minuman' => ['type' => 'pengeluaran', 'icon' => 'ti ti-soup', 'color' => '#e67e22'],
            'Transportasi' => ['type' => 'pengeluaran', 'icon' => 'ti ti-car', 'color' => '#3498db'],
            'Tagihan & Utilitas' => ['type' => 'pengeluaran', 'icon' => 'ti ti-receipt', 'color' => '#e74c3c'],
            'Belanja' => ['type' => 'pengeluaran', 'icon' => 'ti ti-shopping-cart', 'color' => '#9b59b6'],
            'Hiburan & Hobi' => ['type' => 'pengeluaran', 'icon' => 'ti ti-movie', 'color' => '#f1c40f'],
            'Kesehatan' => ['type' => 'pengeluaran', 'icon' => 'ti ti-heart-rate-monitor', 'color' => '#2ecc71'],
            'Donasi & Sosial' => ['type' => 'pengeluaran', 'icon' => 'ti ti-heart-handshake', 'color' => '#1abc9c'],
        ];

        $katMap = [];
        $kategoriModel = new KategoriModel();

        foreach (array_merge($kategoriIncome, $kategoriExpense) as $name => $data) {
            $kategoriModel->insert([
                'user_id' => $userId,
                'name'    => $name,
                'type'    => $data['type'],
                'icon'    => $data['icon'],
                'color'   => $data['color'],
            ]);
            $katMap[$name] = $kategoriModel->getInsertID();
        }

        // 4. Seeder Pemasukan (Data 6 Bulan Terakhir)
        $pemasukanModel = new PemasukanModel();
        $pengeluaranModel = new PengeluaranModel();
        
        $currentMonth = (int)date('m');
        $currentYear = (int)date('Y');
        
        for ($i = 5; $i >= 0; $i--) {
            $month = $currentMonth - $i;
            $year = $currentYear;
            if ($month <= 0) {
                $month += 12;
                $year--;
            }

            // Gaji Utama setiap tanggal 1
            $pemasukanModel->insert([
                'user_id'   => $userId,
                'kategori_id' => $katMap['Gaji Utama'],
                'tanggal'   => sprintf('%04d-%02d-01', $year, $month),
                'nominal'   => 45000000, // 45 Juta agar balance sangat positif
                'sumber'    => 'Kantor Utama',
                'deskripsi' => 'Gaji Bulan ' . date("F", mktime(0, 0, 0, $month, 10)),
            ]);

            // Freelance secara random (tidak setiap bulan, probabilitas 80%)
            if (rand(1, 10) <= 8) {
                $pemasukanModel->insert([
                    'user_id'   => $userId,
                    'kategori_id' => $katMap['Freelance'],
                    'tanggal'   => sprintf('%04d-%02d-%02d', $year, $month, rand(10, 20)),
                    'nominal'   => rand(50, 150) * 100000, // 5 - 15 Juta
                    'sumber'    => 'Klien Luar Negeri',
                    'deskripsi' => 'Pembayaran Project Web Development',
                ]);
            }

            // Hasil Investasi (probabilitas 40%)
            if (rand(1, 10) <= 4) {
                $pemasukanModel->insert([
                    'user_id'   => $userId,
                    'kategori_id' => $katMap['Investasi'],
                    'tanggal'   => sprintf('%04d-%02d-%02d', $year, $month, rand(21, 28)),
                    'nominal'   => rand(5, 20) * 100000,
                    'sumber'    => 'Dividen Saham',
                    'deskripsi' => 'Dividen BBCA & BBRI',
                ]);
            }
        }

        // 5. Seeder Pengeluaran (Data Super Detail, 3-6 transaksi per hari)
        $metodeList = ['BCA Transfer', 'GoPay', 'OVO', 'ShopeePay', 'Cash', 'Kartu Kredit Mandiri'];
        $deskripsiList = [
            'Makanan & Minuman' => ['Kopi Kenangan', 'Starbucks', 'Makan Siang Nasi Padang', 'GoFood Sate Taichan', 'Groceries Superindo', 'Beli Air Galon', 'McDonalds', 'Martabak Malam'],
            'Transportasi' => ['Isi Bensin Shell', 'Gojek ke Kantor', 'GrabCar', 'Topup E-Toll', 'Parkir Mall'],
            'Belanja' => ['Beli Kaos Uniqlo', 'Skincare Sociolla', 'Checkout Shopee (Peralatan Rumah)', 'Beli Sepatu Sneakers'],
            'Hiburan & Hobi' => ['Tiket Bioskop XXI', 'Topup Diamond MLBB', 'Langganan Netflix', 'Spotify Premium', 'Main Biliard'],
            'Kesehatan' => ['Beli Vitamin di Apotek', 'Konsultasi Halodoc', 'Member Gym', 'Beli Obat Flu'],
            'Donasi & Sosial' => ['Sedekah Jumat', 'Kitabisa.com (Bantu Korban Bencana)', 'Sumbangan Panti Asuhan'],
        ];

        // Tagihan Bulanan Fix
        $tagihanBulanan = [
            ['desc' => 'Token Listrik PLN', 'nom' => 500000, 'tgl' => 5],
            ['desc' => 'Internet Indihome', 'nom' => 350000, 'tgl' => 10],
            ['desc' => 'Cicilan KPR', 'nom' => 4500000, 'tgl' => 15],
            ['desc' => 'BPJS Kesehatan', 'nom' => 150000, 'tgl' => 20],
        ];

        for ($i = 5; $i >= 0; $i--) {
            $month = $currentMonth - $i;
            $year = $currentYear;
            if ($month <= 0) {
                $month += 12;
                $year--;
            }
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $maxDay = ($i == 0) ? (int)date('d') : $daysInMonth;

            // Masukkan Tagihan Bulanan Fix
            foreach ($tagihanBulanan as $tagihan) {
                if ($tagihan['tgl'] <= $maxDay) {
                    $pengeluaranModel->insert([
                        'user_id'           => $userId,
                        'kategori_id'       => $katMap['Tagihan & Utilitas'],
                        'tanggal'           => sprintf('%04d-%02d-%02d', $year, $month, $tagihan['tgl']),
                        'nominal'           => $tagihan['nom'],
                        'metode_pembayaran' => 'BCA Transfer',
                        'deskripsi'         => $tagihan['desc'],
                    ]);
                }
            }

            // Pengeluaran Harian Acak (1-3 kali sehari, agar pengeluaran terkontrol)
            for ($day = 1; $day <= $maxDay; $day++) {
                $dailyTransactions = rand(1, 3);
                
                for ($t = 0; $t < $dailyTransactions; $t++) {
                    // Pilih kategori random untuk harian (kecuali Tagihan karena sudah fix)
                    $katKeys = array_diff(array_keys($deskripsiList), ['Tagihan & Utilitas']);
                    $kName = $katKeys[array_rand($katKeys)];
                    
                    $dList = $deskripsiList[$kName];
                    $desc = $dList[array_rand($dList)];

                    // Logika Nominal
                    $nominal = 0;
                    if ($kName === 'Makanan & Minuman') {
                        $nominal = rand(2, 15) * 10000; // 20k - 150k
                        if (str_contains($desc, 'Groceries')) $nominal = rand(30, 80) * 10000;
                    } elseif ($kName === 'Transportasi') {
                        $nominal = rand(15, 200) * 1000; // 15k - 200k
                    } elseif ($kName === 'Belanja') {
                        $nominal = rand(100, 1500) * 1000; // 100k - 1.5M
                    } else {
                        $nominal = rand(50, 300) * 1000;
                    }

                    $pengeluaranModel->insert([
                        'user_id'           => $userId,
                        'kategori_id'       => $katMap[$kName],
                        'tanggal'           => sprintf('%04d-%02d-%02d', $year, $month, $day),
                        'nominal'           => $nominal,
                        'metode_pembayaran' => $metodeList[array_rand($metodeList)],
                        'deskripsi'         => $desc,
                    ]);
                }
            }
        }

        // 6. Seeder Budgeting (Untuk 3 Bulan Terakhir)
        $budgetingModel = new BudgetingModel();
        $budgetList = [
            'Makanan & Minuman' => 4000000,
            'Transportasi' => 1500000,
            'Tagihan & Utilitas' => 5500000,
            'Belanja' => 2000000,
            'Hiburan & Hobi' => 1000000,
            'Kesehatan' => 500000,
            'Donasi & Sosial' => 500000,
        ];

        for ($i = 2; $i >= 0; $i--) {
            $month = $currentMonth - $i;
            $year = $currentYear;
            if ($month <= 0) {
                $month += 12;
                $year--;
            }
            foreach ($budgetList as $kName => $bNominal) {
                $budgetingModel->insert([
                    'user_id'        => $userId,
                    'kategori_id'    => $katMap[$kName],
                    'bulan'          => sprintf('%02d', $month),
                    'tahun'          => $year,
                    'nominal_budget' => $bNominal,
                ]);
            }
        }

        // 7. Seeder Wishlist
        $wishlistModel = new WishlistModel();
        $wishlistItems = [
            ['nama' => 'Iphone 15 Pro Max', 'harga' => 25000000, 'prio' => 'tinggi', 'status' => 'menabung'],
            ['nama' => 'Trip Korea Selatan 7 Hari', 'harga' => 18000000, 'prio' => 'sedang', 'status' => 'belum_mulai'],
            ['nama' => 'Kursi Ergonomis Herman Miller', 'harga' => 15000000, 'prio' => 'rendah', 'status' => 'tercapai'],
            ['nama' => 'Honda PCX 160 ABS', 'harga' => 36000000, 'prio' => 'tinggi', 'status' => 'menabung'],
            ['nama' => 'Logitech MX Master 3S', 'harga' => 1800000, 'prio' => 'sedang', 'status' => 'tercapai'],
        ];

        $wIds = [];
        foreach ($wishlistItems as $item) {
            $wishlistModel->insert([
                'user_id'      => $userId,
                'nama_barang'  => $item['nama'],
                'harga_target' => $item['harga'],
                'prioritas'    => $item['prio'],
                'status'       => $item['status'],
                'catatan'      => 'Airdrop rewards target',
            ]);
            $wIds[$item['nama']] = $wishlistModel->getInsertID();
        }

        // 8. Seeder Tabungan
        $tabunganModel = new TabunganModel();
        
        $tabunganModel->insert([
            'user_id'           => $userId,
            'wishlist_id'       => $wIds['Iphone 15 Pro Max'],
            'nama_tabungan'     => 'Dana HP Baru',
            'target_nominal'    => 25000000,
            'nominal_terkumpul' => 15500000,
            'deadline'          => date('Y-m-d', strtotime('+4 months')),
            'status'            => 'proses',
        ]);

        $tabunganModel->insert([
            'user_id'           => $userId,
            'wishlist_id'       => $wIds['Honda PCX 160 ABS'],
            'nama_tabungan'     => 'DP Motor',
            'target_nominal'    => 36000000,
            'nominal_terkumpul' => 5000000,
            'deadline'          => date('Y-m-d', strtotime('+12 months')),
            'status'            => 'proses',
        ]);

        $tabunganModel->insert([
            'user_id'           => $userId,
            'wishlist_id'       => $wIds['Kursi Ergonomis Herman Miller'],
            'nama_tabungan'     => 'Setup WFH',
            'target_nominal'    => 15000000,
            'nominal_terkumpul' => 15000000,
            'deadline'          => date('Y-m-d', strtotime('-2 months')),
            'status'            => 'selesai',
        ]);

        echo "\nSeeder Super Lengkap untuk AirdropUno berhasil dijalankan! \n";
        echo "Email Login: airdropuno108@gmail.com\n";
        echo "Password: password\n";
        echo "Total Data: Ratusan transaksi dalam 6 bulan terakhir!\n";
    }
}
