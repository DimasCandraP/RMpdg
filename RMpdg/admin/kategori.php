<?php require_once __DIR__ . '/_guard.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kategori Menu - Admin RM Padang</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/admin.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body class="admin-body">

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
            <a href="dashboard.php" class="nav-item"><i class="fa fa-chart-pie"></i> Dashboard</a>
            <div class="nav-label">KELOLA</div>
            <a href="menu-admin.php" class="nav-item"><i class="fa fa-utensils"></i> Menu Makanan</a>
            <a href="kategori.php" class="nav-item active"><i class="fa fa-tags"></i> Kategori Menu</a>
            <a href="catering-admin.php" class="nav-item"><i class="fa fa-box-open"></i> Paket Catering</a>
            <a href="pesanan.php" class="nav-item"><i class="fa fa-clipboard-list"></i> Pesanan Catering</a>
            <a href="pesanan-menu.php" class="nav-item"><i class="fa fa-shopping-cart"></i> Pesanan Menu</a>
            <a href="reservasi-admin.php" class="nav-item"><i class="fa fa-calendar-check"></i> Reservasi</a>
            <a href="promosi-admin.php" class="nav-item"><i class="fa fa-percent"></i> Promosi</a>
            <a href="kontak-admin.php" class="nav-item"><i class="fa fa-envelope"></i> Pesan Masuk</a>
            <div class="nav-label">AKUN</div>
            <a href="#" class="nav-item" onclick="doLogout(); return false;"><i class="fa fa-right-from-bracket"></i> Logout</a>
        </nav>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <div class="topbar-left">
                <button class="sidebar-toggle" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
                <div class="topbar-title">
                    <h2>Kategori Menu</h2>
                    <span>Kelola kelompok kategori sajian makanan & minuman</span>
                </div>
            </div>
            <div class="topbar-right">
                <button class="btn-add" onclick="openKatModal()">
                    <i class="fa fa-plus"></i> Tambah Kategori
                </button>
            </div>
        </header>

        <div class="admin-content">
            <div class="dash-card">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Nama Kategori</th>
                                <th>Jumlah Menu Terdaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="kategoriBody">
                            <tr><td colspan="4" style="text-align:center; padding:25px; color:#888;">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="katModal" onclick="closeKatModal()">
        <div class="modal-box" onclick="event.stopPropagation()" style="max-width:450px;">
            <div class="modal-header">
                <h3>Tambah Kategori Baru</h3>
                <button onclick="closeKatModal()"><i class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group" style="margin-bottom:15px;">
                    <label style="display:block; font-weight:600; margin-bottom:6px;">Nama Kategori <span class="req">*</span></label>
                    <input type="text" id="katNama" placeholder="Contoh: Aneka Jus & Es" style="width:100%; padding:10px; border-radius:6px; border:1px solid #ccc;" />
                </div>
                <div class="modal-footer" style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                    <button class="btn-cancel" onclick="closeKatModal()" style="padding:8px 16px; border-radius:6px; border:1px solid #ccc; background:#f0f0f0;">Batal</button>
                    <button class="btn-save" onclick="saveKategori()" style="padding:8px 16px; border-radius:6px; background:var(--primary); color:white; border:none; font-weight:600;">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/main.js"></script>
    <script>
        const API_BASE = window.API_BASE || (window.location.pathname.includes('/MPTI/') ? '/MPTI/rmpdg-backend/api' : '/rmpdg-backend/api');

        async function doLogout() {
            if (!confirm('Yakin ingin logout?')) return;
            try {
                await fetch(`${API_BASE}/logout.php`, { credentials: 'same-origin' });
            } catch(e){}
            window.location.href = 'login.php';
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadKategori();
        });

        async function loadKategori() {
            try {
                const res = await fetch(`${API_BASE}/kategori.php`, { credentials: 'same-origin' });
                if (!res.ok) throw new Error('Gagal mengambil data dari server');
                const list = await res.json();
                renderTable(list);
            } catch (err) {
                console.error(err);
                document.getElementById('kategoriBody').innerHTML = `<tr><td colspan="4" style="text-align:center; padding:25px; color:#c0392b;">Gagal memuat kategori: ${err.message}</td></tr>`;
            }
        }

        function renderTable(list) {
            const tbody = document.getElementById('kategoriBody');
            if (!Array.isArray(list) || list.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" style="text-align:center; padding:25px; color:#888;">Belum ada kategori.</td></tr>`;
                return;
            }

            tbody.innerHTML = list.map(item => `
                <tr>
                    <td>#${item.id}</td>
                    <td><strong>${item.nama}</strong></td>
                    <td><span class="badge confirmed">${item.total_menu || 0} Menu</span></td>
                    <td>
                        <div class="action-btns">
                            <button class="btn-act delete" onclick="deleteKategori(${item.id})" title="Hapus">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function openKatModal() {
            document.getElementById('katNama').value = '';
            document.getElementById('katModal').style.display = 'flex';
        }

        function closeKatModal() {
            document.getElementById('katModal').style.display = 'none';
        }

        async function saveKategori() {
            const nama = document.getElementById('katNama').value.trim();
            if (!nama) {
                alert('Nama kategori wajib diisi!');
                return;
            }

            try {
                const res = await fetch(`${API_BASE}/kategori.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'same-origin',
                    body: JSON.stringify({ nama })
                });

                const result = await res.json();
                if (!res.ok) throw new Error(result.error || 'Gagal menyimpan kategori');

                alert('Kategori baru berhasil ditambahkan!');
                closeKatModal();
                loadKategori();
            } catch (err) {
                alert('Error: ' + err.message);
            }
        }

        async function deleteKategori(id) {
            if (confirm('Yakin ingin menghapus kategori ini?')) {
                try {
                    const res = await fetch(`${API_BASE}/kategori.php?id=${id}`, {
                        method: 'DELETE',
                        credentials: 'same-origin'
                    });

                    const result = await res.json();
                    if (!res.ok) throw new Error(result.error || 'Gagal menghapus kategori');

                    alert('Kategori berhasil dihapus!');
                    loadKategori();
                } catch (err) {
                    alert('Error: ' + err.message);
                }
            }
        }
    </script>
</body>

</html>
