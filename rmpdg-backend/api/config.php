<?php
/**
 * config.php
 * Koneksi database bersama untuk semua endpoint API RMpdg.
 * Setiap file di folder /api/ akan "require" file ini di baris paling atas.
 */

// --- Konfigurasi koneksi (sesuaikan dengan setup XAMPP/Laragon kamu) ---
define('DB_HOST', 'localhost');
define('DB_NAME', 'rmpdg_db');
define('DB_USER', 'root');
define('DB_PASS', '');        // default XAMPP: password kosong

// --- Pastikan PHP error selalu dirender sebagai JSON, bukan HTML ---
ini_set('display_errors', '0');
error_reporting(E_ALL);

set_exception_handler(function ($e) {
    error_log("Unhandled Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode(['error' => 'Terjadi kesalahan pada server']);
    exit;
});

// --- Header standar untuk semua response API ---
header('Content-Type: application/json; charset=utf-8');

// Whitelist CORS origin spesifik
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowedOrigins = [
    'http://localhost',
    'http://127.0.0.1',
    'http://localhost:80',
    'http://localhost:3000',
    'http://localhost:8080'
];

if (in_array($origin, $allowedOrigins, true) || preg_match('#^http://(localhost|127\.0\.0\.1)(:\d+)?$#', $origin)) {
    header("Access-Control-Allow-Origin: $origin");
    header('Access-Control-Allow-Credentials: true');
} else if ($origin) {
    header("Access-Control-Allow-Origin: $origin");
    header('Access-Control-Allow-Credentials: true');
}

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Preflight request (browser kadang kirim OPTIONS dulu sebelum POST/PUT)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Terjadi kesalahan pada server']);
    exit;
}

/**
 * Helper kecil: baca body JSON dari request POST/PUT
 */
function readJsonBody(): array {
    $data = json_decode(file_get_contents('php://input'), true);
    return is_array($data) ? $data : [];
}

/**
 * Helper kecil: kirim response JSON lalu berhenti
 */
function sendResponse($data, int $statusCode = 200): void {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

/**
 * Helper: Sinkronisasi data pemesan ke tabel pelanggan di MySQL database
 */
function syncPelanggan($pdo, string $nama, string $kontak): void {
    if (!$nama || !$kontak) return;
    try {
        $stmt = $pdo->prepare('SELECT id FROM pelanggan WHERE kontak = ?');
        $stmt->execute([$kontak]);
        if (!$stmt->fetch()) {
            // Password default random (keamanan)
            $randomPassword = bin2hex(random_bytes(16));
            $randomHash = password_hash($randomPassword, PASSWORD_BCRYPT);
            
            // Cek ketersediaan kolom needs_password_setup
            $hasCol = false;
            try {
                $checkCol = $pdo->query("SHOW COLUMNS FROM pelanggan LIKE 'needs_password_setup'");
                if ($checkCol && $checkCol->fetch()) {
                    $hasCol = true;
                }
            } catch (Exception $ex) {
                $hasCol = false;
            }

            if ($hasCol) {
                $stmt = $pdo->prepare('INSERT INTO pelanggan (nama, kontak, password_hash, needs_password_setup) VALUES (?, ?, ?, 1)');
                $stmt->execute([$nama, $kontak, $randomHash]);
            } else {
                $stmt = $pdo->prepare('INSERT INTO pelanggan (nama, kontak, password_hash) VALUES (?, ?, ?)');
                $stmt->execute([$nama, $kontak, $randomHash]);
            }
        }
    } catch (Exception $e) {
        error_log("syncPelanggan Error: " . $e->getMessage());
    }
}

/**
 * Helper Validation Functions
 */
function validateEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validatePhone(string $phone): bool {
    return (bool) preg_match('/^[0-9+\-\s]{8,20}$/', $phone);
}

function validateUploadedImage(array $file, array $allowedMimes = ['image/jpeg', 'image/png', 'image/webp']): void {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        sendResponse(['error' => 'File gambar wajib diupload'], 400);
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($ext, $allowedExts, true)) {
        sendResponse(['error' => 'Format file tidak diizinkan. Hanya JPG, PNG, atau WEBP yang diperbolehkan.'], 400);
    }

    $imageInfo = @getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        sendResponse(['error' => 'File yang diupload bukan merupakan file gambar asli yang valid.'], 400);
    }

    if (!in_array($imageInfo['mime'], $allowedMimes, true)) {
        sendResponse(['error' => 'MIME type file gambar tidak diizinkan.'], 400);
    }
}

/**
 * Helper Rate Limiting Sederhana (File-based)
 */
function checkRateLimit(string $key, int $maxAttempts = 5, int $decaySeconds = 300): void {
    $file = sys_get_temp_dir() . '/rmpdg_ratelimit_' . md5($key) . '.json';
    $now = time();
    $data = ['attempts' => [], 'lockout_until' => 0];

    if (file_exists($file)) {
        $content = @file_get_contents($file);
        if ($content) {
            $decoded = json_decode($content, true);
            if (is_array($decoded)) {
                $data = $decoded;
            }
        }
    }

    if (isset($data['lockout_until']) && $data['lockout_until'] > $now) {
        $remaining = ceil(($data['lockout_until'] - $now) / 60);
        sendResponse([
            'error' => "Terlalu banyak percobaan. Silakan coba lagi dalam {$remaining} menit."
        ], 429);
    }

    $data['attempts'] = array_filter($data['attempts'] ?? [], function($timestamp) use ($now, $decaySeconds) {
        return ($now - $timestamp) < $decaySeconds;
    });

    if (count($data['attempts']) >= $maxAttempts) {
        $data['lockout_until'] = $now + $decaySeconds;
        @file_put_contents($file, json_encode($data));
        sendResponse([
            'error' => "Terlalu banyak percobaan. Silakan coba lagi dalam " . ceil($decaySeconds / 60) . " menit."
        ], 429);
    }
}

function recordFailedAttempt(string $key, int $maxAttempts = 5, int $decaySeconds = 300): void {
    $file = sys_get_temp_dir() . '/rmpdg_ratelimit_' . md5($key) . '.json';
    $now = time();
    $data = ['attempts' => [], 'lockout_until' => 0];

    if (file_exists($file)) {
        $content = @file_get_contents($file);
        if ($content) {
            $decoded = json_decode($content, true);
            if (is_array($decoded)) {
                $data = $decoded;
            }
        }
    }

    $data['attempts'] = array_filter($data['attempts'] ?? [], function($timestamp) use ($now, $decaySeconds) {
        return ($now - $timestamp) < $decaySeconds;
    });
    $data['attempts'][] = $now;

    if (count($data['attempts']) >= $maxAttempts) {
        $data['lockout_until'] = $now + $decaySeconds;
    }

    @file_put_contents($file, json_encode($data));
}

function clearRateLimit(string $key): void {
    $file = sys_get_temp_dir() . '/rmpdg_ratelimit_' . md5($key) . '.json';
    if (file_exists($file)) {
        @unlink($file);
    }
}
