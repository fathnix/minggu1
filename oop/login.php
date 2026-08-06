<?php

class User {
    public $username;
    public $isLoggedIn = false;

    public function __construct() {
        echo "Masukkan username anda : ";
        $this->username = trim(fgets(STDIN));
    }

    public function loginSession() {
        // $this merujuk pada objek itu sendiri
        $this->isLoggedIn = true;
        return "Berhasil: Session aktif untuk user " . $this->username . "\n";
    }

    public function jalankanProgram() {
        if ($this->isLoggedIn) {
            return ">> Program CLI siap dijalankan oleh " . $this->username . "...\n";
        } else {
            return ">> Error: Silakan login dengan session terlebih dahulu!\n";
        }
    }
}

$userSatu = new User();
echo $userSatu->loginSession();

echo $userSatu->jalankanProgram();

?>