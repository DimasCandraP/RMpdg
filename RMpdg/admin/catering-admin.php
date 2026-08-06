<?php require_once __DIR__ . '/_guard.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Paket Catering - Admin RM Padang</title>
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
            <a href="catering-admin.php" class="nav-item active"><i class="fa fa-box-open"></i> Paket Catering</a>
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
                    <h2>Kelola Paket Catering</h2>
                    <span>Atur paket catering yang tersedia di website</span>
                </div>
            </div>
            <div class="topbar-right">
                <button class="btn-add" onclick="openPaketForm()">
                    <i class="fa fa-plus"></i> Tambah Paket
                </button>
            </div>
        </header>

        <div class="admin-content">

            <div class="paket-admin-grid">

                <div class="paket-admin-card">
                    <div class="pak-img">
                        <img src="../img/catering/paket-a.jpg" alt="Paket A" />
                        <span class="pak-populer">POPULER</span>
                    </div>
                    <div class="pak-body">
                        <div class="pak-title-row">
                            <h3>Paket A</h3>
                            <span class="pak-price">Rp1.500.000</span>
                        </div>
                        <p class="pak-porsi"><i class="fa fa-users"></i> 50 Porsi</p>
                        <p class="pak-desc">Cocok untuk acara keluarga, arisan, ulang tahun, dan acara kecil lainnya.
                        </p>
                        <div class="pak-menu-list">
                            <strong>Menu Termasuk:</strong>
                            <p>Rendang, Ayam Pop, Dendeng Balado, Gulai Nangka, Sayur Daun Singkong, Sambal, Kerupuk</p>
                        </div>
                        <div class="pak-footer">
                            <label class="toggle-switch">
                                <input type="checkbox" checked onchange="togglePaket(this)" />
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">Aktif</span>
                            <div class="action-btns" style="margin-left:auto;">
                                <button class="btn-act edit" onclick="openPaketForm('edit')" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button class="btn-act delete" onclick="hapusPaket(this)" title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="paket-admin-card">
                    <div class="pak-img">
                        <img src="../img/catering/paket-b.jpg" alt="Paket B" />
                    </div>
                    <div class="pak-body">
                        <div class="pak-title-row">
                            <h3>Paket B</h3>
                            <span class="pak-price">Rp2.800.000</span>
                        </div>
                        <p class="pak-porsi"><i class="fa fa-users"></i> 100 Porsi</p>
                        <p class="pak-desc">Pilihan tepat untuk acara kantor, pengajian, dan acara menengah.</p>
                        <div class="pak-menu-list">
                            <strong>Menu Termasuk:</strong>
                            <p>Rendang, Ayam Pop, Dendeng Balado, Gulai Kambing, Gulai Nangka, Sayur Daun Singkong,
                                Sambal, Kerupuk</p>
                        </div>
                        <div class="pak-footer">
                            <label class="toggle-switch">
                                <input type="checkbox" checked onchange="togglePaket(this)" />
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">Aktif</span>
                            <div class="action-btns" style="margin-left:auto;">
                                <button class="btn-act edit" onclick="openPaketForm('edit')" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button class="btn-act delete" onclick="hapusPaket(this)" title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="paket-admin-card">
                    <div class="pak-img">
                        <img src="../img/catering/paket-c.jpg" alt="Paket C" />
                    </div>
                    <div class="pak-body">
                        <div class="pak-title-row">
                            <h3>Paket C</h3>
                            <span class="pak-price">Rp4.000.000</span>
                        </div>
                        <p class="pak-porsi"><i class="fa fa-users"></i> 150 Porsi</p>
                        <p class="pak-desc">Cocok untuk acara besar seperti pernikahan, syukuran, dan seminar.</p>
                        <div class="pak-menu-list">
                            <strong>Menu Termasuk:</strong>
                            <p>Rendang, Ayam Pop, Dendeng Balado, Gulai Kambing, Gulai Nangka, Sayur Daun Singkong,
                                Sambal, Kerupuk, Es Sirup</p>
                        </div>
                        <div class="pak-footer">
                            <label class="toggle-switch">
                                <input type="checkbox" checked onchange="togglePaket(this)" />
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">Aktif</span>
                            <div class="action-btns" style="margin-left:auto;">
                                <button class="btn-act edit" onclick="openPaketForm('edit')" title="Edit">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button class="btn-act delete" onclick="hapusPaket(this)" title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-overlay" id="paketModal" onclick="closePaketModal()">
                <div class="modal-box modal-lg" onclick="event.stopPropagation()">
                    <div class="modal-header">
                        <h3 id="paketFormTitle">Tambah Paket Baru</h3>
                        <button onclick="closePaketModal()"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nama Paket <span class="req">*</span></label>
                                <input type="text" placeholder="Paket A" />
                            </div>
                            <div class="form-group">
                                <label>Jumlah Porsi <span class="req">*</span></label>
                                <input type="number" placeholder="50" />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Harga <span class="req">*</span></label>
                                <input type="number" placeholder="1500000" />
                            </div>
                            <div class="form-group">
                                <label>Tandai Populer</label>
                                <select>
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya (Tampilkan badge POPULER)</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Paket</label>
                            <textarea rows="2" placeholder="Deskripsi singkat paket..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Menu Termasuk <span class="req">*</span></label>
                            <textarea rows="3" placeholder="Rendang, Ayam Pop, Dendeng Balado, ..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Gambar Paket</label>
                            <div class="upload-box" onclick="document.getElementById('paketImg').click()">
                                <i class="fa fa-cloud-arrow-up"></i>
                                <p>Klik untuk upload gambar</p>
                                <span>JPG, PNG (Maks. 2MB)</span>
                                <input type="file" id="paketImg" accept="image/*" style="display:none" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button class="btn-cancel" onclick="closePaketModal()">Batal</button>
                            <button class="btn-save" onclick="savePaket()">
                                <i class="fa fa-save"></i> Simpan Paket
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
        let paketList = [];

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
            loadPaketAdmin();
        });

        async function loadPaketAdmin() {
            try {
                const response = await fetch(`${API_BASE}/catering.php`, { credentials: 'same-origin' });
                if (!response.ok) throw new Error('Gagal memuat data paket catering');
                paketList = await response.json();
                renderPaketGrid();
            } catch (err) {
                console.error(err);
            }
        }

        function renderPaketGrid() {
            const grid = document.querySelector('.paket-admin-grid');
            if (!grid || !Array.isArray(paketList) || paketList.length === 0) return;

            grid.innerHTML = paketList.map(pak => {
                const imgSrc = pak.gambar ? `../${pak.gambar}` : '../img/diskon20_.jpg';
                const hargaFormatted = Number(pak.harga).toLocaleString('id-ID');

                return `
                    <div class="paket-admin-card">
                        <div class="pak-img">
                            <img src="${imgSrc}" alt="${pak.nama_paket}" />
                            ${pak.kode_paket === 'A' ? '<span class="pak-populer">POPULER</span>' : ''}
                        </div>
                        <div class="pak-body">
                            <div class="pak-title-row">
                                <h3>${pak.nama_paket}</h3>
                                <span class="pak-price">Rp${hargaFormatted}</span>
                            </div>
                            <p class="pak-porsi"><i class="fa fa-users"></i> ${pak.porsi}</p>
                            <p class="pak-desc">${pak.deskripsi || 'Paket hidangan khas Padang lezat dan komplit.'}</p>
                            <div class="pak-footer">
                                <label class="toggle-switch">
                                    <input type="checkbox" checked onchange="togglePaket(this)" />
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label">Aktif</span>
                                <div class="action-btns" style="margin-left:auto;">
                                    <button class="btn-act edit" onclick="openPaketForm('edit')" title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function openPaketForm(mode = 'add') {
            document.getElementById('paketFormTitle').textContent =
                mode === 'add' ? 'Tambah Paket Baru' : 'Edit Paket';
            document.getElementById('paketModal').style.display = 'flex';
        }

        function closePaketModal() {
            document.getElementById('paketModal').style.display = 'none';
        }

        function hapusPaket(btn) {
            if (confirm('Yakin hapus paket ini?')) {
                btn.closest('.paket-admin-card').remove();
            }
        }

        function togglePaket(cb) {
            const label = cb.closest('.pak-footer').querySelector('.toggle-label');
            label.textContent = cb.checked ? 'Aktif' : 'Nonaktif';
        }

        function savePaket() {
            alert('Paket berhasil diperbarui!');
            closePaketModal();
        }
    </script>
</body>

</html>
