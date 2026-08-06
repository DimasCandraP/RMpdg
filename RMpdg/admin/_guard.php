<?php
/**
 * RMpdg/admin/_guard.php
 * Server-side security guard untuk halaman admin.
 * Memeriksa status session admin sebelum merender halaman HTML.
 */

require_once __DIR__ . '/../../rmpdg-backend/api/session.php';

startAuthSession();

if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.php');
    exit;
}
