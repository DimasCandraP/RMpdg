<?php
require_once __DIR__ . '/../../rmpdg-backend/api/session.php';
startAuthSession();
if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Admin - RM Padang</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/login.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body class="login-body">

  <div class="login-wrapper">

    <!-- Kiri: Branding -->
    <div class="login-left">
      <div class="login-brand">
        <img src="../img/logo.png" alt="Logo RM Padang" />
        <h1>RM PADANG</h1>
        <p>Rumah Makan Padang</p>
      </div>
      <div class="login-tagline">
        <h2>Selamat Datang,<br />Admin!</h2>
        <p>Kelola menu, pesanan catering, reservasi, dan konten website Anda dengan mudah.</p>
      </div>
      <div class="login-features">
        <div class="lf-item"><i class="fa fa-chart-bar"></i> Dashboard Statistik</div>
        <div class="lf-item"><i class="fa fa-utensils"></i> Kelola Menu</div>
        <div class="lf-item"><i class="fa fa-truck"></i> Pesanan Catering</div>
        <div class="lf-item"><i class="fa fa-calendar-check"></i> Reservasi Meja</div>
      </div>
    </div>

    <!-- Kanan: Form Login -->
    <div class="login-right">
      <div class="login-card">
        <div class="login-card-header">
          <i class="fa fa-shield-halved"></i>
          <h3>Login Admin</h3>
          <p>Masukkan kredensial Anda untuk melanjutkan</p>
        </div>

        <div class="form-group">
          <label>Email / Username</label>
          <div class="input-icon">
            <i class="fa fa-envelope"></i>
            <input type="text" id="loginEmail" placeholder="admin@rmpadang.com" />
          </div>
        </div>

        <div class="form-group">
          <label>Password</label>
          <div class="input-icon">
            <i class="fa fa-lock"></i>
            <input type="password" id="loginPassword" placeholder="Masukkan password" />
            <button class="toggle-pw" onclick="togglePassword()" type="button">
              <i class="fa fa-eye" id="eyeIcon"></i>
            </button>
          </div>
        </div>

        <div class="login-remember">
          <label class="checkbox-label">
            <input type="checkbox" /> Ingat saya
          </label>
          <a href="#" class="forgot-pw">Lupa password?</a>
        </div>

        <button class="btn-login-submit" onclick="doLogin()">
          <i class="fa fa-right-to-bracket"></i> Masuk
        </button>

        <div class="login-divider"></div>

        <a href="../index.html" class="btn-back-web">
          <i class="fa fa-arrow-left"></i> Kembali ke Website
        </a>

        <p class="login-error" id="loginError"></p>
      </div>
    </div>

  </div>

  <script>
    const API_BASE = window.location.pathname.includes('/MPTI/') ? '/MPTI/rmpdg-backend/api' : '/rmpdg-backend/api';

    function togglePassword() {
      const pw = document.getElementById('loginPassword');
      const ico = document.getElementById('eyeIcon');
      if (pw.type === 'password') {
        pw.type = 'text';
        ico.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        pw.type = 'password';
        ico.classList.replace('fa-eye-slash', 'fa-eye');
      }
    }

    async function doLogin() {
      const email = document.getElementById('loginEmail').value.trim();
      const password = document.getElementById('loginPassword').value;
      const err = document.getElementById('loginError');

      err.textContent = '';

      if (!email || !password) {
        err.textContent = 'Email dan password wajib diisi.';
        return;
      }

      try {
        const response = await fetch(`${API_BASE}/auth.php`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          credentials: 'same-origin',
          body: JSON.stringify({ email, password })
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
          throw new Error(result.error || 'Login gagal, periksa kembali kredensial Anda.');
        }

        if (result.is_admin) {
          window.location.href = 'dashboard.php';
        } else {
          throw new Error('Akun ini bukan akun Admin.');
        }

      } catch (e) {
        err.textContent = e.message;
      }
    }
  </script>
</body>

</html>
