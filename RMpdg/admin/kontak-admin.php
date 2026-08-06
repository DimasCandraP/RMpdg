<?php require_once __DIR__ . '/_guard.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pesan Masuk - Admin RM Padang</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/admin.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body class="admin-body">

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="../img/logo.png" alt="Logo" />
            <div>
                <span class="brand-name">RM PADANG</span>
                <span class="brand-sub">Admin Panel</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-label">MAIN</div>
            <a href="dashboard.php" class="nav-item"><i class="fa fa-chart-pie"></i> Dashboard</a>
            <div class="nav-label">KELOLA</div>
            <a href="menu-admin.php" class="nav-item"><i class="fa fa-utensils"></i> Menu Makanan</a>
            <a href="kategori.php" class="nav-item"><i class="fa fa-tags"></i> Kategori Menu</a>
            <a href="catering-admin.php" class="nav-item"><i class="fa fa-box-open"></i> Paket Catering</a>
            <a href="pesanan.php" class="nav-item"><i class="fa fa-clipboard-list"></i> Pesanan Catering</a>
            <a href="pesanan-menu.php" class="nav-item"><i class="fa fa-shopping-cart"></i> Pesanan Menu</a>
            <a href="reservasi-admin.php" class="nav-item"><i class="fa fa-calendar-check"></i> Reservasi</a>
            <a href="promosi-admin.php" class="nav-item"><i class="fa fa-percent"></i> Promosi</a>
            <a href="kontak-admin.php" class="nav-item active">
                <i class="fa fa-envelope"></i> Pesan Masuk
                <span class="badge-notif">0</span>
            </a>
            <div class="nav-label">AKUN</div>
            <a href="#" class="nav-item" onclick="doLogout(); return false;"><i class="fa fa-right-from-bracket"></i> Logout</a>
        </nav>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
                <div class="topbar-title">
                    <h2>Pesan Masuk</h2>
                    <span>Pesan dari pengunjung website</span>
                </div>
            </div>
            <div class="topbar-right">
                <button class="btn-add" style="background:#27AE60;" onclick="tandaiSemuaDibaca()">
                    <i class="fa fa-check-double"></i> Tandai Semua Dibaca
                </button>
            </div>
        </header>

        <div class="admin-content">

            <div class="table-toolbar">
                <div class="toolbar-left">
                    <select id="filterBaca" onchange="filterPesan()">
                        <option value="">Semua</option>
                        <option value="0">Belum Dibaca</option>
                        <option value="1">Sudah Dibaca</option>
                    </select>
                    <select id="filterSubjek" onchange="filterPesan()">
                        <option value="">Semua Subjek</option>
                        <option>Pertanyaan Menu</option>
                        <option>Pemesanan Catering</option>
                        <option>Reservasi Meja</option>
                        <option>Kritik & Saran</option>
                        <option>Lainnya</option>
                    </select>
                </div>
                <div class="toolbar-right">
                    <div class="search-box">
                        <i class="fa fa-search"></i>
                        <input type="text" id="searchPesan" placeholder="Cari nama / email..."
                            oninput="filterPesan()" />
                    </div>
                </div>
            </div>

            <div class="dash-card">
                <div class="pesan-list" id="pesanList">
                </div>
            </div>

            <div class="modal-overlay" id="pesanModal" onclick="closePesanModal()">
                <div class="modal-box modal-lg" onclick="event.stopPropagation()">
                    <div class="modal-header">
                        <h3 id="pesanModalTitle">Detail Pesan</h3>
                        <button onclick="closePesanModal()"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="pesan-detail" id="pesanDetail"></div>
                        <div class="modal-footer">
                            <button class="btn-cancel" onclick="closePesanModal()">Tutup</button>
                            <a class="btn-save" id="btnBalas" href="#">
                                <i class="fa fa-reply"></i> Balas via Email
                            </a>
                            <button class="btn-save" style="background:#c0392b;" onclick="hapusPesan()">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="../js/main.js"></script>
    <script>
        const API_BASE = window.API_BASE || (window.location.pathname.includes('/MPTI/') ? '/MPTI/rmpdg-backend/api' : '/rmpdg-backend/api');

        let pesanList = [];
        let activeId = null;

        async function doLogout() {
            if (!confirm('Yakin ingin logout?')) return;
            try {
                await fetch(`${API_BASE}/logout.php`, { credentials: 'same-origin' });
            } catch(e){}
            window.location.href = 'login.php';
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadPesan();
        });

        async function loadPesan() {
            try {
                const response = await fetch(`${API_BASE}/kontak.php`, { credentials: 'same-origin' });
                if (!response.ok) throw new Error('Gagal memuat pesan');
                pesanList = await response.json();
                renderPesanList();
            } catch (err) {
                console.error(err);
                document.getElementById('pesanList').innerHTML = `<p style="text-align:center; padding:20px; color:#c0392b;">Gagal memuat pesan: ${err.message}</p>`;
            }
        }

        function renderPesanList() {
            const container = document.getElementById('pesanList');

            const unreadCount = Array.isArray(pesanList) ? pesanList.filter(x => x.dibaca == 0).length : 0;
            const badgeEl = document.querySelector('.sidebar-nav .badge-notif');
            if (badgeEl) {
                if (unreadCount > 0) {
                    badgeEl.textContent = unreadCount;
                    badgeEl.style.display = 'inline-block';
                } else {
                    badgeEl.style.display = 'none';
                }
            }

            if (!pesanList || pesanList.length === 0) {
                container.innerHTML = `<p style="text-align:center; padding:30px; color:var(--text-gray);">Belum ada pesan masuk.</p>`;
                return;
            }

            container.innerHTML = pesanList.map(item => {
                const isUnread = item.dibaca == 0;
                const waktuFormatted = item.waktu ? new Date(item.waktu).toLocaleString('id-ID', {
                    day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
                }) : '-';

                return `
                    <div class="pesan-item ${isUnread ? 'unread' : ''}" data-baca="${item.dibaca}" data-subjek="${item.subjek}" onclick="bukaPesan(${item.id})">
                        <div class="pi-avatar ${!isUnread ? 'read' : ''}"><i class="fa fa-user"></i></div>
                        <div class="pi-content">
                            <div class="pi-header">
                                <strong>${item.nama_lengkap}</strong>
                                <span class="pi-subjek">${item.subjek}</span>
                                <span class="pi-time">${waktuFormatted}</span>
                            </div>
                            <p class="pi-email"><i class="fa fa-envelope"></i> ${item.kontak_whatsapp}</p>
                            <p class="pi-preview">${item.isi_pesan}</p>
                        </div>
                        ${isUnread ? '<span class="pi-unread-dot"></span>' : ''}
                    </div>
                `;
            }).join('');
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }

        function filterPesan() {
            const baca = document.getElementById('filterBaca').value;
            const subjek = document.getElementById('filterSubjek').value;
            const kw = document.getElementById('searchPesan').value.toLowerCase();
            document.querySelectorAll('.pesan-item').forEach(item => {
                const ib = item.dataset.baca;
                const is = item.dataset.subjek;
                const it = item.textContent.toLowerCase();
                const m1 = baca === '' || ib === baca;
                const m2 = !subjek || is === subjek;
                const m3 = !kw || it.includes(kw);
                item.style.display = (m1 && m2 && m3) ? '' : 'none';
            });
        }

        async function bukaPesan(id) {
            const item = pesanList.find(x => x.id == id);
            if (!item) return;

            activeId = id;

            if (item.dibaca == 0) {
                try {
                    await fetch(`${API_BASE}/kontak.php?id=${id}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        credentials: 'same-origin',
                        body: JSON.stringify({ dibaca: 1 })
                    });
                    item.dibaca = 1;
                } catch (err) {
                    console.error('Gagal memperbarui status baca:', err);
                }
            }

            const waktuFormatted = item.waktu ? new Date(item.waktu).toLocaleString('id-ID', {
                day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
            }) : '-';

            document.getElementById('pesanModalTitle').textContent = item.subjek;
            document.getElementById('pesanDetail').innerHTML = `
                <div class="pd-meta">
                  <div><span>Dari</span><strong>${item.nama_lengkap}</strong></div>
                  <div><span>Kontak</span><strong>${item.kontak_whatsapp}</strong></div>
                  <div><span>Subjek</span><strong>${item.subjek}</strong></div>
                  <div><span>Waktu</span><strong>${waktuFormatted}</strong></div>
                </div>
                <div class="pd-pesan" style="margin-top:15px; line-height:1.6;">
                  <p>${item.isi_pesan}</p>
                </div>
            `;
            
            document.getElementById('btnBalas').href = `mailto:${item.kontak_whatsapp}?subject=Re: ${item.subjek}`;
            document.getElementById('pesanModal').style.display = 'flex';
            renderPesanList();
        }

        function closePesanModal() {
            document.getElementById('pesanModal').style.display = 'none';
            loadPesan();
        }

        async function hapusPesan() {
            if (confirm('Yakin hapus pesan ini?') && activeId) {
                try {
                    const response = await fetch(`${API_BASE}/kontak.php?id=${activeId}`, {
                        method: 'DELETE',
                        credentials: 'same-origin'
                    });
                    if (!response.ok) throw new Error('Gagal menghapus pesan');

                    closePesanModal();
                    loadPesan();
                } catch (err) {
                    alert('Terjadi kesalahan: ' + err.message);
                }
            }
        }

        async function tandaiSemuaDibaca() {
            try {
                const response = await fetch(`${API_BASE}/kontak.php?all=1`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'same-origin',
                    body: JSON.stringify({ dibaca: 1 })
                });

                if (response.ok) {
                    alert('Semua pesan berhasil ditandai sebagai sudah dibaca!');
                    loadPesan();
                }
            } catch (err) {
                alert('Terjadi kesalahan: ' + err.message);
            }
        }
    </script>
</body>

</html>
