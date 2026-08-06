<?php
/**
 * api/session.php
 * Middleware session server-side & helper autentikasi.
 */

if (!function_exists('startAuthSession')) {
    function startAuthSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
            session_set_cookie_params([
                'lifetime' => 86400,
                'path'     => '/',
                'domain'   => '',
                'secure'   => $secure,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }
}

if (!function_exists('requireLogin')) {
    function requireLogin(): void {
        startAuthSession();
        if (empty($_SESSION['user_id'])) {
            sendResponse(['error' => 'Akses ditolak. Silakan login terlebih dahulu.'], 401);
        }
    }
}

if (!function_exists('requireAdmin')) {
    function requireAdmin(): void {
        startAuthSession();
        if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            sendResponse(['error' => 'Akses ditolak. Memerlukan hak akses admin.'], 401);
        }
    }
}

if (!function_exists('getCsrfToken')) {
    function getCsrfToken(): string {
        startAuthSession();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('verifyCsrfToken')) {
    function verifyCsrfToken(): void {
        startAuthSession();
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['csrf_token'] ?? '';
        if (!$token) {
            $data = readJsonBody();
            if (isset($data['csrf_token'])) {
                $token = $data['csrf_token'];
            }
        }

        if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], (string)$token)) {
            sendResponse(['error' => 'Validasi CSRF token gagal. Silakan muat ulang halaman.'], 403);
        }
    }
}
