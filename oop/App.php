<?php

class Admin {
    const Admin = "Muhammad Fatih";
    const PwAdmin = "12";
    const PwManajer = "13";
    const PwKaryawan = "11";
    const PwCeo = "99";

}

class User {
    protected $username;
    protected $password;

    // public $Logein = false;

    public function Login(){
        echo "=== Login ===" . PHP_EOL; 
        echo "Masukkan username : ";
        $this->username = trim(fgets(STDIN));
        echo "Masukkan Password Anda : ";
        $this->password = trim(fgets(STDIN));

        if(empty($this->username) || empty($this->password)){
            echo "Username atau Password tidak boleh kosong" . PHP_EOL;
            $this->Login();
        }else{
            $this->Dashboard();
        }
    }

    public function __construct(){
        $this->Login();
    }

    public function Dashboard(){
        echo "Welcome di admin";
    }
    
}

class Position extends User{
    public $position;
    
    public function SetPosition(){
        switch ($this->password) {
            case Admin::PwAdmin :
                $this->position = "Admin";
                break;

            case Admin::PwManajer :
                $this->position = "Manajer";
                break;

            case Admin::PwKaryawan :
                $this->position = "Karyawan";
                break;

            case Admin::PwCeo :
                $this->position = "CEO";
                break;
            
        }
    }

    public function Dashboard(){
        $this->SetPosition();
        echo "=========== Dashboard ===========" . PHP_EOL;
        echo "Selamat Datang " . $this->username . "!" . PHP_EOL;
        echo "Jabatan Anda " . $this->position . PHP_EOL;
    }
}


$user = new Position;