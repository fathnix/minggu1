<?php

$host = 'localhost';
$dbname = 'db_gudang_app';
$dbuser = 'root'; // Sesuaikan dengan user phpmyadmin kamu
$dbpass = '12';     // Sesuaikan dengan password phpmyadmin kamu (biasanya kosong)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage() . "\n");
}
