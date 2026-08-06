<?php
/**
 * api/pesanan_menu.php
 * Endpoint untuk pesanan menu (dine-in / delivery / takeaway) beserta item-itemnya.
 *
 * GET    /api/pesanan_menu.php            -> ambil semua pesanan (memerlukan Admin)
 * GET    /api/pesanan_menu.php?id=5       -> ambil satu pesanan lengkap dengan item
 * POST   /api/pesanan_menu.php            -> buat pesanan baru dari keranjang (dari pembayaran.html)
 * PUT    /api/pesanan_menu.php?id=5       -> update status (memerlukan Admin)
 * DELETE /api/pesanan_menu.php?id=5       -> hapus pesanan (memerlukan Admin)
 */

require 'config.php';
require 'session.php';

$method = $_SERVER['REQUEST_METHOD'];
$UPLOAD_DIR = __DIR__ . '/../uploads/';

switch ($method) {

    // -----------------------------------------------------------------
    case 'GET':
        if (isset($_GET['id'])) {
            $pesanan = ambilSatuPesanan($pdo, $_GET['id']);
            if (!$pesanan) sendResponse(['error' => 'Pesanan tidak ditemukan'], 404);
            sendResponse($pesanan);
        } else if (isset($_GET['kontak'])) {
            $kontak = trim($_GET['kontak']);
            $stmt = $pdo->prepare('SELECT id FROM pesanan_menu WHERE telepon = ? OR nama_pemesan = ? ORDER BY waktu_daftar DESC');
            $stmt->execute([$kontak, $kontak]);
            $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $hasil = [];
            foreach ($ids as $id) {
                $hasil[] = ambilSatuPesanan($pdo, $id);
            }
            sendResponse($hasil);
        } else {
            requireAdmin();
            $stmt = $pdo->query('SELECT id FROM pesanan_menu ORDER BY waktu_daftar DESC');
            $ids  = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $hasil = [];
            foreach ($ids as $id) {
                $hasil[] = ambilSatuPesanan($pdo, $id);
            }
            sendResponse($hasil);
        }
        break;

    // -----------------------------------------------------------------
    case 'POST':
        $nama         = trim($_POST['nama'] ?? '');
        $telp         = trim($_POST['telp'] ?? '');
        $orderType    = $_POST['orderType'] ?? '';
        $lokasi       = trim($_POST['lokasi'] ?? '');
        $metodeBayar  = $_POST['metodeBayar'] ?? '';
        $catatan      = trim($_POST['catatan'] ?? '');
        $itemsJson    = $_POST['items'] ?? '[]';
        $items        = json_decode($itemsJson, true);

        if (!$nama || !$telp || !$orderType || !$lokasi || !$metodeBayar) {
            sendResponse(['error' => 'Data pemesan belum lengkap'], 400);
        }
        if (!is_array($items) || count($items) === 0) {
            sendResponse(['error' => 'Keranjang kosong'], 400);
        }
        validateUploadedImage($_FILES['fileBukti'] ?? []);
        $ext = strtolower(pathinfo($_FILES['fileBukti']['name'], PATHINFO_EXTENSION));

        if (!is_dir($UPLOAD_DIR)) mkdir($UPLOAD_DIR, 0755, true);
        $namaFile     = 'bukti_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $tujuanUpload = $UPLOAD_DIR . $namaFile;

        if (!move_uploaded_file($_FILES['fileBukti']['tmp_name'], $tujuanUpload)) {
            sendResponse(['error' => 'Gagal menyimpan file bukti pembayaran'], 500);
        }

        $subtotal = 0;
        $itemFinal = [];
        foreach ($items as $it) {
            $menuIdParam = $it['id'] ?? '';
            $stmt = $pdo->prepare('SELECT id, harga FROM menu WHERE slug = ? OR id = ?');
            $stmt->execute([$menuIdParam, $menuIdParam]);
            $menuRow = $stmt->fetch();

            if (!$menuRow) {
                $fallbackHarga = (int) ($it['harga'] ?? 25000);
                $namaMenu = $it['nama'] ?? (is_numeric($menuIdParam) ? 'Menu ' . $menuIdParam : ucwords(str_replace('-', ' ', $menuIdParam)));
                $slug = is_numeric($menuIdParam) ? 'menu-' . $menuIdParam : $menuIdParam;
                
                try {
                    $ins = $pdo->prepare('INSERT INTO menu (slug, kategori_id, nama, harga, status) VALUES (?, 1, ?, ?, "aktif")');
                    $ins->execute([$slug, $namaMenu, $fallbackHarga]);
                    $menuId = $pdo->lastInsertId();
                    $hargaSatuan = $fallbackHarga;
                } catch (Exception $ex) {
                    $firstRow = $pdo->query('SELECT id, harga FROM menu LIMIT 1')->fetch();
                    if ($firstRow) {
                        $menuId = $firstRow['id'];
                        $hargaSatuan = (int) $firstRow['harga'];
                    } else {
                        sendResponse(['error' => "Menu '{$menuIdParam}' tidak ditemukan"], 400);
                    }
                }
            } else {
                $menuId = $menuRow['id'];
                $hargaSatuan = (int) $menuRow['harga'];
            }

            $qty          = max(1, (int) ($it['qty'] ?? 1));
            $subtotalItem = $hargaSatuan * $qty;
            $subtotal    += $subtotalItem;

            $itemFinal[] = [
                'menu_id'      => $menuId,
                'qty'          => $qty,
                'harga_satuan' => $hargaSatuan,
                'subtotal'     => $subtotalItem,
            ];
        }
        $pajak = (int) round($subtotal * 0.10);
        $total = $subtotal + $pajak;

        try {
            $pdo->beginTransaction();
            $tempKode = 'TMP' . bin2hex(random_bytes(3));

            $stmt = $pdo->prepare(
                'INSERT INTO pesanan_menu
                    (kode_pesanan, nama_pemesan, telepon, order_type, lokasi, subtotal, pajak, total, metode_bayar, bukti_bayar, catatan, status)
                 VALUES (:kode, :nama, :telp, :orderType, :lokasi, :subtotal, :pajak, :total, :metode, :bukti, :catatan, "pending")'
            );
            $stmt->execute([
                ':kode'      => $tempKode,
                ':nama'      => $nama,
                ':telp'      => $telp,
                ':orderType' => $orderType,
                ':lokasi'    => $lokasi,
                ':subtotal'  => $subtotal,
                ':pajak'     => $pajak,
                ':total'     => $total,
                ':metode'    => $metodeBayar,
                ':bukti'     => $namaFile,
                ':catatan'   => $catatan,
            ]);
            $pesananId = $pdo->lastInsertId();
            $kode = 'M-' . str_pad($pesananId, 3, '0', STR_PAD_LEFT);
            $updateKodeStmt = $pdo->prepare('UPDATE pesanan_menu SET kode_pesanan = ? WHERE id = ?');
            $updateKodeStmt->execute([$kode, $pesananId]);

            $stmtItem = $pdo->prepare(
                'INSERT INTO pesanan_menu_item (pesanan_id, menu_id, qty, harga_satuan, subtotal)
                 VALUES (?, ?, ?, ?, ?)'
            );
            foreach ($itemFinal as $it) {
                $stmtItem->execute([$pesananId, $it['menu_id'], $it['qty'], $it['harga_satuan'], $it['subtotal']]);
            }

            $pdo->commit();
            syncPelanggan($pdo, $nama, $telp);
        } catch (Exception $e) {
            $pdo->rollBack();
            sendResponse(['error' => 'Gagal menyimpan pesanan'], 500);
        }

        sendResponse([
            'success'      => true,
            'id'           => $pesananId,
            'kode_pesanan' => $kode,
            'total'        => $total,
        ], 201);
        break;

    // -----------------------------------------------------------------
    case 'PUT':
        requireAdmin();
        if (!isset($_GET['id'])) sendResponse(['error' => 'Parameter id wajib ada'], 400);

        $data = readJsonBody();
        $statusValid = ['pending', 'diproses', 'selesai', 'dibatalkan'];
        if (empty($data['status']) || !in_array($data['status'], $statusValid)) {
            sendResponse(['error' => 'Status tidak valid'], 400);
        }

        $stmt = $pdo->prepare('UPDATE pesanan_menu SET status = ? WHERE id = ?');
        $stmt->execute([$data['status'], $_GET['id']]);

        sendResponse(['success' => true]);
        break;

    // -----------------------------------------------------------------
    case 'DELETE':
        requireAdmin();
        if (!isset($_GET['id'])) sendResponse(['error' => 'Parameter id wajib ada'], 400);

        $stmt = $pdo->prepare('DELETE FROM pesanan_menu WHERE id = ?');
        $stmt->execute([$_GET['id']]);

        sendResponse(['success' => true]);
        break;

    default:
        sendResponse(['error' => 'Method tidak didukung'], 405);
}

function ambilSatuPesanan(PDO $pdo, $id): ?array {
    $stmt = $pdo->prepare('SELECT * FROM pesanan_menu WHERE id = ?');
    $stmt->execute([$id]);
    $pesanan = $stmt->fetch();
    if (!$pesanan) return null;

    $stmt = $pdo->prepare(
        'SELECT pmi.id, pmi.menu_id, m.nama AS nama_menu, pmi.qty, pmi.harga_satuan, pmi.subtotal
         FROM pesanan_menu_item pmi
         JOIN menu m ON m.id = pmi.menu_id
         WHERE pmi.pesanan_id = ?'
    );
    $stmt->execute([$id]);
    $pesanan['items'] = $stmt->fetchAll();

    return $pesanan;
}
