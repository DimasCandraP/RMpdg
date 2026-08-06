<?php
/**
 * api/logout.php
 * Endpoint untuk menghapus session server-side & logout.
 */

require 'config.php';
require 'session.php';

startAuthSession();

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

sendResponse([
    'success' => true,
    'message' => 'Logout berhasil'
]);
