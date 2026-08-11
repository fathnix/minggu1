<?php

class Conn{
    private $host = "localhost";
    private $db = "db_gudang_app";
    private $usn = "root";
    private $pw = "12";
    public $conn;

    public function getConnect(){
        $this->conn = new mysqli($this->host, $this->usn, $this->pw, $this->db);

        if($this->conn->connect_error){
            die ("Koneksi db gagal" . $this->conn->connect_error . PHP_EOL);
        }

        return $this->conn;
    }
}

