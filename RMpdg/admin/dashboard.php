<?php require_once __DIR__ . '/_guard.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Admin - RM Padang</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/admin.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body class="admin-body">

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="../img/logo.png" onerror="this.onerror=null; this.src='../img/logo.png';" alt="Logo" />
            <div>
                <span class="brand-name">RM PADANG</span>
                <span class="brand-sub">Admin Panel</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-label">MAIN</div>
            <a href="dashboard.php" class="nav-item active">
                <i class="fa fa-chart-pie"></i> Dashboard
            </a>
            <div class="nav-label">KELOLA</div>
            <a href="menu-admin.php" class="nav-item">
                <i class="fa fa-utensils"></i> Menu Makanan
            </a>
            <a href="kategori.php" class="nav-item">
                <i class="fa fa-tags"></i> Kategori Menu
            </a>
            <a href="catering-admin.php" class="nav-item">
                <i class="fa fa-box-open"></i> Paket Catering
            </a>
            <a href="pesanan.php" class="nav-item">
                <i class="fa fa-clipboard-list"></i> Pesanan Catering
            </a>
            <a href="pesanan-menu.php" class="nav-item">
                <i class="fa fa-shopping-cart"></i> Pesanan Menu
            </a>
            <a href="reservasi-admin.php" class="nav-item">
                <i class="fa fa-calendar-check"></i> Reservasi
            </a>
            <a href="promosi-admin.php" class="nav-item">
                <i class="fa fa-percent"></i> Promosi
            </a>
            <a href="kontak-admin.php" class="nav-item">
                <i class="fa fa-envelope"></i> Pesan Masuk
                <span class="badge-notif">0</span>
            </a>
            <div class="nav-label">AKUN</div>
            <a href="#" class="nav-item" onclick="doLogout(); return false;">
                <i class="fa fa-right-from-bracket"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="admin-main">

        <!-- TOP BAR -->
        <header class="admin-topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fa fa-bars"></i>
                </button>
                <div class="topbar-title">
                    <h2>Dashboard</h2>
                    <span>Selamat datang kembali, Admin!</span>
                </div>
            </div>
            <div class="topbar-right">
                <a href="../index.html" target="_blank" class="btn-view-web">
                    <i class="fa fa-eye"></i> Lihat Website
                </a>
                <div class="admin-avatar">
                    <i class="fa fa-user-circle"></i>
                    <span>Admin</span>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <div class="admin-content">

            <!-- STAT CARDS -->
            <div class="stat-cards">
                <div class="stat-card red">
                    <div class="sc-icon"><i class="fa fa-clipboard-list"></i></div>
                    <div class="sc-info">
                        <span class="sc-num" id="statPesananNum">0</span>
                        <span class="sc-label">Pesanan Masuk</span>
                        <span class="sc-sub" id="statPesananSub">Catering & Menu</span>
                    </div>
                </div>
                <div class="stat-card gold">
                    <div class="sc-icon"><i class="fa fa-calendar-check"></i></div>
                    <div class="sc-info">
                        <span class="sc-num" id="statReservasiNum">0</span>
                        <span class="sc-label">Reservasi</span>
                        <span class="sc-sub" id="statReservasiSub">Meja Resto</span>
                    </div>
                </div>
                <div class="stat-card green">
                    <div class="sc-icon"><i class="fa fa-utensils"></i></div>
                    <div class="sc-info">
                        <span class="sc-num" id="statMenuNum">0</span>
                        <span class="sc-label">Total Menu</span>
                        <span class="sc-sub" id="statMenuSub">Tersedia</span>
                    </div>
                </div>
                <div class="stat-card blue">
                    <div class="sc-icon"><i class="fa fa-envelope"></i></div>
                    <div class="sc-info">
                        <span class="sc-num" id="statPesanNum">0</span>
                        <span class="sc-label">Pesan Masuk</span>
                        <span class="sc-sub" id="statPesanSub">Pengunjung</span>
                    </div>
                </div>
            </div>

            <!-- BARIS TENGAH -->
            <div class="dashboard-mid">

                <!-- Pesanan Terbaru -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h3>Pesanan Catering Terbaru</h3>
                        <a href="pesanan.php">Lihat Semua</a>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Paket</th>
                                <th>Tanggal Acara</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="tblCateringBody">
                            <tr><td colspan="5" style="text-align:center; padding:15px; color:#888;">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- Reservasi Terbaru -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h3>Reservasi Terbaru</h3>
                        <a href="reservasi-admin.php">Lihat Semua</a>
                    </div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>Tamu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="tblReservasiBody">
                            <tr><td colspan="5" style="text-align:center; padding:15px; color:#888;">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- BARIS BAWAH -->
            <div class="dashboard-bot">

                <!-- Aksi Cepat -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h3>Aksi Cepat</h3>
                    </div>
                    <div class="quick-actions">
                        <a href="menu-admin.php" class="qa-btn">
                            <i class="fa fa-plus"></i>
                            <span>Tambah Menu</span>
                        </a>
                        <a href="catering-admin.php" class="qa-btn">
                            <i class="fa fa-box-open"></i>
                            <span>Kelola Paket</span>
                        </a>
                        <a href="promosi-admin.php" class="qa-btn">
                            <i class="fa fa-percent"></i>
                            <span>Buat Promo</span>
                        </a>
                        <a href="kontak-admin.php" class="qa-btn">
                            <i class="fa fa-envelope-open"></i>
                            <span>Baca Pesan</span>
                        </a>
                    </div>
                </div>

                <!-- Status Pesanan Chart -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h3>Ringkasan Status Sistem</h3>
                    </div>
                    <div class="status-summary" style="padding:15px 0;">
                        <p style="font-size:0.9rem; color:var(--text-gray); line-height:1.6;">
                            Sistem terhubung penuh ke database MariaDB/MySQL (`rmpdg_db`). Seluruh pesanan menu, catering, reservasi, dan kontak tersimpan secara real-time.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="../js/main.js"></script>
    <script>
        const API_BASE = window.API_BASE || (window.location.pathname.includes('/MPTI/') ? '/MPTI/rmpdg-backend/api' : '/rmpdg-backend/api');

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }

        async function doLogout() {
            if (!confirm('Yakin ingin logout?')) return;
            try {
                await fetch(`${API_BASE}/logout.php`, { credentials: 'same-origin' });
            } catch(e){}
            window.location.href = 'login.php';
        }

        document.addEventListener('DOMContentLoaded', async () => {
            loadDashboardStats();
        });

        async function loadDashboardStats() {
            try {
                const resMenu = await fetch(`${API_BASE}/pesanan_menu.php`, { credentials: 'same-origin' });
                const menuOrders = resMenu.ok ? await resMenu.json() : [];
                
                const resCat = await fetch(`${API_BASE}/catering.php?admin=1`, { credentials: 'same-origin' });
                const catOrders = resCat.ok ? await resCat.json() : [];

                const totalPesanan = (Array.isArray(menuOrders) ? menuOrders.length : 0) + (Array.isArray(catOrders) ? catOrders.length : 0);
                document.getElementById('statPesananNum').textContent = totalPesanan;

                const cBody = document.getElementById('tblCateringBody');
                if (Array.isArray(catOrders) && catOrders.length > 0) {
                    cBody.innerHTML = catOrders.slice(0, 4).map(row => {
                        let badgeCls = 'pending';
                        if (row.status === 'dikonfirmasi') badgeCls = 'confirmed';
                        else if (row.status === 'selesai') badgeCls = 'done';
                        else if (row.status === 'dibatalkan') badgeCls = 'cancel';

                        return `
                            <tr>
                                <td>${row.kode_pesanan || '#' + row.id}</td>
                                <td>${row.nama}</td>
                                <td>${row.nama_paket || 'Paket'}</td>
                                <td>${row.tanggal_acara}</td>
                                <td><span class="badge ${badgeCls}">${row.status}</span></td>
                            </tr>
                        `;
                    }).join('');
                } else {
                    cBody.innerHTML = `<tr><td colspan="5" style="text-align:center; padding:15px; color:#888;">Belum ada pesanan catering.</td></tr>`;
                }
            } catch (e) {
                console.error(e);
            }

            try {
                const resRes = await fetch(`${API_BASE}/reservasi.php`, { credentials: 'same-origin' });
                const reservasiList = resRes.ok ? await resRes.json() : [];
                document.getElementById('statReservasiNum').textContent = Array.isArray(reservasiList) ? reservasiList.length : 0;

                const rBody = document.getElementById('tblReservasiBody');
                if (Array.isArray(reservasiList) && reservasiList.length > 0) {
                    rBody.innerHTML = reservasiList.slice(0, 4).map(row => {
                        let badgeCls = 'pending';
                        if (row.status === 'dikonfirmasi') badgeCls = 'confirmed';
                        else if (row.status === 'selesai') badgeCls = 'done';
                        else if (row.status === 'dibatalkan') badgeCls = 'cancel';

                        return `
                            <tr>
                                <td>${row.kode_reservasi || '#' + row.id}</td>
                                <td>${row.nama}</td>
                                <td>${row.tanggal}</td>
                                <td>${row.jumlah_tamu} orang</td>
                                <td><span class="badge ${badgeCls}">${row.status}</span></td>
                            </tr>
                        `;
                    }).join('');
                } else {
                    rBody.innerHTML = `<tr><td colspan="5" style="text-align:center; padding:15px; color:#888;">Belum ada reservasi.</td></tr>`;
                }
            } catch (e) {
                console.error(e);
            }

            try {
                const resMenu = await fetch(`${API_BASE}/menu.php?all=1`, { credentials: 'same-origin' });
                const menus = resMenu.ok ? await resMenu.json() : [];
                document.getElementById('statMenuNum').textContent = Array.isArray(menus) ? menus.length : 0;
            } catch (e) {
                console.error(e);
            }

            try {
                const resMsg = await fetch(`${API_BASE}/kontak.php`, { credentials: 'same-origin' });
                const msgs = resMsg.ok ? await resMsg.json() : [];
                const unread = Array.isArray(msgs) ? msgs.filter(m => m.dibaca == 0).length : 0;
                document.getElementById('statPesanNum').textContent = Array.isArray(msgs) ? msgs.length : 0;
                document.getElementById('statPesanSub').textContent = `${unread} belum dibaca`;

                const badgeEl = document.querySelector('.sidebar-nav .badge-notif');
                if (badgeEl) {
                    if (unread > 0) {
                        badgeEl.textContent = unread;
                        badgeEl.style.display = 'inline-block';
                    } else {
                        badgeEl.style.display = 'none';
                    }
                }
            } catch (e) {
                console.error(e);
            }
        }
    </script>
</body>

</html>
