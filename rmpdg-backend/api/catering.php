<?php
/**
 * api/catering.php
 * Endpoint untuk mengelola data paket catering dan pemesanannya.
 *
 * GET    /api/catering.php              -> Ambil daftar semua paket catering (Publik)
 * POST   /api/catering.php              -> Kirim pesanan catering baru (Publik)
 * GET    /api/catering.php?admin=1      -> Ambil daftar pesanan catering (memerlukan Admin)
 * PUT    /api/catering.php?id=3         -> Update status pesanan catering (memerlukan Admin)
 * DELETE /api/catering.php?id=3         -> Hapus pesanan catering (memerlukan Admin)
 */

require 'config.php';
require 'session.php';

$method = $_SERVER['REQUEST_METHOD'];
$UPLOAD_DIR = __DIR__ . '/../uploads/';

switch ($method) {

    // -----------------------------------------------------------------
    case 'GET':
        if (isset($_GET['admin']) && $_GET['admin'] == '1') {
            requireAdmin();
            $stmt = $pdo->query('SELECT pc.*, pk.nama_paket FROM pesanan_catering pc JOIN paket_catering pk ON pc.paket_id = pk.id ORDER BY pc.waktu_daftar DESC');
            sendResponse($stmt->fetchAll());
        } else if (isset($_GET['kontak'])) {
            $kontak = trim($_GET['kontak']);
            $stmt = $pdo->prepare('SELECT pc.*, pk.nama_paket FROM pesanan_catering pc JOIN paket_catering pk ON pc.paket_id = pk.id WHERE pc.telepon = ? OR pc.nama = ? OR pc.email = ? ORDER BY pc.waktu_daftar DESC');
            $stmt->execute([$kontak, $kontak, $kontak]);
            sendResponse($stmt->fetchAll());
        } else {
            $stmt = $pdo->query('SELECT * FROM paket_catering ORDER BY id ASC');
            sendResponse($stmt->fetchAll());
        }
        break;

    // -----------------------------------------------------------------
    case 'POST':
        $nama        = trim($_POST['nama'] ?? '');
        $telepon     = trim($_POST['telepon'] ?? '');
        $email       = trim($_POST['email'] ?? '');
        $tanggal     = $_POST['tanggal_acara'] ?? '';
        $jenisAcara  = trim($_POST['jenis_acara'] ?? '');
        $jumlahTamu  = (int) ($_POST['jumlah_tamu'] ?? 0);
        $paketId     = (int) ($_POST['paket_id'] ?? 0);
        $catatan     = trim($_POST['catatan'] ?? '');

        if (!$nama || !$telepon || !$tanggal || !$paketId) {
            sendResponse(['error' => 'Data pemenuhan catering belum lengkap'], 400);
        }

        validateUploadedImage($_FILES['fileBukti'] ?? []);
        $ext = strtolower(pathinfo($_FILES['fileBukti']['name'], PATHINFO_EXTENSION));

        if (!is_dir($UPLOAD_DIR)) mkdir($UPLOAD_DIR, 0755, true);
        $namaFile = 'catering_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        
        if (!move_uploaded_file($_FILES['fileBukti']['tmp_name'], $UPLOAD_DIR . $namaFile)) {
            sendResponse(['error' => 'Gagal menyimpan file bukti pembayaran'], 500);
        }

        try {
            $pdo->beginTransaction();
            $tempKode = 'TMP' . bin2hex(random_bytes(3));

            $stmt = $pdo->prepare(
                'INSERT INTO pesanan_catering 
                    (kode_pesanan, nama, telepon, email, tanggal_acara, jenis_acara, jumlah_tamu, paket_id, bukti_bayar, catatan, status)
                VALUES (:kode, :nama, :telepon, :email, :tanggal, :acara, :tamu, :paket, :bukti, :catatan, "pending")'
            );
            $stmt->execute([
                ':kode'    => $tempKode,
                ':nama'    => $nama,
                ':telepon' => $telepon,
                ':email'   => $email ?: null,
                ':tanggal' => $tanggal,
                ':acara'   => $jenisAcara ?: null,
                ':tamu'    => $jumlahTamu ?: null,
                ':paket'   => $paketId,
                ':bukti'   => $namaFile,
                ':catatan' => $catatan ?: '',
            ]);

            $insertId = $pdo->lastInsertId();
            $kode = 'C-' . str_pad($insertId, 3, '0', STR_PAD_LEFT);
            $updateStmt = $pdo->prepare('UPDATE pesanan_catering SET kode_pesanan = ? WHERE id = ?');
            $updateStmt->execute([$kode, $insertId]);
            $pdo->commit();

            syncPelanggan($pdo, $nama, $email ?: $telepon);

            sendResponse([
                'success'      => true,
                'id'           => $insertId,
                'kode_pesanan' => $kode
            ], 201);

        } catch (Exception $e) {
            sendResponse(['error' => 'Gagal menyimpan pesanan catering'], 500);
        }
        break;

    // -----------------------------------------------------------------
    case 'PUT':
        requireAdmin();
        if (!isset($_GET['id'])) sendResponse(['error' => 'Parameter id wajib ada'], 400);

        $data = readJsonBody();
        $statusValid = ['pending', 'dikonfirmasi', 'selesai', 'dibatalkan'];
        if (empty($data['status']) || !in_array($data['status'], $statusValid)) {
            sendResponse(['error' => 'Status tidak valid'], 400);
        }

        $stmt = $pdo->prepare('UPDATE pesanan_catering SET status = ? WHERE id = ?');
        $stmt->execute([$data['status'], $_GET['id']]);

        sendResponse(['success' => true]);
        break;

    // -----------------------------------------------------------------
    case 'DELETE':
        requireAdmin();
        if (!isset($_GET['id'])) sendResponse(['error' => 'Parameter id wajib ada'], 400);

        $stmt = $pdo->prepare('DELETE FROM pesanan_catering WHERE id = ?');
        $stmt->execute([$_GET['id']]);

        sendResponse(['success' => true]);
        break;

    default:
        sendResponse(['error' => 'Method tidak didukung'], 405);
}