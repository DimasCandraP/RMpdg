<?php
/**
 * api/promosi.php
 * Endpoint untuk mengelola data promosi dan diskon restoran.
 *
 * GET    /api/promosi.php           -> Ambil semua promosi yang aktif (untuk publik)
 * GET    /api/promosi.php?all=1     -> Ambil semua promosi (memerlukan Admin)
 * POST   /api/promosi.php           -> Tambah promo baru (memerlukan Admin)
 * PUT    /api/promosi.php?id=1      -> Update data/status promo (memerlukan Admin)
 * DELETE /api/promosi.php?id=1      -> Hapus promo (memerlukan Admin)
 */

require 'config.php';
require 'session.php';

$method = $_SERVER['REQUEST_METHOD'];
$UPLOAD_DIR = __DIR__ . '/../uploads/';

switch ($method) {

    // -----------------------------------------------------------------
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $pdo->prepare('SELECT * FROM promosi WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $promo = $stmt->fetch();
            if (!$promo) sendResponse(['error' => 'Promosi tidak ditemukan', 'found' => false], 200);
            sendResponse($promo);
        } else {
            if (isset($_GET['all']) && $_GET['all'] == '1') {
                requireAdmin();
                $stmt = $pdo->query('SELECT * FROM promosi ORDER BY created_at DESC');
            } else {
                $stmt = $pdo->query('SELECT * FROM promosi WHERE status = "aktif" ORDER BY created_at DESC');
            }
            sendResponse($stmt->fetchAll());
        }
        break;

    // -----------------------------------------------------------------
    case 'POST':
        requireAdmin();
        $judul       = trim($_POST['judul'] ?? '');
        $subJudul    = trim($_POST['sub_judul'] ?? '');
        $deskripsi   = trim($_POST['deskripsi'] ?? '');
        $diskon      = (int)($_POST['diskon_persen'] ?? 0);
        $warnaTema   = $_POST['warna_tema'] ?? 'red';
        $tglMulai    = $_POST['tanggal_mulai'] ?? '';
        $tglAkhir    = $_POST['tanggal_akhir'] ?? '';
        $status      = $_POST['status'] ?? 'aktif';

        if (!$judul || !$tglMulai || !$tglAkhir) {
            sendResponse(['error' => 'Judul, tanggal mulai, dan tanggal akhir wajib diisi'], 400);
        }

        $namaFile = null;
        if (isset($_FILES['gambar']) && !empty($_FILES['gambar']['tmp_name'])) {
            validateUploadedImage($_FILES['gambar']);
            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));

            if (!is_dir($UPLOAD_DIR)) mkdir($UPLOAD_DIR, 0755, true);
            $namaFile = 'promo_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            
            if (!@move_uploaded_file($_FILES['gambar']['tmp_name'], $UPLOAD_DIR . $namaFile)) {
                if (!@copy($_FILES['gambar']['tmp_name'], $UPLOAD_DIR . $namaFile)) {
                    sendResponse(['error' => 'Gagal mengunggah gambar promosi'], 500);
                }
            }
        }

        try {
            $stmt = $pdo->prepare(
                'INSERT INTO promosi (judul, sub_judul, deskripsi, diskon_persen, warna_tema, tanggal_mulai, tanggal_akhir, gambar, status)
                 VALUES (:judul, :sub, :desk, :diskon, :warna, :mulai, :akhir, :gambar, :status)'
            );
            $stmt->execute([
                ':judul'  => $judul,
                ':sub'    => $subJudul ?: null,
                ':desk'   => $deskripsi ?: null,
                ':diskon' => $diskon ?: null,
                ':warna'  => $warnaTema,
                ':mulai'  => $tglMulai,
                ':akhir'  => $tglAkhir,
                ':gambar' => $namaFile,
                ':status' => $status,
            ]);

            sendResponse(['success' => true, 'message' => 'Promosi berhasil ditambahkan', 'id' => $pdo->lastInsertId()], 201);

        } catch (Exception $e) {
            sendResponse(['error' => 'Gagal menambah promosi'], 500);
        }
        break;

    // -----------------------------------------------------------------
    case 'PUT':
        requireAdmin();
        if (!isset($_GET['id'])) sendResponse(['error' => 'Parameter id wajib ada'], 400);

        $data = readJsonBody();
        $stmt = $pdo->prepare(
            'UPDATE promosi SET judul = ?, sub_judul = ?, deskripsi = ?, diskon_persen = ?, warna_tema = ?, tanggal_mulai = ?, tanggal_akhir = ?, status = ? WHERE id = ?'
        );
        $stmt->execute([
            $data['judul'],
            $data['sub_judul'] ?? null,
            $data['deskripsi'] ?? null,
            $data['diskon_persen'] ?? null,
            $data['warna_tema'] ?? 'red',
            $data['tanggal_mulai'],
            $data['tanggal_akhir'],
            $data['status'] ?? 'aktif',
            $_GET['id']
        ]);

        sendResponse(['success' => true, 'message' => 'Promosi berhasil diperbarui']);
        break;

    // -----------------------------------------------------------------
    case 'DELETE':
        requireAdmin();
        if (!isset($_GET['id'])) sendResponse(['error' => 'Parameter id wajib ada'], 400);

        $stmt = $pdo->prepare('DELETE FROM promosi WHERE id = ?');
        $stmt->execute([$_GET['id']]);

        sendResponse(['success' => true, 'message' => 'Promosi berhasil dihapus']);
        break;

    default:
        sendResponse(['error' => 'Method tidak didukung'], 405);
}