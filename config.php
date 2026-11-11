<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$user = 'root';
$pass = '';

try {
    // 1. Koneksi awal tanpa database untuk memastikan database 'dtitik_sudut_pos' ada
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buat database jika belum ada
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `dtitik_sudut_pos`");
    $pdo->exec("USE `dtitik_sudut_pos`");

    // 2. Otomatis buat tabel admins jika belum ada
    $pdo->exec("CREATE TABLE IF NOT EXISTS `admins` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `username` VARCHAR(50) NOT NULL,
      `private_key_hash` VARCHAR(255) NOT NULL
    )");

    // Cek apakah data admin sudah ada, jika belum masukkan default
    $stmt = $pdo->query("SELECT COUNT(*) FROM `admins`");
    if ($stmt->fetchColumn() == 0) {
        $default_key = password_hash('SECRET-KEY-2026', PASSWORD_DEFAULT);
        $insertAdmin = $pdo->prepare("INSERT INTO `admins` (`username`, `private_key_hash`) VALUES (?, ?)");
        $insertAdmin->execute(['admin_oksan', $default_key]);
    }

    // 3. Otomatis buat tabel products jika belum ada
    $pdo->exec("CREATE TABLE IF NOT EXISTS `products` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `name_encrypted` TEXT NOT NULL,
      `price_encrypted` TEXT NOT NULL,
      `image_url` VARCHAR(255) DEFAULT NULL,
      `stock` INT NOT NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

} catch (\PDOException $e) {
    die("Koneksi atau Inisialisasi Database Gagal: " . $e->getMessage());
}

// Konfigurasi Enkripsi AES-256
define('ENCRYPTION_KEY', 'aero_secure_key_dtitik_sudut_2026');
define('CIPHER_METHOD', 'AES-256-CBC');

function encryptData($data) {
    $ivlen = openssl_cipher_iv_length(CIPHER_METHOD);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $encrypted = openssl_encrypt($data, CIPHER_METHOD, ENCRYPTION_KEY, 0, $iv);
    return base64_encode($iv . $encrypted);
}

function decryptData($data) {
    $c = base64_decode($data);
    $ivlen = openssl_cipher_iv_length(CIPHER_METHOD);
    $iv = substr($c, 0, $ivlen);
    $encrypted_string = substr($c, $ivlen);
    return openssl_decrypt($encrypted_string, CIPHER_METHOD, ENCRYPTION_KEY, 0, $iv);
}
?>