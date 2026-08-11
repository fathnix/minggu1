<?php

require_once 'Conn.php';
$loggedInUserId = null;
$loggedInUsername = null;

// Helper function untuk menerima input dari terminal
function getInput($prompt) {
    echo $prompt;
    return trim(fgets(STDIN));
}

// ==========================================
// LOOP UTAMA APLIKASI
// ==========================================
while (true) {
    echo "\n======================================\n";
    echo "       SISTEM GUDANG CLI APLIKASI     \n";
    echo "======================================\n";

    if ($loggedInUserId === null) {
        // --- MENU SEBELUM LOGIN ---
        echo "1. Login\n";
        echo "2. Register\n";
        echo "3. Keluar Program\n";
        $pilihan = getInput("Pilih menu (1/2/3): ");

        if ($pilihan == '1') {
            // PROSES LOGIN
            $username = getInput("Username: ");
            $password = getInput("Password: ");

            $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifikasi password (menggunakan password_verify karena saat register kita hash)
            if ($user && password_verify($password, $user['password'])) {
                $loggedInUserId = $user['id'];
                $loggedInUsername = $user['username'];
                echo "\n[SUKSES] Selamat datang, $loggedInUsername!\n";
            } else {
                echo "\n[GAGAL] Username atau password salah!\n";
            }

        } elseif ($pilihan == '2') {
            // PROSES REGISTER
            $username = getInput("Masukkan Username baru: ");
            $password = getInput("Masukkan Password baru: ");
            
            // Hash password agar aman di database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            try {
                $stmt = $pdo->prepare("INSERT INTO user (username, password) VALUES (?, ?)");
                $stmt->execute([$username, $hashedPassword]);
                echo "\n[SUKSES] Registrasi berhasil! Silakan Login.\n";
            } catch (PDOException $e) {
                // Menampilkan pesan error asli dari database agar mudah dilacak
                echo "\n[GAGAL] Terjadi kesalahan: " . $e->getMessage() . "\n";
            }

        } elseif ($pilihan == '3') {
            echo "Keluar dari program. Bye!\n";
            exit;
        } else {
            echo "Pilihan tidak valid!\n";
        }

    } else {
        // --- MENU SETELAH LOGIN ---
        echo "User aktif: $loggedInUsername\n";
        echo "--------------------------------------\n";
        echo "1. Lihat Daftar Barang & Stok\n";
        echo "2. Titip Barang Baru ke Gudang\n";
        echo "3. Tambah Kategori Baru\n";
        echo "4. Logout\n";
        $pilihan = getInput("Pilih aksi (1/2/3/4): ");

        if ($pilihan == '1') {
            // READ: Menampilkan data dengan relasi (JOIN)
            $stmt = $pdo->query("
                SELECT g.id, g.nama_barang, k.nama_kategori, g.jumlah_barang 
                FROM gudang g
                JOIN kategori k ON g.kategori_id = k.id
            ");
            $barangs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "\n--- DAFTAR BARANG DI GUDANG ---\n";
            foreach ($barangs as $b) {
                echo "ID: {$b['id']} | Nama: {$b['nama_barang']} | Kategori: {$b['nama_kategori']} | Stok: {$b['jumlah_barang']}\n";
            }

        } elseif ($pilihan == '2') {
            // CREATE: Menambah master barang sekaligus mencatat transaksi masuk
            echo "\n--- TITIP BARANG BARU ---\n";
            
            // Tampilkan kategori dulu agar user tahu ID-nya
            $stmt = $pdo->query("SELECT * FROM kategori");
            $kategoris = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "Daftar Kategori Tersedia:\n";
            foreach ($kategoris as $k) {
                echo "- ID: {$k['id']} | Nama: {$k['nama_kategori']}\n";
            }

            $nama_barang = getInput("Nama Barang: ");
            $kategori_id = getInput("ID Kategori: ");
            $jumlah = (int)getInput("Jumlah titipan awal: ");

            try {
                // Gunakan Transaction agar jika salah satu gagal, semuanya batal (Data Integrity)
                $pdo->beginTransaction();

                // 1. Masukkan ke tabel gudang
                $stmtGudang = $pdo->prepare("INSERT INTO gudang (user_id, nama_barang, jumlah_barang, kategori_id) VALUES (?, ?, ?, ?)");
                $stmtGudang->execute([$loggedInUserId, $nama_barang, $jumlah, $kategori_id]);
                
                // Ambil ID gudang yang baru saja dibuat
                $gudang_id = $pdo->lastInsertId();

                // 2. Catat riwayatnya di tabel transaksi
                $jenis_transaksi = 1; // Anggap 1 = Masuk
                $tanggal = date('Y-m-d H:i:s');
                $stmtTransaksi = $pdo->prepare("INSERT INTO transaksi (user_id, jenis_transaksi, tanggal, jumlah, gudang_id) VALUES (?, ?, ?, ?, ?)");
                $stmtTransaksi->execute([$loggedInUserId, $jenis_transaksi, $tanggal, $jumlah, $gudang_id]);

                $pdo->commit();
                echo "\n[SUKSES] Barang berhasil dititipkan dan dicatat di riwayat transaksi!\n";

            } catch (Exception $e) {
                $pdo->rollBack();
                echo "\n[GAGAL] Terjadi kesalahan: " . $e->getMessage() . "\n";
            }

        } elseif ($pilihan == '3') {
            // CREATE: Tambah kategori (syarat sebelum bisa titip barang)
            $nama_kategori = getInput("\nNama Kategori Baru: ");
            $stmt = $pdo->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
            $stmt->execute([$nama_kategori]);
            echo "[SUKSES] Kategori berhasil ditambahkan!\n";

        } elseif ($pilihan == '4') {
            // LOGOUT
            $loggedInUserId = null;
            $loggedInUsername = null;
            echo "\n[SUKSES] Anda telah logout.\n";
        } else {
            echo "Pilihan tidak valid!\n";
        }
    }
}
?>