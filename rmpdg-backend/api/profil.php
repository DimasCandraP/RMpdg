<?php
/**
 * api/profil.php
 * Endpoint untuk mengelola profil pelanggan (GET, Update profil, Ganti kata sandi).
 * Dilindungi middleware requireLogin() untuk mencegah vulnerabilitas IDOR.
 */

require 'config.php';
require 'session.php';

requireLogin();

$userId = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $pdo->prepare('SELECT id, nama, kontak, created_at FROM pelanggan WHERE id = ? LIMIT 1');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if ($user) {
            sendResponse(['success' => true, 'user' => $user]);
        } else {
            sendResponse(['error' => 'Data profil tidak ditemukan'], 404);
        }
        break;

    case 'PUT':
        $data = readJsonBody();
        $action = $data['action'] ?? 'update_profile';

        if ($action === 'update_profile') {
            $nama = trim($data['nama'] ?? '');
            $newKontak = trim($data['new_kontak'] ?? $data['kontak'] ?? '');

            if (!$nama || !$newKontak) {
                sendResponse(['error' => 'Nama lengkap dan Email/No. HP wajib diisi'], 400);
            }

            $stmt = $pdo->prepare('UPDATE pelanggan SET nama = ?, kontak = ? WHERE id = ?');
            $stmt->execute([$nama, $newKontak, $userId]);

            $_SESSION['nama']   = $nama;
            $_SESSION['kontak'] = $newKontak;

            sendResponse([
                'success' => true, 
                'message' => 'Profil berhasil diperbarui!', 
                'user'    => ['id' => $userId, 'nama' => $nama, 'kontak' => $newKontak]
            ]);
        } 
        else if ($action === 'change_password') {
            $oldPw = $data['old_password'] ?? '';
            $newPw = $data['new_password'] ?? '';

            if (!$oldPw || !$newPw) sendResponse(['error' => 'Kata sandi saat ini dan kata sandi baru wajib diisi'], 400);
            if (strlen($newPw) < 6) sendResponse(['error' => 'Kata sandi baru minimal 6 karakter'], 400);

            $stmt = $pdo->prepare('SELECT * FROM pelanggan WHERE id = ? LIMIT 1');
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            if (!$user || empty($user['password_hash']) || !password_verify($oldPw, $user['password_hash'])) {
                sendResponse(['error' => 'Kata sandi saat ini salah/tidak cocok!'], 400);
            }

            $newHash = password_hash($newPw, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('UPDATE pelanggan SET password_hash = ? WHERE id = ?');
            $stmt->execute([$newHash, $userId]);

            sendResponse(['success' => true, 'message' => 'Kata sandi berhasil diubah!']);
        }
        break;

    default:
        sendResponse(['error' => 'Method tidak didukung'], 405);
}
