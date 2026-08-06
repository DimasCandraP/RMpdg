<?php
/**
 * api/menu.php
 * Endpoint untuk mengelola dan mengambil data menu makanan/minuman.
 *
 * GET    /api/menu.php                -> Ambil semua menu aktif (Publik)
 * GET    /api/menu.php?slug=nasigoreng -> Ambil detail satu menu berdasarkan slug (Publik)
 * POST   /api/menu.php                -> Tambah menu baru (memerlukan Admin)
 * PUT    /api/menu.php?id=5           -> Update menu (memerlukan Admin)
 * DELETE /api/menu.php?id=5           -> Hapus menu (memerlukan Admin)
 */

require 'config.php';
require 'session.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // -----------------------------------------------------------------
    case 'GET':
        if (isset($_GET['slug'])) {
            $stmt = $pdo->prepare('SELECT m.*, k.nama AS kategori_nama FROM menu m JOIN kategori_menu k ON m.kategori_id = k.id WHERE m.slug = ?');
            $stmt->execute([$_GET['slug']]);
            $menu = $stmt->fetch();
            
            if (!$menu) sendResponse(['error' => 'Menu tidak ditemukan', 'found' => false], 200);
            sendResponse($menu);
        } else {
            $kategoriId = $_GET['kategori'] ?? null;
            
            if ($kategoriId) {
                $stmt = $pdo->prepare('SELECT m.*, k.nama AS kategori_nama FROM menu m JOIN kategori_menu k ON m.kategori_id = k.id WHERE m.kategori_id = ? AND m.status = "aktif" ORDER BY m.id ASC');
                $stmt->execute([$kategoriId]);
            } else {
                $stmt = $pdo->query('SELECT m.*, k.nama AS kategori_nama FROM menu m JOIN kategori_menu k ON m.kategori_id = k.id WHERE m.status = "aktif" ORDER BY m.id ASC');
            }
            
            sendResponse($stmt->fetchAll());
        }
        break;

    // -----------------------------------------------------------------
    case 'POST':
        requireAdmin();
        $data = readJsonBody();

        $wajib = ['slug', 'kategori_id', 'nama', 'harga'];
        foreach ($wajib as $field) {
            if (empty($data[$field])) {
                sendResponse(['error' => "Field '$field' wajib diisi"], 400);
            }
        }

        $stmt = $pdo->prepare(
            'INSERT INTO menu (slug, kategori_id, nama, harga, deskripsi, berat, kalori, tingkat_pedas, gambar_utama, status)
             VALUES (:slug, :kategori_id, :nama, :harga, :deskripsi, :berat, :kalori, :tingkat_pedas, :gambar, :status)'
        );
        $stmt->execute([
            ':slug'          => $data['slug'],
            ':kategori_id'   => $data['kategori_id'],
            ':nama'          => $data['nama'],
            ':harga'         => $data['harga'],
            ':deskripsi'     => $data['deskripsi'] ?? null,
            ':berat'         => $data['berat'] ?? null,
            ':kalori'        => $data['kalori'] ?? null,
            ':tingkat_pedas' => $data['tingkat_pedas'] ?? 0,
            ':gambar'        => $data['gambar_utama'] ?? null,
            ':status'        => $data['status'] ?? 'aktif',
        ]);

        sendResponse(['success' => true, 'id' => $pdo->lastInsertId()], 201);
        break;

    // -----------------------------------------------------------------
    case 'PUT':
        requireAdmin();
        if (!isset($_GET['id'])) sendResponse(['error' => 'Parameter id wajib ada'], 400);
        $data = readJsonBody();

        $stmt = $pdo->prepare(
            'UPDATE menu SET kategori_id = ?, nama = ?, harga = ?, deskripsi = ?, status = ? WHERE id = ?'
        );
        $stmt->execute([
            $data['kategori_id'],
            $data['nama'],
            $data['harga'],
            $data['deskripsi'] ?? null,
            $data['status'] ?? 'aktif',
            $_GET['id']
        ]);

        sendResponse(['success' => true]);
        break;

    // -----------------------------------------------------------------
    case 'DELETE':
        requireAdmin();
        if (!isset($_GET['id'])) sendResponse(['error' => 'Parameter id wajib ada'], 400);

        $stmt = $pdo->prepare('DELETE FROM menu WHERE id = ?');
        $stmt->execute([$_GET['id']]);

        sendResponse(['success' => true]);
        break;

    default:
        sendResponse(['error' => 'Method tidak didukung'], 405);
}