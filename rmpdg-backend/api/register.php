<?php
/**
 * api/register.php
 * Endpoint untuk pendaftaran akun pelanggan baru ke database MySQL (`pelanggan`).
 * 
 * POST /api/register.php -> nama, kontak (email/telepon), password
 */

require 'config.php';
require 'session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(['error' => 'Method tidak didukung'], 405);
}

$ipKey = 'register_' . ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
checkRateLimit($ipKey, 5, 300);

$data = readJsonBody();
$nama     = trim($data['nama'] ?? '');
$kontak   = trim($data['kontak'] ?? '');
$password = $data['password'] ?? '';

if (!$nama || !$kontak || !$password) {
    sendResponse(['error' => 'Nama, email/telepon, dan kata sandi wajib diisi'], 400);
}

if (strlen($password) < 6) {
    sendResponse(['error' => 'Kata sandi minimal 6 karakter'], 400);
}

// Cek apakah kontak sudah terdaftar di database
$stmt = $pdo->prepare('SELECT id FROM pelanggan WHERE kontak = ?');
$stmt->execute([$kontak]);
if ($stmt->fetch()) {
    recordFailedAttempt($ipKey, 5, 300);
    sendResponse(['error' => 'Email atau Nomor Telepon sudah terdaftar. Silakan login.'], 400);
}

// Simpan akun ke database dengan password hash aman
$hash = password_hash($password, PASSWORD_BCRYPT);
$stmt = $pdo->prepare('INSERT INTO pelanggan (nama, kontak, password_hash) VALUES (?, ?, ?)');
$stmt->execute([$nama, $kontak, $hash]);
$newId = $pdo->lastInsertId();

clearRateLimit($ipKey);

startAuthSession();
session_regenerate_id(true);
$_SESSION['user_id']  = $newId;
$_SESSION['is_admin'] = false;
$_SESSION['nama']     = $nama;
$_SESSION['kontak']   = $kontak;

sendResponse([
    'success' => true,
    'message' => 'Pendaftaran akun berhasil!',
    'user' => [
        'id'     => $newId,
        'nama'   => $nama,
        'kontak' => $kontak
    ]
], 201);
