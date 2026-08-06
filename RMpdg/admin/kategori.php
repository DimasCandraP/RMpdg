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

    <!-- Modal View Menu Per Kategori -->
    <div class="modal-overlay" id="viewMenuModal" onclick="closeViewMenuModal()">
        <div class="modal-box" onclick="event.stopPropagation()" style="max-width:720px; width:92%; border-radius:20px; box-shadow:0 20px 50px rgba(0,0,0,0.2); padding:24px;">
            <div class="modal-header" style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px dashed #eee; padding-bottom:14px;">
                <h3 id="viewMenuTitle" style="margin:0; font-size:1.15rem; color:var(--primary-dark,#8B0000); font-weight:800;"><i class="fa fa-utensils"></i> Daftar Menu Kategori</h3>
                <button onclick="closeViewMenuModal()" style="background:#F5F5F5; border:none; width:32px; height:32px; border-radius:50%; font-size:1.1rem; cursor:pointer; color:#666; display:flex; align-items:center; justify-content:center; transition:all 0.2s;"><i class="fa fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding:18px 0 0; max-height:68vh; overflow-y:auto;">
                <div id="viewMenuContent">
                    <p style="text-align:center; color:#888;">Memuat data menu...</p>
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
                    <td><strong style="color:#2c3e50; font-size:0.95rem;">${item.nama}</strong></td>
                    <td>
                        <span class="badge confirmed" style="cursor:pointer; padding:6px 12px; font-size:0.82rem; font-weight:700; border-radius:20px;" onclick="viewCategoryMenu(${item.id}, '${item.nama.replace(/'/g, "\\'")}')" title="Klik untuk melihat menu">
                            <i class="fa fa-utensils" style="margin-right:4px;"></i> ${item.total_menu || 0} Menu
                        </span>
                    </td>
                    <td>
                        <div class="action-btns" style="display:flex; align-items:center; gap:8px;">
                            <button class="btn-view-menu" onclick="viewCategoryMenu(${item.id}, '${item.nama.replace(/'/g, "\\'")}')" title="Lihat Daftar Menu">
                                <i class="fa fa-eye"></i> Lihat Menu
                            </button>
                            <button class="btn-act delete" onclick="deleteKategori(${item.id})" title="Hapus Kategori">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        async function viewCategoryMenu(catId, catName) {
            document.getElementById('viewMenuTitle').innerHTML = `<i class="fa fa-utensils"></i> Daftar Menu: <strong style="color:var(--primary,#8B0000);">${catName}</strong>`;
            document.getElementById('viewMenuContent').innerHTML = `<p style="text-align:center; padding:20px; color:#888;"><i class="fa fa-spinner fa-spin"></i> Memuat daftar menu...</p>`;
            document.getElementById('viewMenuModal').style.display = 'flex';

            try {
                const res = await fetch(`${API_BASE}/menu.php?kategori=${catId}&all=1`, { credentials: 'same-origin' });
                if (!res.ok) throw new Error('Gagal memuat menu dari server');
                const menuList = await res.json();

                if (!Array.isArray(menuList) || menuList.length === 0) {
                    document.getElementById('viewMenuContent').innerHTML = `<div style="text-align:center; padding:30px; color:#888;"><i class="fa fa-info-circle" style="font-size:2rem; margin-bottom:8px; display:block; color:#ccc;"></i>Belum ada menu terdaftar di kategori ini.</div>`;
                    return;
                }

                document.getElementById('viewMenuContent').innerHTML = `
                    <div style="font-size:0.85rem; color:#666; margin-bottom:12px;">Total <strong>${menuList.length}</strong> menu ditemukan pada kategori ini:</div>
                    <div class="table-responsive">
                        <table class="admin-table" style="font-size:0.88rem;">
                            <thead>
                                <tr>
                                    <th>Gambar</th>
                                    <th>Nama Menu</th>
                                    <th>Harga</th>
                                    <th>Pedas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${menuList.map(m => {
                                    const imgUrl = m.gambar_utama 
                                        ? (m.gambar_utama.startsWith('http') ? m.gambar_utama : `${API_BASE}/../uploads/${m.gambar_utama}`)
                                        : '../img/logo.png';
                                    return `
                                        <tr>
                                            <td><img src="${imgUrl}" alt="${m.nama}" style="width:40px; height:40px; border-radius:6px; object-fit:cover; border:1px solid #eee;" onerror="this.src='../img/logo.png'" /></td>
                                            <td><strong>${m.nama}</strong><br/><small style="color:#888;">/${m.slug}</small></td>
                                            <td style="font-weight:700; color:var(--primary,#8B0000);">Rp ${(parseInt(m.harga)||0).toLocaleString('id-ID')}</td>
                                            <td>${m.tingkat_pedas ? `<span style="color:#e74c3c; font-weight:700;">🌶️ ${m.tingkat_pedas}</span>` : '-'}</td>
                                            <td><span class="badge ${m.status === 'aktif' ? 'confirmed' : 'cancelled'}">${m.status || 'aktif'}</span></td>
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } catch (err) {
                document.getElementById('viewMenuContent').innerHTML = `<p style="text-align:center; padding:20px; color:#c0392b;">Gagal memuat menu: ${err.message}</p>`;
            }
        }

        function closeViewMenuModal() {
            document.getElementById('viewMenuModal').style.display = 'none';
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
