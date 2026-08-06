<?php
/**
 * api/google-auth.php
 * Autentikasi via Google OAuth - Verifikasi ID token Google & buat/temukan akun pelanggan.
 */

require 'config.php';
require 'session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(['error' => 'Method tidak didukung'], 405);
}

$data = readJsonBody();
$idToken     = trim($data['id_token'] ?? '');
$accessToken = trim($data['access_token'] ?? '');

if (!$idToken && !$accessToken) {
    sendResponse(['error' => 'Token Google tidak ditemukan'], 400);
}

$googleUser = null;

if ($idToken) {
    // Verifikasi ID Token dengan Google API
    $url = 'https://oauth2.googleapis.com/tokeninfo?id_token=' . urlencode($idToken);
    $opts = [
        'http' => [
            'method'  => 'GET',
            'timeout' => 10,
            'header'  => 'Accept: application/json',
        ]
    ];
    $context  = stream_context_create($opts);
    $response = @file_get_contents($url, false, $context);

    if ($response !== false) {
        $googleUser = json_decode($response, true);
    }
}

if (!$googleUser && $accessToken) {
    $url = 'https://www.googleapis.com/oauth2/v3/userinfo';
    $opts = [
        'http' => [
            'method'  => 'GET',
            'timeout' => 10,
            'header'  => "Authorization: Bearer " . $accessToken . "\r\nAccept: application/json",
        ]
    ];
    $context  = stream_context_create($opts);
    $response = @file_get_contents($url, false, $context);

    if ($response !== false) {
        $googleUser = json_decode($response, true);
    }
}

if (!$googleUser) {
    sendResponse(['error' => 'Gagal verifikasi token ke server Google. Periksa koneksi internet.'], 500);
}

// Validasi response
if (isset($googleUser['error_description'])) {
    sendResponse(['error' => 'Token Google tidak valid: ' . $googleUser['error_description']], 401);
}

if (empty($googleUser['sub']) && empty($googleUser['email'])) {
    sendResponse(['error' => 'Data akun Google tidak lengkap'], 400);
}

// Ambil data profil dari Google
$googleId   = $googleUser['sub'];
$email      = $googleUser['email'];
$namaGoogle = $googleUser['name'] ?? $googleUser['email'];
$picture    = $googleUser['picture'] ?? null;

// Coba tambahkan kolom google_id & foto_url jika belum ada (untuk database lama)
try { $pdo->exec("ALTER TABLE pelanggan ADD COLUMN `google_id` varchar(50) DEFAULT NULL"); } catch(Exception $e) {}
try { $pdo->exec("ALTER TABLE pelanggan ADD COLUMN `foto_url` varchar(500) DEFAULT NULL"); } catch(Exception $e) {}

// Cek apakah akun sudah ada (berdasarkan email atau google_id)
$stmt = $pdo->prepare('SELECT * FROM pelanggan WHERE kontak = ? OR google_id = ? LIMIT 1');
$stmt->execute([$email, $googleId]);
$user = $stmt->fetch();

if ($user) {
    // Update google_id & foto jika belum tersimpan
    $updateStmt = $pdo->prepare('UPDATE pelanggan SET google_id = ?, foto_url = ? WHERE id = ?');
    $updateStmt->execute([$googleId, $picture, $user['id']]);
    
    startAuthSession();
    session_regenerate_id(true);
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['is_admin'] = false;
    $_SESSION['nama']     = $user['nama'];
    $_SESSION['kontak']   = $user['kontak'];

    sendResponse([
        'success' => true,
        'is_new'  => false,
        'user'    => [
            'id'      => $user['id'],
            'nama'    => $user['nama'],
            'kontak'  => $user['kontak'],
            'foto'    => $picture ?? $user['foto_url'],
        ]
    ]);
} else {
    // Buat akun baru dari data Google
    $stmt = $pdo->prepare(
        'INSERT INTO pelanggan (nama, kontak, password_hash, google_id, foto_url) VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([$namaGoogle, $email, '', $googleId, $picture]);
    $newId = $pdo->lastInsertId();

    startAuthSession();
    session_regenerate_id(true);
    $_SESSION['user_id']  = $newId;
    $_SESSION['is_admin'] = false;
    $_SESSION['nama']     = $namaGoogle;
    $_SESSION['kontak']   = $email;

    sendResponse([
        'success' => true,
        'is_new'  => true,
        'user'    => [
            'id'     => $newId,
            'nama'   => $namaGoogle,
            'kontak' => $email,
            'foto'   => $picture,
        ]
    ]);
}
