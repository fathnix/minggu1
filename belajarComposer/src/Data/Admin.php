<?php
namespace Fatih\Data;

class Admin extends People{
    public $password;
    public $verif;

    public function Dashboard(){
        echo "Selamat Datang di Dashboard Admin";
    }

    public function admin(){
        echo "Masukkan password : ";
        $this->password = trim(fgets(STDIN));
    }


    public function logAdmin(){
        echo "Apakah anda memiliki Passwod admin? (y/n): ";
        $this->verif = trim(fgets(STDIN));
    }

}
