<?php

$host = 'localhost';
$usn = 'root';
$pw = '12';
$db = 'db_artikel';

try{
    $conn = new PDO ("mysql:host=$host;dbname=$db", $usn, $pw);
    // PENANGANAN ERROR KALAU PAKAI GUI
    // $conn->setAttribut(PDO::ATTR_ERMODE, PDO::ERMODE_EXCEPTION);
    // echo "koneksi berhasil";
}catch(PDOException $e){
    // PENANGANAN ERROR
    echo $e->getMessage();
}   