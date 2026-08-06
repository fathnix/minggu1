<?php

// 1. VARIABEL & TIPE DATA
// Menggunakan tipe data Array untuk menyimpan daftar tugas
$daftarTugas = [];

// 2. FUNGSI (Functions)
// Fungsi untuk merapikan tampilan menu agar bisa dipanggil berulang kali
function tampilkanMenu() {
    echo "\n=== APLIKASI TO-DO LIST ===\n";
    echo "1. Lihat Tugas\n";
    echo "2. Tambah Tugas\n";
    echo "3. Hapus Tugas\n";
    echo "4. Keluar\n";
    echo "Pilih menu (1-4): ";
}

// 3. KONTROL ALUR (Perulangan / Loops)
// while(true) membuat aplikasi terus berjalan sampai ada perintah exit
while (true) {
    tampilkanMenu();
    
    // Menerima input dari pengguna (menggunakan STDIN di CLI PHP)
    $pilihan = trim(fgets(STDIN));

    // 4. KONTROL ALUR (Percabangan / Switch-Case)
    switch ($pilihan) {
        case '1':
            // Mengecek apakah array kosong
            if (empty($daftarTugas)) {
                echo "[!] Daftar tugas masih kosong.\n";
            } else {
                echo "\n--- Daftar Tugas Anda ---\n";
                // Perulangan foreach untuk membaca isi array
                foreach ($daftarTugas as $index => $tugas) {
                    echo ($index + 1) . ". " . $tugas . "\n";
                }
            }
            break;

        case '2':
            echo "Masukkan tugas baru: ";
            $tugasBaru = trim(fgets(STDIN));
            
            // 5. ERROR HANDLING & VALIDASI INPUT
            // Mencegah pengguna memasukkan tugas yang kosong
            if ($tugasBaru === "") {
                echo "[!] Error: Tugas tidak boleh kosong!\n";
            } else {
                $daftarTugas[] = $tugasBaru; // Memasukkan data ke array
                echo "[V] Tugas '$tugasBaru' berhasil ditambahkan!\n";
            }
            break;

        case '3':
            echo "Masukkan nomor tugas yang ingin dihapus: ";
            $nomor = trim(fgets(STDIN));
            
            // 5. ERROR HANDLING LANJUTAN
            // Memastikan input adalah angka DAN nomor index tersebut ada di dalam array
            if (is_numeric($nomor) && isset($daftarTugas[$nomor - 1])) {
                $tugasDihapus = $daftarTugas[$nomor - 1];
                unset($daftarTugas[$nomor - 1]); // Menghapus data dari array
                
                // Menyusun ulang nomor urut (index) array
                $daftarTugas = array_values($daftarTugas); 
                echo "[V] Tugas '$tugasDihapus' berhasil dihapus!\n";
            } else {
                echo "[!] Error: Nomor tugas tidak valid atau tidak ditemukan!\n";
            }
            break;

        case '4':
            echo "Terima kasih telah menggunakan aplikasi ini...\n";
            exit; // Menghentikan eksekusi program

        default:
            // Error handling jika pengguna mengetik angka di luar 1-4 atau huruf
            echo "[!] Pilihan tidak valid. Silakan ketik angka 1, 2, 3, atau 4.\n";
            break;
    }
}

?>