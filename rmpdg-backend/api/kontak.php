<?php
/**
 * api/kontak.php
 * Endpoint untuk menangani pesan kontak dari form publik dan panel admin.
 *
 * GET    /api/kontak.php          -> Ambil semua daftar pesan masuk (memerlukan Admin)
 * POST   /api/kontak.php          -> Kirim pesan baru dari halaman kontak (Publik)
 * PUT    /api/kontak.php?id=1     -> Tandai pesan sudah dibaca (memerlukan Admin)
 * DELETE /api/kontak.php?id=1     -> Hapus pesan kontak (memerlukan Admin)
 */

require 'config.php';
require 'session.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // -----------------------------------------------------------------
    case 'GET':
        requireAdmin();
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare('SELECT * FROM pesan_kontak WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $pesan = $stmt->fetch();
            if (!$pesan) sendResponse(['error' => 'Pesan tidak ditemukan'], 404);
            sendResponse($pesan);
        } else {
            $stmt = $pdo->query('SELECT * FROM pesan_kontak ORDER BY waktu DESC');
            sendResponse($stmt->fetchAll());
        }
        break;

    // -----------------------------------------------------------------
    case 'POST':
        $data = readJsonBody();

        $nama   = trim($data['nama_lengkap'] ?? '');
        $kontak = trim($data['kontak_whatsapp'] ?? '');
        $subjek = trim($data['subjek'] ?? '');
        $isi    = trim($data['isi_pesan'] ?? '');

        if (!$nama || !$kontak || !$isi) {
            sendResponse(['error' => 'Nama, kontak WhatsApp, dan isi pesan wajib diisi'], 400);
        }

        try {
            $stmt = $pdo->prepare(
                'INSERT INTO pesan_kontak (nama_lengkap, kontak_whatsapp, subjek, isi_pesan, dibaca)
                 VALUES (:nama, :kontak, :subjek, :isi, 0)'
            );
            $stmt->execute([
                ':nama'   => $nama,
                ':kontak' => $kontak,
                ':subjek' => $subjek ?: 'Tanpa Subjek',
                ':isi'    => $isi,
            ]);

            sendResponse([
                'success' => true,
                'message' => 'Pesan berhasil dikirim',
                'id'      => $pdo->lastInsertId()
            ], 201);

        } catch (Exception $e) {
            sendResponse(['error' => 'Gagal mengirim pesan'], 500);
        }
        break;

    // -----------------------------------------------------------------
    case 'PUT':
        requireAdmin();
        $data = readJsonBody();
        $dibaca = isset($data['dibaca']) ? (int)$data['dibaca'] : 1;

        if (isset($_GET['all']) && $_GET['all'] == '1') {
            $stmt = $pdo->prepare('UPDATE pesan_kontak SET dibaca = ?');
            $stmt->execute([$dibaca]);
            sendResponse(['success' => true, 'message' => 'Semua pesan ditandai sebagai sudah dibaca']);
        } else {
            if (!isset($_GET['id'])) sendResponse(['error' => 'Parameter id atau all wajib ada'], 400);
            $stmt = $pdo->prepare('UPDATE pesan_kontak SET dibaca = ? WHERE id = ?');
            $stmt->execute([$dibaca, $_GET['id']]);
            sendResponse(['success' => true, 'message' => 'Status pesan diperbarui']);
        }
        break;

    // -----------------------------------------------------------------
    case 'DELETE':
        requireAdmin();
        if (!isset($_GET['id'])) sendResponse(['error' => 'Parameter id wajib ada'], 400);

        $stmt = $pdo->prepare('DELETE FROM pesan_kontak WHERE id = ?');
        $stmt->execute([$_GET['id']]);

        sendResponse(['success' => true, 'message' => 'Pesan berhasil dihapus']);
        break;

    default:
        sendResponse(['error' => 'Method tidak didukung'], 405);
}