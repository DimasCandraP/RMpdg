<?php
/**
 * api/me.php
 * Endpoint untuk mendapatkan data pengguna yang sedang login berdasarkan Session.
 */

require 'config.php';
require 'session.php';

startAuthSession();

if (empty($_SESSION['user_id'])) {
    sendResponse(['error' => 'Belum terautentikasi'], 401);
}

$isAdmin = $_SESSION['is_admin'] ?? false;

if ($isAdmin) {
    sendResponse([
        'success'    => true,
        'is_admin'   => true,
        'csrf_token' => getCsrfToken(),
        'user'       => [
            'id'    => $_SESSION['user_id'],
            'nama'  => $_SESSION['nama'] ?? 'Admin',
            'email' => $_SESSION['email'] ?? '',
            'role'  => $_SESSION['role'] ?? 'staff',
        ]
    ]);
} else {
    sendResponse([
        'success'    => true,
        'is_admin'   => false,
        'csrf_token' => getCsrfToken(),
        'user'       => [
            'id'     => $_SESSION['user_id'],
            'nama'   => $_SESSION['nama'] ?? '',
            'kontak' => $_SESSION['kontak'] ?? '',
        ]
    ]);
}
