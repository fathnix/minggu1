<?php
// Pastikan file conn.php menggunakan PDO dan menghasilkan variabel $conn
include 'conn.php'; 

// 1. FUNGSI (Functions)
function tampilkanMenu() {
    echo "\n=== APLIKASI TO-DO LIST ===\n";
    echo "1. Lihat Tugas\n";
    echo "2. Tambah Tugas\n";
    echo "3. Hapus Tugas\n";
    echo "4. Keluar\n";
    echo "Pilih menu (1-4): ";
}

// 2. KONTROL ALUR
while (true) {
    tampilkanMenu();
    
    $pilihan = trim(fgets(STDIN));

    switch ($pilihan) {
        case '1':
            try {
                // READ: Mengambil data dari database
                // GANTI 'nama_tabel' dengan nama tabel asli di database Anda
                $stmt = $conn->query("SELECT id, tugas FROM tugas");
                $daftarTugas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (empty($daftarTugas)) {
                    echo "[!] Daftar tugas masih kosong.\n";
                } else {
                    echo "\n--- Daftar Tugas Anda ---\n";
                    foreach ($daftarTugas as $row) {
                        // Menampilkan ID langsung dari database
                        echo $row['id'] . ". " . $row['tugas'] . "\n";
                    }
                }
            } catch (PDOException $e) {
                echo "[!] Error Database: " . $e->getMessage() . "\n";
            }
            break;

        case '2':
            echo "Masukkan tugas baru: ";
            $tugasBaru = trim(fgets(STDIN));
            
            if ($tugasBaru === "") {
                echo "[!] Error: Tugas tidak boleh kosong!\n";
            } else {
                try {
                    // CREATE: Memasukkan data ke database (menggunakan Prepared Statement agar aman)
                    $stmt = $conn->prepare("INSERT INTO tugas (tugas) VALUES (:tugas)");
                    $stmt->execute(['tugas' => $tugasBaru]);
                    
                    echo "[V] Tugas '$tugasBaru' berhasil ditambahkan!\n";
                } catch (PDOException $e) {
                    echo "[!] Error Database: " . $e->getMessage() . "\n";
                }
            }
            break;

        case '3':
            // Karena menggunakan database, kita menghapus berdasarkan ID (primary key)
            echo "Masukkan ID tugas yang ingin dihapus (lihat nomor ID pada menu 1): ";
            $idHapus = trim(fgets(STDIN));
            
            if (is_numeric($idHapus)) {
                try {
                    // DELETE: Menghapus dari database
                    $stmt = $conn->prepare("DELETE FROM tugas WHERE id = :id");
                    $stmt->execute(['id' => $idHapus]);
                    
                    // Mengecek apakah ada baris data yang terpengaruh (terhapus)
                    if ($stmt->rowCount() > 0) {
                        echo "[V] Tugas dengan ID $idHapus berhasil dihapus!\n";
                    } else {
                        echo "[!] Error: ID tugas tidak ditemukan di database!\n";
                    }
                } catch (PDOException $e) {
                    echo "[!] Error Database: " . $e->getMessage() . "\n";
                }
            } else {
                echo "[!] Error: ID tugas harus berupa angka!\n";
            }
            break;

        case '4':
            echo "Terima kasih telah menggunakan aplikasi ini...\n";
            exit; 

        default:
            echo "[!] Pilihan tidak valid. Silakan ketik angka 1, 2, 3, atau 4.\n";
            break;
    }
}
?>