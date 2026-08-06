<?php
/**
 * api/auth.php
 * Endpoint untuk autentikasi login Admin & Pelanggan.
 * 
 * POST /api/auth.php -> Kirim email/kontak & password untuk verifikasi login
 */

require 'config.php';
require 'session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(['error' => 'Method tidak didukung'], 405);
}

$data = readJsonBody();
$email    = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (!$email || !$password) {
    sendResponse(['error' => 'Email/Username dan password wajib diisi'], 400);
}

$ipKey = 'auth_' . ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
checkRateLimit($ipKey, 5, 300);

startAuthSession();

// 1. Cari admin berdasarkan email atau nama di database
$stmt = $pdo->prepare('SELECT * FROM admin WHERE (email = ? OR nama = ?) AND is_aktif = 1 LIMIT 1');
$stmt->execute([$email, $email]);
$admin = $stmt->fetch();

// Verifikasi password hash admin (Strict password_verify tanpa backdoor)
if ($admin && password_verify($password, $admin['password_hash'])) {
    clearRateLimit($ipKey);
    session_regenerate_id(true);
    $_SESSION['user_id']  = $admin['id'];
    $_SESSION['is_admin'] = true;
    $_SESSION['nama']     = $admin['nama'];
    $_SESSION['email']    = $admin['email'];
    $_SESSION['role']     = $admin['role'];

    sendResponse([
        'success'  => true,
        'message'  => 'Login berhasil',
        'is_admin' => true,
        'redirect' => 'admin/dashboard.php',
        'admin'    => [
            'id'    => $admin['id'],
            'nama'  => $admin['nama'],
            'email' => $admin['email'],
            'role'  => $admin['role']
        ]
    ]);
}

// 2. Cari pelanggan berdasarkan kontak/nama di database
$stmt = $pdo->prepare('SELECT * FROM pelanggan WHERE kontak = ? OR nama = ? LIMIT 1');
$stmt->execute([$email, $email]);
$user = $stmt->fetch();

// Strict password_verify & tidak ada auto-create akun
if ($user && !empty($user['password_hash']) && password_verify($password, $user['password_hash'])) {
    clearRateLimit($ipKey);
    session_regenerate_id(true);
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['is_admin'] = false;
    $_SESSION['nama']     = $user['nama'];
    $_SESSION['kontak']   = $user['kontak'];

    sendResponse([
        'success'  => true,
        'message'  => 'Login berhasil',
        'is_admin' => false,
        'user'     => [
            'id'     => $user['id'],
            'nama'   => $user['nama'],
            'kontak' => $user['kontak']
        ]
    ]);
}

recordFailedAttempt($ipKey, 5, 300);
sendResponse(['error' => 'Email/No. Telepon atau kata sandi tidak valid'], 401);