<?php

class Admin {
    const Admin = "Muhammad Fatih";
    const PwAdmin = "12";

}

class User {
    protected $username;
    protected $password;

    public function Login(){
        echo "Masukkan username : ";
        $this->username = trim(fgets(STDIN));
        echo "Masukkan Password Anda : ";
        $this->password = trim(fgets(STDIN));
    }

    public function __construct(){
        echo "Selamat datang di APP work flow";
        $this->Login();
    }
}


$user = new User;