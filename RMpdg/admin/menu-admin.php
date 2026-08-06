<?php require_once __DIR__ . '/_guard.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Menu - Admin RM Padang</title>
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
            <a href="menu-admin.php" class="nav-item active"><i class="fa fa-utensils"></i> Menu Makanan</a>
            <a href="kategori.php" class="nav-item"><i class="fa fa-tags"></i> Kategori Menu</a>
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
                    <h2>Kelola Menu</h2>
                    <span>Tambah, edit, dan hapus data menu makanan</span>
                </div>
            </div>
            <div class="topbar-right">
                <button class="btn-add" onclick="openForm()">
                    <i class="fa fa-plus"></i> Tambah Menu
                </button>
            </div>
        </header>

        <div class="admin-content">

            <div class="table-toolbar">
                <div class="toolbar-left">
                    <select id="filterKategori">
                        <option value="">Semua Kategori</option>
                        <option>Makanan Utama</option>
                        <option>Aneka Lauk</option>
                        <option>Aneka Sayur</option>
                        <option>Minuman</option>
                        <option>Tambahan</option>
                    </select>
                    <select id="filterTersedia">
                        <option value="">Semua Status</option>
                        <option value="1">Tersedia</option>
                        <option value="0">Tidak Tersedia</option>
                    </select>
                </div>
                <div class="toolbar-right">
                    <div class="search-box">
                        <i class="fa fa-search"></i>
                        <input type="text" placeholder="Cari menu..." />
                    </div>
                </div>
            </div>

            <div class="dash-card">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Nama Menu</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Terjual</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td colspan="7" style="text-align:center; padding:15px; color:#888;">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <style>
                #menuModalOverlay {
                    display: none;
                    position: fixed;
                    inset: 0;
                    background: rgba(0,0,0,0.55);
                    backdrop-filter: blur(4px);
                    z-index: 300;
                    align-items: center;
                    justify-content: center;
                    padding: 24px;
                }

                #menuModalOverlay .modal-box {
                    background: #fff;
                    border-radius: 16px;
                    width: 100%;
                    max-width: 640px;
                    max-height: 90vh;
                    overflow-y: auto;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
                    animation: mfadeIn 0.25s ease;
                }

                @keyframes mfadeIn {
                    from { opacity: 0; transform: translateY(-20px) scale(0.97); }
                    to   { opacity: 1; transform: translateY(0) scale(1); }
                }

                #menuModalOverlay .modal-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 20px 24px;
                    border-bottom: 1px solid #F0F0F0;
                    background: #fff;
                    position: sticky;
                    top: 0;
                    z-index: 10;
                }

                #menuModalOverlay .modal-header h3 {
                    font-size: 1.1rem;
                    font-weight: 700;
                    color: var(--primary);
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }

                #menuModalOverlay .modal-header h3::before {
                    content: '';
                    display: inline-block;
                    width: 4px;
                    height: 18px;
                    background: var(--primary);
                    border-radius: 2px;
                }

                #menuModalOverlay .modal-header button {
                    background: #F5F5F5;
                    border: none;
                    width: 32px;
                    height: 32px;
                    border-radius: 50%;
                    font-size: 0.9rem;
                    color: #666;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: background 0.2s, color 0.2s;
                }

                #menuModalOverlay .modal-header button:hover {
                    background: #FFEDED;
                    color: var(--primary);
                }

                #menuModalOverlay .modal-body {
                    padding: 24px;
                    display: flex;
                    flex-direction: column;
                    gap: 0;
                }

                #menuModalOverlay .mform-section-title {
                    font-size: 0.75rem;
                    font-weight: 700;
                    text-transform: uppercase;
                    letter-spacing: 1px;
                    color: #999;
                    margin-bottom: 12px;
                    margin-top: 4px;
                    padding-bottom: 6px;
                    border-bottom: 1px dashed #EEE;
                }

                #menuModalOverlay .mform-row {
                    display: flex;
                    gap: 14px;
                    margin-bottom: 16px;
                }

                #menuModalOverlay .mform-row.two-col .mform-group {
                    flex: 1;
                }

                #menuModalOverlay .mform-group {
                    display: flex;
                    flex-direction: column;
                    gap: 6px;
                    flex: 1;
                }

                #menuModalOverlay .mform-group.full {
                    width: 100%;
                    margin-bottom: 16px;
                }

                #menuModalOverlay .mform-group label {
                    font-size: 0.8rem;
                    font-weight: 600;
                    color: #444;
                    display: flex;
                    align-items: center;
                    gap: 4px;
                    margin: 0;
                }

                #menuModalOverlay .mform-group label .req {
                    color: #E53E3E;
                }

                #menuModalOverlay .mform-group input,
                #menuModalOverlay .mform-group select,
                #menuModalOverlay .mform-group textarea {
                    width: 100%;
                    padding: 10px 14px;
                    border: 1.5px solid #E2E8F0;
                    border-radius: 8px;
                    font-size: 0.88rem;
                    font-family: inherit;
                    color: #2D3748;
                    background: #FAFAFA;
                    outline: none;
                    box-sizing: border-box;
                    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
                }

                #menuModalOverlay .mform-group input::placeholder,
                #menuModalOverlay .mform-group textarea::placeholder {
                    color: #C0C9D6;
                }

                #menuModalOverlay .mform-group input:focus,
                #menuModalOverlay .mform-group select:focus,
                #menuModalOverlay .mform-group textarea:focus {
                    border-color: var(--primary);
                    background: #fff;
                    box-shadow: 0 0 0 3px rgba(139,0,0,0.1);
                }

                #menuModalOverlay .mform-group textarea {
                    resize: vertical;
                    min-height: 80px;
                }

                #menuModalOverlay .mform-group select {
                    cursor: pointer;
                    appearance: none;
                    -webkit-appearance: none;
                    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23718096' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
                    background-repeat: no-repeat;
                    background-position: right 12px center;
                    padding-right: 36px;
                }

                #menuModalOverlay .mmodal-footer {
                    display: flex;
                    justify-content: flex-end;
                    align-items: center;
                    gap: 12px;
                    padding: 20px 24px;
                    border-top: 1px solid #F0F0F0;
                    background: #FAFAFA;
                    border-radius: 0 0 16px 16px;
                    margin: 16px -24px -24px;
                }

                #menuModalOverlay .mbtn-cancel {
                    padding: 10px 22px;
                    background: #fff;
                    color: #555;
                    border: 1.5px solid #E2E8F0;
                    border-radius: 8px;
                    font-size: 0.85rem;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.2s;
                }

                #menuModalOverlay .mbtn-cancel:hover {
                    background: #F5F5F5;
                    color: #333;
                }

                #menuModalOverlay .mbtn-save {
                    padding: 10px 24px;
                    background: var(--primary);
                    color: #fff;
                    border: none;
                    border-radius: 8px;
                    font-size: 0.85rem;
                    font-weight: 700;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    box-shadow: 0 4px 12px rgba(139,0,0,0.25);
                    transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
                }

                #menuModalOverlay .mbtn-save:hover {
                    background: var(--primary-dark);
                    transform: translateY(-1px);
                    box-shadow: 0 6px 16px rgba(139,0,0,0.35);
                }

                @media (max-width: 580px) {
                    #menuModalOverlay .mform-row {
                        flex-direction: column;
                        gap: 16px;
                    }
                }
            </style>

            <div class="modal-overlay" id="menuModalOverlay" onclick="closeMenuForm()">
                <div class="modal-box" onclick="event.stopPropagation()" style="max-width:640px;">
                    <div class="modal-header">
                        <h3 id="menuFormTitle">Tambah Menu Baru</h3>
                        <button onclick="closeMenuForm()"><i class="fa fa-times"></i></button>
                    </div>

                    <div class="modal-body">
                        <p class="mform-section-title"><i class="fa fa-info-circle" style="color:var(--primary);margin-right:6px;"></i>Informasi Dasar</p>
                        <div class="mform-row">
                            <div class="mform-group">
                                <label>Nama Menu <span class="req">*</span></label>
                                <input type="text" id="mNama" placeholder="Contoh: Rendang Sapi" />
                            </div>
                            <div class="mform-group">
                                <label>Kategori <span class="req">*</span></label>
                                <select id="mKategori">
                                    <option value="1">🍛 Makanan Utama</option>
                                    <option value="2">🥩 Aneka Lauk</option>
                                    <option value="3">🥦 Aneka Sayur</option>
                                    <option value="4">🥤 Minuman</option>
                                    <option value="5">➕ Tambahan</option>
                                </select>
                            </div>
                        </div>

                        <div class="mform-group full">
                            <label>Deskripsi</label>
                            <textarea id="mDeskripsi" rows="3" placeholder="Tulis deskripsi singkat menu ini..."></textarea>
                        </div>

                        <p class="mform-section-title" style="margin-top:8px;"><i class="fa fa-tag" style="color:var(--primary);margin-right:6px;"></i>Harga</p>
                        <div class="mform-group full">
                            <label>Harga (Rp) <span class="req">*</span></label>
                            <input type="number" id="mHarga" placeholder="25000" min="0" />
                        </div>

                        <div class="mform-group full">
                            <label>Status Ketersediaan</label>
                            <select id="mStatus">
                                <option value="aktif">✅ Tersedia (Aktif)</option>
                                <option value="nonaktif">❌ Tidak Tersedia (Nonaktif)</option>
                            </select>
                        </div>

                        <div class="mmodal-footer">
                            <button class="mbtn-cancel" onclick="closeMenuForm()">
                                <i class="fa fa-times"></i> Batal
                            </button>
                            <button class="mbtn-save" onclick="saveMenu()">
                                <i class="fa fa-save"></i> Simpan Menu
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

        let menuList = [];

        async function doLogout() {
            if (!confirm('Yakin ingin logout?')) return;
            try {
                await fetch(`${API_BASE}/logout.php`, { credentials: 'same-origin' });
            } catch(e){}
            window.location.href = 'login.php';
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadMenuAdmin();
        });

        async function loadMenuAdmin() {
            try {
                const response = await fetch(`${API_BASE}/menu.php?all=1`, { credentials: 'same-origin' });
                if (!response.ok) throw new Error('Gagal memuat data menu');
                menuList = await response.json();
                renderMenuTable();
            } catch (err) {
                console.error(err);
                document.querySelector('.admin-table tbody').innerHTML = `<tr><td colspan="7" style="text-align:center; padding:30px; color:#c0392b;">Gagal memuat data: ${err.message}</td></tr>`;
            }
        }

        function renderMenuTable() {
            const tbody = document.querySelector('.admin-table tbody');
            if (!menuList || menuList.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding:30px; color:var(--text-gray);">Belum ada data menu.</td></tr>`;
                return;
            }

            tbody.innerHTML = menuList.map((menu) => {
                let badgeClass = menu.status === 'aktif' ? 'confirmed' : 'cancel';
                let statusLabel = menu.status === 'aktif' ? 'Tersedia' : 'Tidak Tersedia';

                let imgSrc = menu.gambar_utama || 'img/logo.png';
                if (!imgSrc.startsWith('img/') && !imgSrc.startsWith('../') && !imgSrc.startsWith('/')) {
                    imgSrc = 'img/' + imgSrc;
                }

                return `
                    <tr>
                        <td><img src="../${imgSrc}" onerror="this.onerror=null; this.src='../img/logo.png';" alt="${menu.nama}" class="tbl-img" style="width:45px; height:45px; object-fit:cover; border-radius:6px;" /></td>
                        <td><strong>${menu.nama}</strong><br /><small>${menu.slug || '-'}</small></td>
                        <td>${menu.kategori_nama || 'Menu'}</td>
                        <td>Rp${Number(menu.harga).toLocaleString('id-ID')}</td>
                        <td><span class="badge ${badgeClass}" id="badge-${menu.id}">${statusLabel}</span></td>
                        <td>${menu.jumlah_terjual ? Number(menu.jumlah_terjual).toLocaleString('id-ID') + ' porsi' : '-'}</td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-act" style="background:${menu.status==='aktif'?'#e8f5e9':'#fff3e0'};color:${menu.status==='aktif'?'#2e7d32':'#e65100'};border:1px solid ${menu.status==='aktif'?'#a5d6a7':'#ffcc80'};" onclick="toggleMenuStatus(${menu.id},'${menu.status}')" title="Toggle Status">
                                    <i class="fa fa-${menu.status==='aktif'?'check-circle':'times-circle'}"></i>
                                </button>
                                <button class="btn-act delete" onclick="hapusMenu(${menu.id})" title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function hapusMenu(id) {
            if (confirm('Yakin ingin menghapus menu ini?')) {
                try {
                    const response = await fetch(`${API_BASE}/menu.php?id=${id}`, {
                        method: 'DELETE',
                        credentials: 'same-origin'
                    });
                    const result = await response.json();
                    if (!response.ok) throw new Error(result.error || 'Gagal menghapus menu');

                    alert('Menu berhasil dihapus!');
                    loadMenuAdmin();
                } catch (err) {
                    alert('Terjadi kesalahan: ' + err.message);
                }
            }
        }

        async function toggleMenuStatus(id, currentStatus) {
            const newStatus = currentStatus === 'aktif' ? 'nonaktif' : 'aktif';
            const label = newStatus === 'aktif' ? 'Tersedia' : 'Habis';
            if (!confirm(`Ubah status menu menjadi "${label}"?`)) return;
            try {
                const response = await fetch(`${API_BASE}/menu.php?id=${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'same-origin',
                    body: JSON.stringify({ status: newStatus })
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.error || 'Gagal update status');
                loadMenuAdmin();
            } catch (err) {
                alert('Gagal: ' + err.message);
            }
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }

        function openForm(mode = 'add') {
            document.getElementById('menuFormTitle').textContent =
                mode === 'add' ? 'Tambah Menu Baru' : 'Edit Menu';
            if (mode === 'add') {
                document.getElementById('mNama').value = '';
                document.getElementById('mDeskripsi').value = '';
                document.getElementById('mHarga').value = '';
                document.getElementById('mStatus').value = 'aktif';
                document.getElementById('mKategori').value = '1';
            }
            document.getElementById('menuModalOverlay').style.display = 'flex';
        }

        function closeMenuForm() {
            document.getElementById('menuModalOverlay').style.display = 'none';
        }

        async function saveMenu() {
            const nama = document.getElementById('mNama').value.trim();
            const kategori_id = document.getElementById('mKategori').value;
            const deskripsi = document.getElementById('mDeskripsi').value.trim();
            const harga = document.getElementById('mHarga').value;
            const status = document.getElementById('mStatus').value;

            if (!nama || !harga) {
                alert('Nama menu dan Harga wajib diisi!');
                return;
            }

            const slug = nama.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');

            try {
                const response = await fetch(`${API_BASE}/menu.php`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        slug,
                        kategori_id,
                        nama,
                        harga: Number(harga),
                        deskripsi,
                        gambar_utama: 'img/071114000_1522751934-Resep-Rendang-Ayam-Kering.jpg',
                        status
                    })
                });

                const result = await response.json();
                if (!response.ok) throw new Error(result.error || 'Gagal menyimpan menu');

                alert('Menu baru berhasil ditambahkan!');
                closeMenuForm();
                loadMenuAdmin();
            } catch (err) {
                alert('Terjadi kesalahan: ' + err.message);
            }
        }
    </script>
</body>

</html>
