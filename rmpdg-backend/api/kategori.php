<?php
/**
 * api/kategori.php
 * Endpoint untuk mengelola kategori menu (Makanan Utama, Lauk, Sayur, Minuman, dll).
 * 
 * GET    /api/kategori.php   -> Ambil semua kategori beserta jumlah menu (Publik)
 * POST   /api/kategori.php   -> Tambah kategori baru (memerlukan Admin)
 * DELETE /api/kategori.php?id=1 -> Hapus kategori (memerlukan Admin)
 */

require 'config.php';
require 'session.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $pdo->query('
            SELECT k.*, COUNT(m.id) AS total_menu 
            FROM kategori_menu k 
            LEFT JOIN menu m ON k.id = m.kategori_id 
            GROUP BY k.id 
            ORDER BY k.id ASC
        ');
        sendResponse($stmt->fetchAll());
        break;

    case 'POST':
        requireAdmin();
        $data = readJsonBody();
        $nama = trim($data['nama'] ?? '');
        if (!$nama) {
            sendResponse(['error' => 'Nama kategori wajib diisi'], 400);
        }

        $stmt = $pdo->prepare('INSERT INTO kategori_menu (nama) VALUES (?)');
        $stmt->execute([$nama]);
        sendResponse(['success' => true, 'id' => $pdo->lastInsertId()], 201);
        break;

    case 'DELETE':
        requireAdmin();
        if (!isset($_GET['id'])) {
            sendResponse(['error' => 'Parameter id wajib ada'], 400);
        }

        $id = (int)$_GET['id'];
        $stmt = $pdo->prepare('DELETE FROM kategori_menu WHERE id = ?');
        $stmt->execute([$id]);
        sendResponse(['success' => true]);
        break;

    default:
        sendResponse(['error' => 'Method tidak didukung'], 405);
}
