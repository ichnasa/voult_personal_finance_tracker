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

class DemoSeeder extends Seeder
{
    public function run()
    {
        // Models
        $userModel = new UserModel();
        $kategoriModel = new KategoriModel();
        $pemasukanModel = new PemasukanModel();
        $pengeluaranModel = new PengeluaranModel();
        $budgetingModel = new BudgetingModel();
        $wishlistModel = new WishlistModel();
        $tabunganModel = new TabunganModel();

        // 1. Bersihkan data lama (opsional, hati-hati jika dipakai di production)
        $this->db->disableForeignKeyChecks();
        $this->db->table('tabungan')->truncate();
        $this->db->table('wishlist')->truncate();
        $this->db->table('budgeting')->truncate();
        $this->db->table('pengeluaran')->truncate();
        $this->db->table('pemasukan')->truncate();
        $this->db->table('kategori')->truncate();
        $this->db->table('users')->truncate();
        $this->db->enableForeignKeyChecks();

        // 2. Buat User Demo
        $demoUser = [
            'name' => 'User Demo',
            'email' => 'demo@fintrack.local',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'default_metode_pembayaran' => 'Cash',
        ];
        $userModel->insert($demoUser);
        $userId = $userModel->getInsertID();

        // 3. Buat Kategori Default
        $kategoriModel->createDefaultsForUser($userId);

        // Ambil ID Kategori
        $kategoris = $kategoriModel->getByUser($userId);
        $kategoriIds = [];
        $katMap = [];
        foreach ($kategoris as $k) {
            $kategoriIds[] = $k['id'];
            $katMap[$k['name']] = $k['id'];
        }

        // Setup Bulan (3 bulan terakhir sampai bulan ini)
        $currentMonth = (int) date('m');
        $currentYear = (int) date('Y');

        // 4. Seeder Pemasukan (Gaji bulanan & Bonus)
        for ($i = 2; $i >= 0; $i--) {
            $month = $currentMonth - $i;
            $year = $currentYear;
            if ($month <= 0) {
                $month += 12;
                $year--;
            }

            // Gaji
            $pemasukanModel->insert([
                'user_id' => $userId,
                'tanggal' => sprintf('%04d-%02d-01', $year, $month),
                'nominal' => 15000000, // 15 Juta
                'sumber' => 'Gaji Bulanan',
                'deskripsi' => 'Gaji PT Makmur Sejahtera',
            ]);

            // Bonus (sesekali)
            if ($i == 1) { // Bulan lalu
                $pemasukanModel->insert([
                    'user_id' => $userId,
                    'tanggal' => sprintf('%04d-%02d-15', $year, $month),
                    'nominal' => 3000000,
                    'sumber' => 'Bonus Project',
                    'deskripsi' => 'Bonus penyelesaian project A',
                ]);
            }
        }

        // 5. Seeder Pengeluaran (Acak selama 3 bulan terakhir)
        $metodeList = ['Cash', 'Transfer', 'E-Wallet', 'Debit', 'Kredit'];
        $deskripsiList = [
            'Makanan' => ['Makan Siang Nasi Padang', 'Beli Kopi', 'Makan Malam Seafood', 'Groceries Mingguan', 'Cemilan'],
            'Transportasi' => ['Isi Bensin Mobil', 'Grab/Gojek', 'Tiket Kereta', 'Tol', 'Parkir'],
            'Hiburan' => ['Nonton Bioskop', 'Langganan Netflix', 'Beli Game Steam', 'Konser Musik'],
            'Pendidikan' => ['Buku Pemrograman', 'Kursus Online Udemy', 'SPP Anak'],
            'Belanja' => ['Beli Baju', 'Sepatu Baru', 'Skincare', 'Gadget Aksesoris'],
            'Tagihan' => ['Listrik PLN', 'Air PDAM', 'Internet Indihome', 'Cicilan Rumah'],
        ];

        for ($i = 2; $i >= 0; $i--) {
            $month = $currentMonth - $i;
            $year = $currentYear;
            if ($month <= 0) {
                $month += 12;
                $year--;
            }

            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            // Batasi hari jika ini bulan berjalan
            $maxDay = ($i == 0) ? (int) date('d') : $daysInMonth;

            // Generate 30 pengeluaran per bulan
            for ($j = 0; $j < 30; $j++) {
                $day = rand(1, $maxDay);
                // Pilih Kategori Random
                $kName = array_rand($katMap);
                $kId = $katMap[$kName];

                // Pilih Deskripsi Random sesuai kategori
                $dList = $deskripsiList[$kName] ?? ['Pengeluaran Lain'];
                $desc = $dList[array_rand($dList)];

                // Nominal Random (10rb - 500rb)
                $nominal = rand(10, 500) * 1000;

                // Jika tagihan biasanya lebih besar
                if ($kName == 'Tagihan')
                    $nominal = rand(200, 1500) * 1000;

                $pengeluaranModel->insert([
                    'user_id' => $userId,
                    'kategori_id' => $kId,
                    'tanggal' => sprintf('%04d-%02d-%02d', $year, $month, $day),
                    'nominal' => $nominal,
                    'metode_pembayaran' => $metodeList[array_rand($metodeList)],
                    'deskripsi' => $desc,
                ]);
            }
        }

        // 6. Seeder Budgeting (Bulan Berjalan)
        $budgetList = [
            'Makanan' => 3000000,
            'Transportasi' => 1500000,
            'Hiburan' => 1000000,
            'Tagihan' => 2500000,
            'Belanja' => 2000000,
        ];

        foreach ($budgetList as $kName => $bNominal) {
            if (isset($katMap[$kName])) {
                $budgetingModel->insert([
                    'user_id' => $userId,
                    'kategori_id' => $katMap[$kName],
                    'bulan' => sprintf('%02d', $currentMonth),
                    'tahun' => $currentYear,
                    'nominal_budget' => $bNominal,
                ]);
            }
        }

        // 7. Seeder Wishlist
        $wishlistItems = [
            ['nama' => 'Macbook Pro M3', 'harga' => 30000000, 'prio' => 'tinggi', 'status' => 'belum_mulai'],
            ['nama' => 'Liburan ke Jepang', 'harga' => 15000000, 'prio' => 'sedang', 'status' => 'belum_mulai'],
            ['nama' => 'PS5 Pro', 'harga' => 9000000, 'prio' => 'rendah', 'status' => 'tercapai'],
            ['nama' => 'Sepeda Gunung', 'harga' => 5000000, 'prio' => 'sedang', 'status' => 'belum_mulai'],
        ];

        $wIds = [];
        foreach ($wishlistItems as $item) {
            $wishlistModel->insert([
                'user_id' => $userId,
                'nama_barang' => $item['nama'],
                'harga_target' => $item['harga'],
                'prioritas' => $item['prio'],
                'status' => $item['status'],
                'catatan' => 'Imipian tahun ini',
            ]);
            $wIds[$item['nama']] = $wishlistModel->getInsertID();
        }

        // 8. Seeder Tabungan
        // Tabungan untuk Macbook (proses)
        $tabunganModel->insert([
            'user_id' => $userId,
            'wishlist_id' => $wIds['Macbook Pro M3'],
            'nama_tabungan' => 'Tabungan Macbook Pro',
            'target_nominal' => 30000000,
            'nominal_terkumpul' => 12500000,
            'deadline' => date('Y-m-d', strtotime('+6 months')),
            'status' => 'proses',
        ]);

        // Tabungan Liburan (proses)
        $tabunganModel->insert([
            'user_id' => $userId,
            'wishlist_id' => $wIds['Liburan ke Jepang'],
            'nama_tabungan' => 'Dana Liburan',
            'target_nominal' => 15000000,
            'nominal_terkumpul' => 3000000,
            'deadline' => date('Y-m-d', strtotime('+3 months')),
            'status' => 'proses',
        ]);

        // Tabungan PS5 (selesai)
        $tabunganModel->insert([
            'user_id' => $userId,
            'wishlist_id' => $wIds['PS5 Pro'],
            'nama_tabungan' => 'Tabungan PS5',
            'target_nominal' => 9000000,
            'nominal_terkumpul' => 9000000,
            'deadline' => date('Y-m-d', strtotime('-1 month')),
            'status' => 'selesai',
        ]);

        echo "\nSeeder Demo berhasil dijalankan! \n";
        echo "Email Login: demo@fintrack.local\n";
        echo "Password: password\n";
    }
}
