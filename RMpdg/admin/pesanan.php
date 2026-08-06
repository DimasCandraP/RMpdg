<?php require_once __DIR__ . '/_guard.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pesanan Catering - Admin RM Padang</title>
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
            <a href="pesanan.php" class="nav-item active"><i class="fa fa-clipboard-list"></i> Pesanan Catering</a>
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
                    <h2>Pesanan Catering</h2>
                    <span>Kelola semua pesanan catering masuk</span>
                </div>
            </div>
            <div class="topbar-right">
                <a href="../index.html" target="_blank" class="btn-view-web">
                    <i class="fa fa-eye"></i> Lihat Website
                </a>
            </div>
        </header>

        <div class="admin-content">

            <div class="table-toolbar">
                <div class="toolbar-left">
                    <select id="filterStatus" onchange="filterPesanan()">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="dikonfirmasi">Dikonfirmasi</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                    <select id="filterPaket" onchange="filterPesanan()">
                        <option value="">Semua Paket</option>
                        <option value="Paket A">Paket A</option>
                        <option value="Paket B">Paket B</option>
                        <option value="Paket C">Paket C</option>
                    </select>
                </div>
                <div class="toolbar-right">
                    <div class="search-box">
                        <i class="fa fa-search"></i>
                        <input type="text" id="searchPesanan" placeholder="Cari nama pemesan..."
                            oninput="filterPesanan()" />
                    </div>
                </div>
            </div>

            <div class="dash-card">
                <div class="table-responsive">
                    <table class="admin-table" id="pesananTable">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Nama Pemesan</th>
                                <th>Paket</th>
                                <th>Tanggal Acara</th>
                                <th>Jumlah Tamu</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="pesananBody">
                            <tr><td colspan="7" style="text-align:center; padding:25px; color:#888;">Memuat data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-overlay" id="modalOverlay" onclick="closeModal()">
                <div class="modal-box" onclick="event.stopPropagation()">
                    <div class="modal-header">
                        <h3>Detail Pesanan Catering</h3>
                        <button onclick="closeModal()"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-grid">
                        </div>
                        <div class="modal-status-update">
                            <label>Update Status:</label>
                            <select id="modalStatus">
                                <option value="pending">Pending</option>
                                <option value="dikonfirmasi">Dikonfirmasi</option>
                                <option value="selesai">Selesai</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                            <button class="btn-save-status" onclick="saveStatus()">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="../js/main.js"></script>
    <script>
        const API_BASE = window.API_BASE || (window.location.pathname.includes('/MPTI/') ? '/MPTI/rmpdg-backend/api' : '/rmpdg-backend/api');

        let pesananCateringList = [];
        let selectedPesananIndex = -1;

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
            loadPesananCatering();
        });

        async function loadPesananCatering() {
            try {
                const response = await fetch(`${API_BASE}/catering.php?admin=1`, { credentials: 'same-origin' });
                if (!response.ok) throw new Error('Gagal memuat data pesanan catering');
                pesananCateringList = await response.json();
                renderPesananTable();
            } catch (err) {
                console.error(err);
                document.getElementById('pesananBody').innerHTML = `<tr><td colspan="7" style="text-align:center; padding:25px; color:#c0392b;">Gagal memuat data: ${err.message}</td></tr>`;
            }
        }

        function renderPesananTable() {
            const tbody = document.getElementById('pesananBody');
            if (!pesananCateringList || pesananCateringList.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding:25px; color:var(--text-gray);">Belum ada pesanan catering.</td></tr>`;
                return;
            }

            tbody.innerHTML = pesananCateringList.map((row, idx) => {
                let badgeClass = 'pending';
                let labelStatus = 'Pending';
                if (row.status === 'dikonfirmasi') { badgeClass = 'confirmed'; labelStatus = 'Dikonfirmasi'; }
                else if (row.status === 'selesai') { badgeClass = 'done'; labelStatus = 'Selesai'; }
                else if (row.status === 'dibatalkan') { badgeClass = 'cancel'; labelStatus = 'Dibatalkan'; }

                const tglFormatted = row.tanggal_acara || '-';

                return `
                    <tr data-status="${row.status}" data-paket="${row.nama_paket}">
                        <td>${row.kode_pesanan || '#' + row.id}</td>
                        <td>
                            <strong>${row.nama}</strong><br />
                            <small>${row.telepon}</small>
                        </td>
                        <td>${row.nama_paket || 'Paket'}</td>
                        <td>${tglFormatted}</td>
                        <td>${row.jumlah_tamu || 0} orang</td>
                        <td><span class="badge ${badgeClass}">${labelStatus}</span></td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-act view" onclick="viewPesanan(${idx})" title="Detail">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function filterPesanan() {
            const status = document.getElementById('filterStatus').value;
            const paket = document.getElementById('filterPaket').value;
            const kw = document.getElementById('searchPesanan').value.toLowerCase();
            document.querySelectorAll('#pesananBody tr').forEach(row => {
                const rowStatus = row.dataset.status || '';
                const rowPaket = row.dataset.paket || '';
                const rowText = row.textContent.toLowerCase();
                const m1 = !status || rowStatus === status;
                const m2 = !paket || rowPaket.includes(paket);
                const m3 = !kw || rowText.includes(kw);
                row.style.display = (m1 && m2 && m3) ? '' : 'none';
            });
        }

        function getUploadUrl(fileName) {
            if (!fileName) return '#';
            if (fileName.startsWith('http')) return fileName;
            const path = window.location.pathname;
            if (path.includes('/MPTI/')) return `/MPTI/rmpdg-backend/uploads/${fileName}`;
            return `/rmpdg-backend/uploads/${fileName}`;
        }

        function showBuktiModal(imgUrl) {
            let overlay = document.getElementById('buktiImageOverlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'buktiImageOverlay';
                overlay.style.cssText = 'position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.85); display:flex; align-items:center; justify-content:center; z-index:99999; flex-direction:column; padding:20px;';
                overlay.innerHTML = `
                    <div style="position:relative; max-width:90vw; max-height:85vh; text-align:center;">
                        <button onclick="document.getElementById('buktiImageOverlay').style.display='none'" style="position:absolute; top:-15px; right:-15px; background:#d32f2f; color:#fff; border:none; width:36px; height:36px; border-radius:50%; font-size:1.2rem; font-weight:bold; cursor:pointer; box-shadow:0 4px 12px rgba(0,0,0,0.3);">&times;</button>
                        <img id="buktiImageModalTag" src="" style="max-width:100%; max-height:75vh; border-radius:8px; box-shadow:0 8px 32px rgba(0,0,0,0.5); object-fit:contain; background:#fff;" />
                        <div style="margin-top:14px;">
                            <a id="buktiImageLinkTag" href="#" target="_blank" style="color:#fff; background:var(--primary,#8B0000); padding:8px 18px; border-radius:6px; text-decoration:none; font-weight:bold; font-size:0.88rem; display:inline-block;">
                                <i class="fa fa-external-link-alt"></i> Buka Gambar di Tab Baru ↗
                            </a>
                        </div>
                    </div>
                `;
                document.body.appendChild(overlay);
                overlay.addEventListener('click', (e) => {
                    if (e.target === overlay) overlay.style.display = 'none';
                });
            }
            document.getElementById('buktiImageModalTag').src = imgUrl;
            document.getElementById('buktiImageLinkTag').href = imgUrl;
            overlay.style.display = 'flex';
        }

        function viewPesanan(idx) {
            selectedPesananIndex = idx;
            const row = pesananCateringList[idx];

            document.querySelector('#modalOverlay h3').textContent = `Detail Pesanan Catering ${row.kode_pesanan || '#' + row.id}`;

            const grid = document.querySelector('#modalOverlay .modal-grid');
            const imgUrl = getUploadUrl(row.bukti_bayar);
            let buktiHtml = row.bukti_bayar ? `
                <div style="display:flex; align-items:center; gap:10px; margin-top:4px;">
                    <a href="${imgUrl}" onclick="showBuktiModal('${imgUrl}'); return false;" style="color:var(--primary); font-weight:700; text-decoration:underline; display:inline-flex; align-items:center; gap:5px;">
                        <i class="fa fa-image"></i> Lihat Bukti Payment ↗
                    </a>
                </div>
            ` : '<span style="color:#999;">Belum upload</span>';

            grid.innerHTML = `
                <div><span>Nama Pemesan</span><strong>${row.nama}</strong></div>
                <div><span>Telepon / WA</span><strong>${row.telepon}</strong></div>
                <div><span>Email</span><strong>${row.email || '-'}</strong></div>
                <div><span>Paket</span><strong>${row.nama_paket || 'Paket'}</strong></div>
                <div><span>Tanggal Acara</span><strong>${row.tanggal_acara}</strong></div>
                <div><span>Jenis Acara</span><strong>${row.jenis_acara || '-'}</strong></div>
                <div><span>Jumlah Tamu</span><strong>${row.jumlah_tamu || 0} orang</strong></div>
                <div><span>Bukti Transfer</span><strong>${buktiHtml}</strong></div>
                <div class="full-col"><span>Catatan</span><strong>${row.catatan || '-'}</strong></div>
            `;

            document.getElementById('modalStatus').value = row.status;
            document.getElementById('modalOverlay').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('modalOverlay').style.display = 'none';
        }

        async function saveStatus() {
            if (selectedPesananIndex === -1) return;
            const id = pesananCateringList[selectedPesananIndex].id;
            const newStatus = document.getElementById('modalStatus').value;

            try {
                const response = await fetch(`${API_BASE}/catering.php?id=${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'same-origin',
                    body: JSON.stringify({ status: newStatus })
                });

                const result = await response.json();
                if (!response.ok) throw new Error(result.error || 'Gagal mengubah status');

                alert('Status pesanan catering berhasil diupdate!');
                closeModal();
                loadPesananCatering();
            } catch (err) {
                alert('Terjadi kesalahan: ' + err.message);
            }
        }
    </script>
</body>

</html>
