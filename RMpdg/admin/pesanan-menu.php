<?php require_once __DIR__ . '/_guard.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pesanan Menu - Admin RM Padang</title>
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
            <a href="pesanan-menu.php" class="nav-item active"><i class="fa fa-shopping-cart"></i> Pesanan Menu</a>
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
                    <h2>Pesanan Menu</h2>
                    <span>Kelola semua pesanan menu makanan harian</span>
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
                    <select id="filterStatus" onchange="filterOrders()">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                    <select id="filterType" onchange="filterOrders()">
                        <option value="">Semua Layanan</option>
                        <option value="dinein">Makan di Tempat</option>
                        <option value="takeaway">Bawa Pulang</option>
                        <option value="delivery">Delivery</option>
                    </select>
                </div>
                <div class="toolbar-right">
                    <button onclick="exportToCSV()" style="background:var(--primary);color:#fff;border:none;padding:8px 16px;border-radius:6px;font-weight:700;cursor:pointer;font-size:0.85rem;display:inline-flex;align-items:center;gap:6px;margin-right:8px;">
                        <i class="fa fa-file-excel"></i> Export Excel
                    </button>
                    <div class="search-box">
                        <i class="fa fa-search"></i>
                        <input type="text" id="searchOrder" placeholder="Cari nama pemesan..." oninput="filterOrders()" />
                    </div>
                </div>
            </div>

            <div class="dash-card">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Nama Pemesan</th>
                                <th>Layanan</th>
                                <th>Lokasi / Meja</th>
                                <th>Waktu</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="ordersBody">
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-overlay" id="modalOverlay" onclick="closeModal()">
                <div class="modal-box" onclick="event.stopPropagation()">
                    <div class="modal-header">
                        <h3 id="modalTitle">Detail Pesanan</h3>
                        <button onclick="closeModal()"><i class="fa fa-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                            <div><span>Nama Pemesan</span><strong id="mNama">-</strong></div>
                            <div><span>Telepon / WA</span><strong id="mTelp">-</strong></div>
                            <div><span>Layanan</span><strong id="mLayanan">-</strong></div>
                            <div><span>Nomor Meja / Alamat</span><strong id="mLokasi">-</strong></div>
                            <div><span>Total Bayar</span><strong id="mTotal" style="color:var(--primary)">-</strong></div>
                            <div><span>Metode Pembayaran</span><strong id="mMetode">-</strong></div>
                            <div class="full-col" style="grid-column: 1/-1;"><span>Bukti Pembayaran</span><strong id="mBukti">-</strong></div>
                            <div class="full-col" style="grid-column: 1/-1;"><span>Catatan</span><strong id="mCatatan">-</strong></div>
                        </div>

                        <div style="margin-top: 16px;">
                            <span style="font-size:0.8rem; color:var(--text-gray); font-weight:600; display:block; margin-bottom:8px;">DAFTAR MENU YANG DIPESAN</span>
                            <div id="mItemsList" style="border:1px solid #eee; border-radius:8px; padding:10px; background:#fcfcfc;">
                            </div>
                        </div>

                        <div class="modal-status-update" style="margin-top: 20px; display:flex; align-items:center; gap:10px; padding-top:15px; border-top:1px solid #eee;">
                            <label style="font-weight:700;">Update Status:</label>
                            <select id="modalStatus" style="padding:8px 12px; border-radius:6px; border:1px solid #ddd; flex:1;">
                                <option value="pending">Pending</option>
                                <option value="diproses">Diproses</option>
                                <option value="selesai">Selesai</option>
                                <option value="dibatalkan">Dibatalkan</option>
                            </select>
                            <button class="btn-save-status" onclick="saveStatus()" style="background:var(--primary); color:white; border:none; padding:8px 16px; border-radius:6px; font-weight:700; cursor:pointer;">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="../js/main.js"></script>
    <script>
        const API_BASE = window.API_BASE || (window.location.pathname.includes('/MPTI/') ? '/MPTI/rmpdg-backend/api' : '/rmpdg-backend/api');

        let orders = [];
        let selectedOrderIndex = -1;

        async function doLogout() {
            if (!confirm('Yakin ingin logout?')) return;
            try {
                await fetch(`${API_BASE}/logout.php`, { credentials: 'same-origin' });
            } catch(e){}
            window.location.href = 'login.php';
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadOrders();
        });

        async function loadOrders() {
            try {
                const response = await fetch(`${API_BASE}/pesanan_menu.php`, { credentials: 'same-origin' });
                if (!response.ok) throw new Error('Gagal memuat data pesanan dari server');
                orders = await response.json();
                renderOrdersTable();
            } catch (err) {
                console.error(err);
                const tbody = document.getElementById('ordersBody');
                tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; padding:30px; color:#c0392b;">Gagal memuat data dari database: ${err.message}</td></tr>`;
            }
        }

        function renderOrdersTable() {
            const tbody = document.getElementById('ordersBody');
            if (!orders || orders.length === 0) {
                tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; padding:30px; color:var(--text-gray);">Belum ada pesanan menu masuk.</td></tr>`;
                return;
            }

            tbody.innerHTML = orders.map((order, idx) => {
                let badgeClass = 'pending';
                let labelStatus = 'Pending';
                if (order.status === 'diproses') { badgeClass = 'process'; labelStatus = 'Diproses'; }
                else if (order.status === 'selesai') { badgeClass = 'done'; labelStatus = 'Selesai'; }
                else if (order.status === 'dibatalkan') { badgeClass = 'cancel'; labelStatus = 'Dibatalkan'; }

                let typeLabel = 'Makan di Tempat';
                if (order.order_type === 'takeaway') typeLabel = 'Bawa Pulang';
                else if (order.order_type === 'delivery') typeLabel = 'Delivery';

                const waktuFormatted = order.waktu_daftar ? new Date(order.waktu_daftar).toLocaleString('id-ID', {
                    day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'
                }) : '-';

                return `
                    <tr data-status="${order.status}" data-type="${order.order_type}">
                        <td>${order.kode_pesanan || '#' + order.id}</td>
                        <td>
                            <strong>${order.nama_pemesan}</strong><br/>
                            <small>${order.telepon}</small>
                        </td>
                        <td><span style="font-weight:600;">${typeLabel}</span></td>
                        <td><span style="font-size:0.88rem;">${order.lokasi}</span></td>
                        <td><small>${waktuFormatted}</small></td>
                        <td><strong style="color:var(--primary)">Rp${Number(order.total).toLocaleString('id-ID')}</strong></td>
                        <td><span class="badge ${badgeClass}">${labelStatus}</span></td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-act view" onclick="viewOrder(${idx})" title="Detail">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <button class="btn-act delete" onclick="deleteOrder(${order.id})" title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function filterOrders() {
            const status = document.getElementById('filterStatus').value;
            const type = document.getElementById('filterType').value;
            const kw = document.getElementById('searchOrder').value.toLowerCase();

            document.querySelectorAll('#ordersBody tr').forEach(row => {
                const rowStatus = row.dataset.status || '';
                const rowType = row.dataset.type || '';
                const rowText = row.textContent.toLowerCase();

                const matchStatus = !status || rowStatus === status;
                const matchType = !type || rowType === type;
                const matchKw = !kw || rowText.includes(kw);

                row.style.display = (matchStatus && matchType && matchKw) ? '' : 'none';
            });
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

        function viewOrder(idx) {
            selectedOrderIndex = idx;
            const order = orders[idx];

            document.getElementById('modalTitle').textContent = `Detail Pesanan ${order.kode_pesanan || '#' + order.id}`;
            document.getElementById('mNama').textContent = order.nama_pemesan;
            document.getElementById('mTelp').textContent = order.telepon;
            
            let typeLabel = 'Makan di Tempat (Dine-in)';
            if (order.order_type === 'takeaway') typeLabel = 'Bawa Pulang (Takeaway)';
            else if (order.order_type === 'delivery') typeLabel = 'Kirim ke Alamat (Delivery)';
            
            document.getElementById('mLayanan').textContent = typeLabel;
            document.getElementById('mLokasi').textContent = order.lokasi;
            document.getElementById('mTotal').textContent = `Rp${Number(order.total).toLocaleString('id-ID')}`;
            document.getElementById('mMetode').textContent = order.metode_bayar;
            document.getElementById('mCatatan').textContent = order.catatan || '-';

            const buktiEl = document.getElementById('mBukti');
            if (order.bukti_bayar) {
                const imgUrl = (window.location.pathname.includes('/MPTI/')) 
                    ? `/MPTI/rmpdg-backend/uploads/${order.bukti_bayar}` 
                    : `/rmpdg-backend/uploads/${order.bukti_bayar}`;
                buktiEl.innerHTML = `<a href="${imgUrl}" onclick="showBuktiModal('${imgUrl}'); return false;" style="color:var(--primary); font-weight:700;"><i class="fa fa-image"></i> Lihat Bukti Transfer ↗</a>`;
            } else {
                buktiEl.textContent = 'Belum diupload';
            }

            const itemsContainer = document.getElementById('mItemsList');
            if (order.items && order.items.length > 0) {
                itemsContainer.innerHTML = order.items.map(item => `
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; border-bottom:1px solid #f0f0f0; padding-bottom:6px;">
                        <div>
                            <strong style="font-size:0.9rem;">${item.nama_menu}</strong><br/>
                            <span style="font-size:0.8-rem; color:var(--text-gray);">${item.qty} porsi x Rp${Number(item.harga_satuan).toLocaleString('id-ID')}</span>
                        </div>
                        <strong style="font-size:0.9rem; color:var(--primary)">Rp${Number(item.subtotal).toLocaleString('id-ID')}</strong>
                    </div>
                `).join('');
            } else {
                itemsContainer.innerHTML = '<p style="font-size:0.85rem; color:#888;">Tidak ada rincian item.</p>';
            }

            document.getElementById('modalStatus').value = order.status;
            document.getElementById('modalOverlay').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('modalOverlay').style.display = 'none';
        }

        async function saveStatus() {
            if (selectedOrderIndex === -1) return;
            const orderId = orders[selectedOrderIndex].id;
            const newStatus = document.getElementById('modalStatus').value;

            try {
                const response = await fetch(`${API_BASE}/pesanan_menu.php?id=${orderId}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    credentials: 'same-origin',
                    body: JSON.stringify({ status: newStatus })
                });

                const result = await response.json();
                if (!response.ok) throw new Error(result.error || 'Gagal memperbarui status');

                alert('Status pesanan berhasil diperbarui!');
                closeModal();
                loadOrders();
            } catch (err) {
                alert('Terjadi kesalahan: ' + err.message);
            }
        }

        async function deleteOrder(id) {
            if (confirm('Yakin ingin menghapus data pesanan ini?')) {
                try {
                    const response = await fetch(`${API_BASE}/pesanan_menu.php?id=${id}`, {
                        method: 'DELETE',
                        credentials: 'same-origin'
                    });

                    const result = await response.json();
                    if (!response.ok) throw new Error(result.error || 'Gagal menghapus pesanan');

                    loadOrders();
                } catch (err) {
                    alert('Terjadi kesalahan: ' + err.message);
                }
            }
        }

        function exportToCSV() {
            if (!orders || orders.length === 0) {
                alert('Tidak ada data pesanan untuk diekspor.');
                return;
            }

            const headers = ['Kode Pesanan', 'Nama Pemesan', 'Telepon', 'Layanan', 'Lokasi/Meja', 'Total (Rp)', 'Metode Bayar', 'Status', 'Waktu Pesan', 'Item Pesanan'];
            const rows = orders.map(order => {
                const items = (order.items && order.items.length > 0)
                    ? order.items.map(i => `${i.nama_menu} x${i.qty}`).join('; ')
                    : '-';
                return [
                    order.kode_pesanan || ('#' + order.id),
                    `"${(order.nama_pemesan || '').replace(/"/g, '""')}"`,
                    order.telepon || '-',
                    order.order_type || '-',
                    `"${(order.lokasi || '').replace(/"/g, '""')}"`,
                    Number(order.total) || 0,
                    order.metode_bayar || '-',
                    order.status || '-',
                    order.waktu_daftar || '-',
                    `"${items.replace(/"/g, '""')}"`,
                ].join(',');
            });

            const csvContent = [headers.join(','), ...rows].join('\n');
            const BOM = '\uFEFF'; // UTF-8 BOM agar Excel baca karakter Indonesia dengan benar
            const blob = new Blob([BOM + csvContent], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            const tanggal = new Date().toISOString().slice(0, 10);
            link.setAttribute('href', url);
            link.setAttribute('download', `pesanan_menu_${tanggal}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }
    </script>
</body>
</html>
