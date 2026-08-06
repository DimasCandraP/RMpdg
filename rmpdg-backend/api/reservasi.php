<?php
/**
 * api/reservasi.php
 * Endpoint untuk fitur reservasi meja.
 *
 * GET    /api/reservasi.php            -> ambil semua reservasi (memerlukan Admin)
 * GET    /api/reservasi.php?id=3       -> ambil satu reservasi
 * POST   /api/reservasi.php            -> tambah reservasi baru (dari reservasi.html)
 * PUT    /api/reservasi.php?id=3       -> update status (memerlukan Admin)
 * DELETE /api/reservasi.php?id=3       -> hapus reservasi (memerlukan Admin)
 */

require 'config.php';
require 'session.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

    // -----------------------------------------------------------------
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare('SELECT * FROM reservasi WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $row = $stmt->fetch();
            if (!$row) sendResponse(['error' => 'Reservasi tidak ditemukan'], 404);
            sendResponse($row);
        } else if (isset($_GET['kontak'])) {
            $kontak = trim($_GET['kontak']);
            $stmt = $pdo->prepare('SELECT * FROM reservasi WHERE telepon = ? OR nama = ? OR email = ? ORDER BY waktu_daftar DESC');
            $stmt->execute([$kontak, $kontak, $kontak]);
            sendResponse($stmt->fetchAll());
        } else {
            requireAdmin();
            $stmt = $pdo->query('SELECT * FROM reservasi ORDER BY waktu_daftar DESC');
            sendResponse($stmt->fetchAll());
        }
        break;

    // -----------------------------------------------------------------
    case 'POST':
        $data = readJsonBody();

        $wajib = ['nama', 'telepon', 'tanggal', 'jam', 'jumlah_tamu'];
        foreach ($wajib as $field) {
            if (empty($data[$field])) {
                sendResponse(['error' => "Field '$field' wajib diisi"], 400);
            }
        }

        $pdo->beginTransaction();
        $tempKode = 'TMP' . bin2hex(random_bytes(3));

        // Normalisasi jam (misal: '21.00 WIB' -> '21:00:00')
        $jamFormatted = trim($data['jam']);
        $jamFormatted = str_replace('.', ':', $jamFormatted);
        $jamFormatted = preg_replace('/[^\d:]/', '', $jamFormatted);
        if (strlen($jamFormatted) === 5) {
            $jamFormatted .= ':00';
        }

        $stmt = $pdo->prepare(
            'INSERT INTO reservasi (kode_reservasi, nama, telepon, email, tanggal, jam, jumlah_tamu, jenis_acara, catatan, status)
             VALUES (:kode, :nama, :telepon, :email, :tanggal, :jam, :jumlah_tamu, :jenis_acara, :catatan, "pending")'
        );
        $stmt->execute([
            ':kode'        => $tempKode,
            ':nama'        => $data['nama'],
            ':telepon'     => $data['telepon'],
            ':email'       => $data['email'] ?? null,
            ':tanggal'     => $data['tanggal'],
            ':jam'         => $jamFormatted ?: '12:00:00',
            ':jumlah_tamu' => $data['jumlah_tamu'],
            ':jenis_acara' => $data['jenis_acara'] ?? 'Makan Biasa',
            ':catatan'     => $data['catatan'] ?? '',
        ]);

        $insertId = $pdo->lastInsertId();
        $kode = str_pad($insertId, 3, '0', STR_PAD_LEFT);
        $updateStmt = $pdo->prepare('UPDATE reservasi SET kode_reservasi = ? WHERE id = ?');
        $updateStmt->execute([$kode, $insertId]);
        $pdo->commit();

        syncPelanggan($pdo, $data['nama'], $data['email'] ?? $data['telepon']);

        sendResponse(['success' => true, 'id' => $insertId, 'kode_reservasi' => $kode], 201);
        break;

    // -----------------------------------------------------------------
    case 'PUT':
        requireAdmin();
        if (!isset($_GET['id'])) sendResponse(['error' => 'Parameter id wajib ada'], 400);

        $data = readJsonBody();
        if (empty($data['status'])) sendResponse(['error' => "Field 'status' wajib diisi"], 400);

        $statusValid = ['pending', 'dikonfirmasi', 'selesai', 'dibatalkan'];
        if (!in_array($data['status'], $statusValid)) {
            sendResponse(['error' => 'Status tidak valid'], 400);
        }

        $stmt = $pdo->prepare('UPDATE reservasi SET status = ? WHERE id = ?');
        $stmt->execute([$data['status'], $_GET['id']]);

        sendResponse(['success' => true]);
        break;

    // -----------------------------------------------------------------
    case 'DELETE':
        requireAdmin();
        if (!isset($_GET['id'])) sendResponse(['error' => 'Parameter id wajib ada'], 400);

        $stmt = $pdo->prepare('DELETE FROM reservasi WHERE id = ?');
        $stmt->execute([$_GET['id']]);

        sendResponse(['success' => true]);
        break;

    // -----------------------------------------------------------------
    default:
        sendResponse(['error' => 'Method tidak didukung'], 405);
}
