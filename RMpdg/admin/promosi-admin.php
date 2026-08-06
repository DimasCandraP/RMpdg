<?php require_once __DIR__ . '/_guard.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Promosi - Admin RM Padang</title>
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
            <a href="promosi-admin.php" class="nav-item active"><i class="fa fa-percent"></i> Promosi</a>
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
                    <h2>Kelola Promosi</h2>
                    <span>Atur promo dan diskon yang tampil di website</span>
                </div>
            </div>
            <div class="topbar-right">
                <button class="btn-add" onclick="openPromoForm()">
                    <i class="fa fa-plus"></i> Tambah Promo
                </button>
            </div>
        </header>

        <div class="admin-content">

            <div class="promo-admin-grid" id="promoGrid">
            </div>

            <div class="modal-overlay" id="promoModal" onclick="closePromoModal()">
                <div class="modal-box modal-lg" onclick="event.stopPropagation()">
                    <div class="modal-header">
                        <h3 id="promoFormTitle">Tambah Promo Baru</h3>
                        <button onclick="closePromoModal()"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Judul Promo <span class="req">*</span></label>
                                <input type="text" id="pJudul" placeholder="Contoh: DISKON 20%" />
                            </div>
                            <div class="form-group">
                                <label>Sub Judul</label>
                                <input type="text" id="pSubJudul" placeholder="Contoh: PAKET CATERING" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Promo</label>
                            <textarea id="pDeskripsi" rows="3" placeholder="Jelaskan syarat dan detail promosi..."></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Diskon (%)</label>
                                <input type="number" id="pDiskon" min="0" max="100" placeholder="20" />
                            </div>
                            <div class="form-group">
                                <label>Warna Tema</label>
                                <select id="pWarna">
                                    <option value="red">Merah (Maroon)</option>
                                    <option value="gold">Kuning (Gold)</option>
                                    <option value="maroon">Merah Tua</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Tanggal Mulai <span class="req">*</span></label>
                                <input type="date" id="pTglMulai" />
                            </div>
                            <div class="form-group">
                                <label>Tanggal Akhir <span class="req">*</span></label>
                                <input type="date" id="pTglAkhir" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Gambar Promo</label>
                            <div class="upload-box" onclick="document.getElementById('promoImg').click()">
                                <i class="fa fa-cloud-arrow-up"></i>
                                <p>Klik untuk upload gambar</p>
                                <span>JPG, PNG (Maks. 2MB)</span>
                                <input type="file" id="promoImg" accept="image/*" style="display:none" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select id="pStatus">
                                <option value="aktif">Aktif (Tampil di website)</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button class="btn-cancel" onclick="closePromoModal()">Batal</button>
                            <button class="btn-save" onclick="savePromo()">
                                <i class="fa fa-save"></i> Simpan Promo
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

        let promoList = [];

        async function doLogout() {
            if (!confirm('Yakin ingin logout?')) return;
            try {
                await fetch(`${API_BASE}/logout.php`, { credentials: 'same-origin' });
            } catch(e){}
            window.location.href = 'login.php';
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadPromoAdmin();
        });

        async function loadPromoAdmin() {
            try {
                const response = await fetch(`${API_BASE}/promosi.php?all=1`, { credentials: 'same-origin' });
                if (!response.ok) throw new Error('Gagal memuat data promosi');
                promoList = await response.json();
                renderPromoGrid();
            } catch (err) {
                console.error(err);
                document.getElementById('promoGrid').innerHTML = `<p style="text-align:center; padding:30px; color:#c0392b; grid-column:1/-1;">Gagal memuat data: ${err.message}</p>`;
            }
        }

        function renderPromoGrid() {
            const container = document.getElementById('promoGrid');
            if (!promoList || promoList.length === 0) {
                container.innerHTML = `<p style="text-align:center; padding:30px; color:var(--text-gray); grid-column:1/-1;">Belum ada data promosi.</p>`;
                return;
            }

            container.innerHTML = promoList.map(promo => {
                const isActive = promo.status === 'aktif';
                const themeClass = promo.warna_tema || 'red';

                return `
                    <div class="promo-admin-card">
                        <div class="pac-header ${themeClass}">
                            <span class="pac-badge ${isActive ? '' : 'nonaktif'}">${isActive ? 'AKTIF' : 'NONAKTIF'}</span>
                            <div class="pac-actions">
                                <button onclick="hapusPromo(${promo.id})" title="Hapus"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        <div class="pac-body">
                            <h3>${promo.judul}</h3>
                            <p class="pac-sub">${promo.sub_judul || '-'}</p>
                            <p class="pac-desc">${promo.deskripsi || '-'}</p>
                            <div class="pac-meta">
                                <span><i class="fa fa-calendar"></i> ${promo.tanggal_mulai} s/d ${promo.tanggal_akhir}</span>
                                <span><i class="fa fa-percent"></i> ${promo.diskon_persen || 0}%</span>
                            </div>
                            <div class="pac-toggle">
                                <span>Status Tampil:</span>
                                <label class="toggle-switch">
                                    <input type="checkbox" ${isActive ? 'checked' : ''} onchange="toggleStatusPromo(${promo.id}, this)" />
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label">${isActive ? 'Aktif' : 'Nonaktif'}</span>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        async function toggleStatusPromo(id, checkbox) {
            const statusBaru = checkbox.checked ? 'aktif' : 'nonaktif';
            const promoData = promoList.find(x => x.id == id);
            if (!promoData) return;

            promoData.status = statusBaru;

            try {
                const response = await fetch(`${API_BASE}/promosi.php?id=${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        judul: promoData.judul,
                        sub_judul: promoData.sub_judul,
                        deskripsi: promoData.deskripsi,
                        diskon_persen: promoData.diskon_persen,
                        warna_tema: promoData.warna_tema,
                        tanggal_mulai: promoData.tanggal_mulai,
                        tanggal_akhir: promoData.tanggal_akhir,
                        status: statusBaru
                    })
                });

                const result = await response.json();
                if (!response.ok) throw new Error(result.error || 'Gagal mengubah status promo');
                
                loadPromoAdmin();
            } catch (err) {
                alert('Terjadi kesalahan: ' + err.message);
                loadPromoAdmin();
            }
        }

        async function hapusPromo(id) {
            if (confirm('Yakin ingin menghapus promosi ini?')) {
                try {
                    const response = await fetch(`${API_BASE}/promosi.php?id=${id}`, {
                        method: 'DELETE',
                        credentials: 'same-origin'
                    });
                    const result = await response.json();
                    if (!response.ok) throw new Error(result.error || 'Gagal menghapus promo');

                    alert('Promosi berhasil dihapus!');
                    loadPromoAdmin();
                } catch (err) {
                    alert('Terjadi kesalahan: ' + err.message);
                }
            }
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }

        function openPromoForm(mode = 'add') {
            document.getElementById('promoFormTitle').textContent =
                mode === 'add' ? 'Tambah Promo Baru' : 'Edit Promo';
            document.getElementById('promoModal').style.display = 'flex';
        }

        function closePromoModal() {
            document.getElementById('promoModal').style.display = 'none';
        }

        async function savePromo() {
            const judul = document.getElementById('pJudul').value.trim();
            const subJudul = document.getElementById('pSubJudul').value.trim();
            const deskripsi = document.getElementById('pDeskripsi').value.trim();
            const diskon = document.getElementById('pDiskon').value;
            const warnaTema = document.getElementById('pWarna').value;
            const tglMulai = document.getElementById('pTglMulai').value;
            const tglAkhir = document.getElementById('pTglAkhir').value;
            const status = document.getElementById('pStatus').value;
            const fileInput = document.getElementById('promoImg');

            if (!judul || !tglMulai || !tglAkhir) {
                alert('Judul promo, Tanggal Mulai, dan Tanggal Akhir wajib diisi!');
                return;
            }

            const formData = new FormData();
            formData.append('judul', judul);
            formData.append('sub_judul', subJudul);
            formData.append('deskripsi', deskripsi);
            formData.append('diskon_persen', diskon || 0);
            formData.append('warna_tema', warnaTema);
            formData.append('tanggal_mulai', tglMulai);
            formData.append('tanggal_akhir', tglAkhir);
            formData.append('status', status);

            if (fileInput.files && fileInput.files.length > 0) {
                formData.append('gambar', fileInput.files[0]);
            }

            try {
                const response = await fetch(`${API_BASE}/promosi.php`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    body: formData
                });

                const result = await response.json();
                if (!response.ok) throw new Error(result.error || 'Gagal menambahkan promo');

                alert('Promosi berhasil ditambahkan!');
                closePromoModal();
                loadPromoAdmin();
            } catch (err) {
                alert('Terjadi kesalahan: ' + err.message);
            }
        }
    </script>
</body>

</html>
