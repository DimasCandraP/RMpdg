<?php require_once __DIR__ . '/_guard.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reservasi - Admin RM Padang</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/admin.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    .skeleton {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: shimmer 1.4s infinite;
      border-radius: 6px;
    }
    @keyframes shimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

    #toast-container {
      position: fixed;
      bottom: 24px;
      right: 24px;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .toast {
      min-width: 260px;
      max-width: 340px;
      padding: 14px 18px;
      border-radius: 10px;
      font-size: 0.85rem;
      font-weight: 600;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 6px 24px rgba(0,0,0,0.18);
      animation: slideInToast 0.3s ease;
    }
    @keyframes slideInToast {
      from { opacity: 0; transform: translateX(60px); }
      to   { opacity: 1; transform: translateX(0); }
    }
    .toast.success { background: #27AE60; }
    .toast.error   { background: #E74C3C; }
    .toast.info    { background: #2980B9; }

    .refresh-indicator {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 0.78rem;
      color: var(--text-gray);
    }
    .refresh-dot {
      width: 8px;
      height: 8px;
      background: #27AE60;
      border-radius: 50%;
      animation: pulse-dot 2s infinite;
    }
    @keyframes pulse-dot {
      0%, 100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.5; transform: scale(0.7); }
    }

    .badge.pending   { background: #FEF0E6; color: #E67E22; }
    .badge.confirmed { background: #E8F8F0; color: #27AE60; }
    .badge.done      { background: #FFF0F0; color: var(--primary); }
    .badge.cancel    { background: #F5F5F5; color: #95A5A6; }

    .sc-num { transition: all 0.4s ease; }

    #lastUpdated {
      font-size: 0.73rem;
      color: #aaa;
      margin-left: 4px;
    }

    .btn-status-quick {
      font-size: 0.7rem;
      padding: 3px 8px;
      border-radius: 5px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.2s;
    }
    .btn-status-quick.confirm {
      background: #E8F8F0;
      color: #27AE60;
    }
    .btn-status-quick.confirm:hover { background: #27AE60; color: #fff; }
    .btn-status-quick.cancel {
      background: #FEF0E6;
      color: #E67E22;
    }
    .btn-status-quick.cancel:hover { background: #E67E22; color: #fff; }
  </style>
</head>

<body class="admin-body">

  <div id="toast-container"></div>

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
      <a href="pesanan-menu.php" class="nav-item"><i class="fa fa-shopping-cart"></i> Pesanan Menu</a>
      <a href="reservasi-admin.php" class="nav-item active"><i class="fa fa-calendar-check"></i> Reservasi</a>
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
          <h2>Kelola Reservasi</h2>
          <span>Daftar reservasi meja dari pelanggan</span>
        </div>
      </div>
      <div class="topbar-right" style="display:flex;align-items:center;gap:16px;">
        <div class="refresh-indicator">
          <div class="refresh-dot"></div>
          <span>Real-time</span>
          <span id="lastUpdated"></span>
        </div>
        <button onclick="loadReservasi()" style="background:none;border:1.5px solid #ddd;border-radius:7px;padding:6px 12px;font-size:0.8rem;cursor:pointer;color:var(--text-gray);display:flex;align-items:center;gap:6px;" id="refreshBtn">
          <i class="fa fa-rotate-right"></i> Refresh
        </button>
        <a href="../index.html" target="_blank" class="btn-view-web">
          <i class="fa fa-eye"></i> Lihat Website
        </a>
      </div>
    </header>

    <div class="admin-content">

      <div class="stat-cards">
        <div class="stat-card red">
          <div class="sc-icon"><i class="fa fa-clock"></i></div>
          <div class="sc-info">
            <span class="sc-num" id="statPending">—</span>
            <span class="sc-label">Pending</span>
            <span class="sc-sub">Butuh konfirmasi</span>
          </div>
        </div>
        <div class="stat-card gold">
          <div class="sc-icon"><i class="fa fa-check-circle"></i></div>
          <div class="sc-info">
            <span class="sc-num" id="statDikonfirmasi">—</span>
            <span class="sc-label">Dikonfirmasi</span>
            <span class="sc-sub">Siap dilayani</span>
          </div>
        </div>
        <div class="stat-card green">
          <div class="sc-icon"><i class="fa fa-calendar-day"></i></div>
          <div class="sc-info">
            <span class="sc-num" id="statBulanIni">—</span>
            <span class="sc-label">Total Bulan Ini</span>
            <span class="sc-sub" id="statBulanLabel">—</span>
          </div>
        </div>
        <div class="stat-card blue">
          <div class="sc-icon"><i class="fa fa-users"></i></div>
          <div class="sc-info">
            <span class="sc-num" id="statTamu">—</span>
            <span class="sc-label">Total Tamu</span>
            <span class="sc-sub">Bulan ini</span>
          </div>
        </div>
      </div>

      <div class="table-toolbar">
        <div class="toolbar-left" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
          <select id="filterBulanRes" onchange="onBulanFilterChange()" style="border:1.5px solid #ddd;border-radius:7px;padding:7px 12px;font-size:0.82rem;outline:none;font-weight:600;background:#fff;">
            <option value="current">Bulan Ini</option>
            <option value="all">Semua Periode</option>
          </select>
          <select id="filterStatusRes" onchange="filterReservasi()" style="border:1.5px solid #ddd;border-radius:7px;padding:7px 12px;font-size:0.82rem;outline:none;background:#fff;">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="dikonfirmasi">Dikonfirmasi</option>
            <option value="selesai">Selesai</option>
            <option value="dibatalkan">Dibatalkan</option>
          </select>
          <input type="date" id="filterTanggal" onchange="filterReservasi()"
            style="border:1.5px solid #ddd;border-radius:7px;padding:7px 12px;font-size:0.82rem;outline:none;" />
        </div>
        <div class="toolbar-right" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
          <button onclick="exportToCSV()" style="background:#27AE60;color:#fff;border:none;border-radius:7px;padding:7px 14px;font-size:0.82rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;box-shadow:0 2px 6px rgba(39,174,96,0.25);" title="Unduh rekap reservasi format Excel/CSV">
            <i class="fa fa-file-excel"></i> Export Excel/CSV
          </button>
          <div class="search-box">
            <i class="fa fa-search"></i>
            <input type="text" id="searchRes" placeholder="Cari nama, telepon, kode..." oninput="filterReservasi()" />
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
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Jumlah Tamu</th>
                <th>Jenis Acara</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="resBody">
              <tr><td colspan="8" style="padding:40px;text-align:center;">
                <div style="display:flex;flex-direction:column;gap:10px;align-items:center;">
                  <div class="skeleton" style="height:16px;width:60%;"></div>
                  <div class="skeleton" style="height:16px;width:45%;"></div>
                  <div class="skeleton" style="height:16px;width:50%;"></div>
                </div>
              </td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="modal-overlay" id="resModal" onclick="closeResModal()">
        <div class="modal-box" onclick="event.stopPropagation()" style="max-width:560px;">
          <div class="modal-header">
            <h3><i class="fa fa-calendar-check" style="margin-right:8px;font-size:0.95rem;"></i>Detail Reservasi</h3>
            <button onclick="closeResModal()"><i class="fa fa-times"></i></button>
          </div>
          <div class="modal-body">
            <div class="modal-grid" id="resModalContent"></div>
            <div class="modal-status-update">
              <label><i class="fa fa-tag" style="color:var(--primary);margin-right:4px;"></i>Update Status:</label>
              <select id="resModalStatus">
                <option value="pending">⏳ Pending</option>
                <option value="dikonfirmasi">✅ Dikonfirmasi</option>
                <option value="selesai">🏁 Selesai</option>
                <option value="dibatalkan">❌ Dibatalkan</option>
              </select>
              <button class="btn-save-status" onclick="saveResStatus()">
                <i class="fa fa-save"></i> Simpan
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

    let reservasiList = [];
    let selectedResIndex = -1;
    let autoRefreshTimer = null;

    async function doLogout() {
      if (!confirm('Yakin ingin logout?')) return;
      try {
        await fetch(`${API_BASE}/logout.php`, { credentials: 'same-origin' });
      } catch(e){}
      window.location.href = 'login.php';
    }

    document.addEventListener('DOMContentLoaded', () => {
      loadReservasi();
      startAutoRefresh();
    });

    function startAutoRefresh() {
      if (autoRefreshTimer) clearInterval(autoRefreshTimer);
      autoRefreshTimer = setInterval(() => {
        loadReservasi(true);
      }, 30000);
    }

    async function loadReservasi(silent = false) {
      const btn = document.getElementById('refreshBtn');
      if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fa fa-rotate-right fa-spin"></i> Memuat...';
      }

      if (!silent) {
        document.getElementById('resBody').innerHTML = `
          <tr><td colspan="8" style="padding:40px;text-align:center;">
            <div style="display:flex;flex-direction:column;gap:10px;align-items:center;">
              <div class="skeleton" style="height:16px;width:60%;"></div>
              <div class="skeleton" style="height:16px;width:45%;"></div>
              <div class="skeleton" style="height:16px;width:50%;"></div>
            </div>
          </td></tr>`;
      }

      try {
        const response = await fetch(`${API_BASE}/reservasi.php?_t=${Date.now()}`, { credentials: 'same-origin' });
        if (!response.ok) throw new Error(`Server error: ${response.status}`);
        reservasiList = await response.json();
        populateMonthDropdown();
        updateStatCards();
        filterReservasi();
        updateLastUpdated();
      } catch (err) {
        console.error(err);
        document.getElementById('resBody').innerHTML = `
          <tr><td colspan="8" style="text-align:center;padding:40px;color:#c0392b;">
            <i class="fa fa-exclamation-triangle" style="font-size:1.5rem;margin-bottom:8px;display:block;"></i>
            Gagal memuat data: <strong>${err.message}</strong><br>
            <small style="color:#999;margin-top:6px;display:block;">Pastikan database <code>rmpdg_db</code> sudah diimport dan MySQL aktif.</small>
            <button onclick="loadReservasi()" style="margin-top:12px;padding:8px 16px;background:var(--primary);color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:0.82rem;">
              <i class="fa fa-rotate-right"></i> Coba Lagi
            </button>
          </td></tr>`;
      } finally {
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = '<i class="fa fa-rotate-right"></i> Refresh';
        }
      }
    }

    function populateMonthDropdown() {
      const select = document.getElementById('filterBulanRes');
      if (!select) return;
      const currentSelection = select.value;

      const months = new Set();
      reservasiList.forEach(r => {
        if (r.tanggal && r.tanggal.length >= 7) {
          months.add(r.tanggal.substring(0, 7)); // format: YYYY-MM
        }
      });

      const sortedMonths = Array.from(months).sort().reverse();
      let html = '<option value="current">Bulan Ini</option><option value="all">Semua Periode</option>';
      sortedMonths.forEach(m => {
        const [year, month] = m.split('-');
        const dateObj = new Date(year, month - 1, 1);
        const label = dateObj.toLocaleString('id-ID', { month: 'long', year: 'numeric' });
        html += `<option value="${m}">${label}</option>`;
      });

      select.innerHTML = html;
      if (currentSelection) select.value = currentSelection;
    }

    function onBulanFilterChange() {
      updateStatCards();
      filterReservasi();
    }

    function updateStatCards() {
      const select = document.getElementById('filterBulanRes');
      const selectedVal = select ? select.value : 'current';
      
      const now = new Date();
      const currentMonthStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;
      
      let targetMonthStr = currentMonthStr;
      let monthLabelText = now.toLocaleString('id-ID', { month: 'long', year: 'numeric' });

      if (selectedVal === 'all') {
        targetMonthStr = null;
        monthLabelText = 'Semua Periode';
      } else if (selectedVal && selectedVal !== 'current') {
        targetMonthStr = selectedVal;
        const [year, month] = selectedVal.split('-');
        const dateObj = new Date(year, month - 1, 1);
        monthLabelText = dateObj.toLocaleString('id-ID', { month: 'long', year: 'numeric' });
      }

      const pending       = reservasiList.filter(r => r.status === 'pending').length;
      const dikonfirmasi  = reservasiList.filter(r => r.status === 'dikonfirmasi').length;
      
      // Filter data periode terpilih yang valid (mengabaikan status dibatalkan)
      const validData = reservasiList.filter(r => {
        const isNotCancelled = r.status !== 'dibatalkan';
        const isTargetMonth  = !targetMonthStr || (r.tanggal && r.tanggal.startsWith(targetMonthStr));
        return isNotCancelled && isTargetMonth;
      });

      const totalBulanIni = validData.length;
      const totalTamu     = validData.reduce((sum, r) => sum + (parseInt(r.jumlah_tamu) || 0), 0);

      animateNumber('statPending',       pending);
      animateNumber('statDikonfirmasi',  dikonfirmasi);
      animateNumber('statBulanIni',      totalBulanIni);
      animateNumber('statTamu',          totalTamu);
      document.getElementById('statBulanLabel').textContent = monthLabelText;
    }

    function animateNumber(id, target) {
      const el = document.getElementById(id);
      if (!el) return;
      const current = parseInt(el.textContent) || 0;
      if (current === target) return;
      let start = current;
      const step = target > start ? 1 : -1;
      const interval = setInterval(() => {
        start += step;
        el.textContent = start;
        if (start === target) clearInterval(interval);
      }, 30);
    }

    function renderReservasiTable(list = null) {
      const data = list || reservasiList;
      const tbody = document.getElementById('resBody');

      if (!data || data.length === 0) {
        tbody.innerHTML = `
          <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-gray);">
            <i class="fa fa-calendar-xmark" style="font-size:2rem;color:#ddd;display:block;margin-bottom:8px;"></i>
            Belum ada data reservasi.
          </td></tr>`;
        return;
      }

      tbody.innerHTML = data.map((row, idx) => {
        const realIdx = reservasiList.indexOf(row);
        let badgeClass = 'pending', labelStatus = 'Pending';
        if (row.status === 'dikonfirmasi') { badgeClass = 'confirmed'; labelStatus = 'Dikonfirmasi'; }
        else if (row.status === 'selesai') { badgeClass = 'done';      labelStatus = 'Selesai'; }
        else if (row.status === 'dibatalkan') { badgeClass = 'cancel'; labelStatus = 'Dibatalkan'; }

        let tglDisplay = row.tanggal || '-';
        try {
          const d = new Date(row.tanggal);
          tglDisplay = d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
        } catch(e) {}

        let quickActions = '';
        if (row.status === 'pending') {
          quickActions = `
            <button class="btn-status-quick confirm" onclick="quickUpdateStatus(${row.id}, 'dikonfirmasi', ${realIdx})" title="Konfirmasi">
              <i class="fa fa-check"></i> Konfirmasi
            </button>`;
        } else if (row.status === 'dikonfirmasi') {
          quickActions = `
            <button class="btn-status-quick confirm" onclick="quickUpdateStatus(${row.id}, 'selesai', ${realIdx})" title="Selesai">
              <i class="fa fa-flag-checkered"></i> Selesai
            </button>`;
        }

        return `
          <tr data-status="${row.status}" data-tanggal="${row.tanggal}" style="transition:background 0.3s;">
            <td style="font-family:monospace;font-size:0.8rem;color:#999;">${row.kode_reservasi || '#' + String(row.id).padStart(3,'0')}</td>
            <td>
              <strong>${escHtml(row.nama)}</strong><br />
              <small style="color:var(--text-gray);">${escHtml(row.telepon)}</small>
            </td>
            <td>${tglDisplay}</td>
            <td>${row.jam || '-'}</td>
            <td><strong>${row.jumlah_tamu}</strong> <small style="color:#999;">orang</small></td>
            <td>${escHtml(row.jenis_acara || 'Makan Biasa')}</td>
            <td><span class="badge ${badgeClass}">${labelStatus}</span></td>
            <td>
              <div style="display:flex;flex-direction:column;gap:5px;align-items:flex-start;">
                <div class="action-btns">
                  <button class="btn-act view" onclick="viewRes(${realIdx})" title="Detail"><i class="fa fa-eye"></i></button>
                  <button class="btn-act delete" onclick="deleteRes(${row.id})" title="Hapus"><i class="fa fa-trash"></i></button>
                </div>
                ${quickActions}
              </div>
            </td>
          </tr>`;
      }).join('');
    }

    function filterReservasi() {
      const bulanSelect = document.getElementById('filterBulanRes');
      const bulanVal    = bulanSelect ? bulanSelect.value : 'current';
      const status     = document.getElementById('filterStatusRes').value;
      const tanggal    = document.getElementById('filterTanggal').value;
      const kw         = document.getElementById('searchRes').value.toLowerCase().trim();

      const now = new Date();
      const currentMonthStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;

      const filtered = reservasiList.filter(row => {
        let matchBulan = true;
        if (bulanVal === 'current') {
          matchBulan = row.tanggal && row.tanggal.startsWith(currentMonthStr);
        } else if (bulanVal && bulanVal !== 'all') {
          matchBulan = row.tanggal && row.tanggal.startsWith(bulanVal);
        }

        const matchStatus  = !status  || row.status === status;
        const matchTanggal = !tanggal || row.tanggal === tanggal;
        const matchKw      = !kw      || (row.nama && row.nama.toLowerCase().includes(kw)) ||
                                        (row.telepon && row.telepon.includes(kw)) ||
                                        (row.kode_reservasi && row.kode_reservasi.toLowerCase().includes(kw));
        return matchBulan && matchStatus && matchTanggal && matchKw;
      });

      window.currentFilteredReservasi = filtered;
      renderReservasiTable(filtered);
    }

    function exportToCSV() {
      const dataToExport = window.currentFilteredReservasi || reservasiList;
      if (!dataToExport || dataToExport.length === 0) {
        showToast('Tidak ada data reservasi untuk diekspor!', 'error');
        return;
      }

      let csvContent = "\uFEFF"; // UTF-8 BOM agar rapi di Excel
      csvContent += "Kode Reservasi,Nama Pemesan,Nomor Telepon,Email,Tanggal,Jam,Jumlah Tamu,Jenis Acara,Status,Catatan\n";

      dataToExport.forEach(r => {
        const kode    = `"${(r.kode_reservasi || '#' + String(r.id).padStart(3,'0')).replace(/"/g, '""')}"`;
        const nama    = `"${(r.nama || '').replace(/"/g, '""')}"`;
        const telp    = `"${(r.telepon || '').replace(/"/g, '""')}"`;
        const email   = `"${(r.email || '').replace(/"/g, '""')}"`;
        const tgl     = `"${(r.tanggal || '').replace(/"/g, '""')}"`;
        const jam     = `"${(r.jam || '').replace(/"/g, '""')}"`;
        const tamu    = r.jumlah_tamu || 0;
        const acara   = `"${(r.jenis_acara || '').replace(/"/g, '""')}"`;
        const status  = `"${(r.status || '').replace(/"/g, '""')}"`;
        const catatan = `"${(r.catatan || '').replace(/\r?\n|\r/g, ' ').replace(/"/g, '""')}"`;

        csvContent += `${kode},${nama},${telp},${email},${tgl},${jam},${tamu},${acara},${status},${catatan}\n`;
      });

      const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
      const url = URL.createObjectURL(blob);
      const todayStr = new Date().toISOString().slice(0, 10);

      const link = document.createElement('a');
      link.setAttribute('href', url);
      link.setAttribute('download', `rekap_reservasi_RM_Padang_${todayStr}.csv`);
      link.style.visibility = 'hidden';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      showToast('Berhasil mengunduh rekap reservasi (Excel/CSV)!', 'success');
    }

    function viewRes(idx) {
      selectedResIndex = idx;
      const row = reservasiList[idx];
      if (!row) return;

      let tglDisplay = row.tanggal || '-';
      try { tglDisplay = new Date(row.tanggal).toLocaleDateString('id-ID', { weekday:'long', day:'2-digit', month:'long', year:'numeric' }); } catch(e) {}

      const statusLabels = { pending:'⏳ Pending', dikonfirmasi:'✅ Dikonfirmasi', selesai:'🏁 Selesai', dibatalkan:'❌ Dibatalkan' };

      let buktiHtml = '-';
      if (row.bukti_bayar) {
        const imgUrl = `${API_BASE}/../uploads/${row.bukti_bayar}`;
        buktiHtml = `<a href="${imgUrl}" target="_blank" style="color:var(--primary);font-weight:700;text-decoration:underline;"><i class="fa fa-image"></i> Lihat Foto Bukti</a>`;
      }

      document.getElementById('resModalContent').innerHTML = `
        <div><span>ID / Kode</span><strong>${row.kode_reservasi || '#' + String(row.id).padStart(3,'0')}</strong></div>
        <div><span>Status</span><strong>${statusLabels[row.status] || row.status}</strong></div>
        <div><span>Nama Pemesan</span><strong>${escHtml(row.nama)}</strong></div>
        <div><span>Telepon</span><strong>${escHtml(row.telepon)}</strong></div>
        <div><span>Email</span><strong>${escHtml(row.email || '-')}</strong></div>
        <div><span>Tanggal & Jam</span><strong>${tglDisplay} | ${row.jam || '-'}</strong></div>
        <div><span>Jumlah Tamu</span><strong>${row.jumlah_tamu} orang</strong></div>
        <div><span>Jenis Acara</span><strong>${escHtml(row.jenis_acara || 'Makan Biasa')}</strong></div>
        <div><span>Metode Bayar</span><strong>${escHtml(row.metode_bayar || 'QRIS')}</strong></div>
        <div><span>Bukti Pembayaran</span><strong>${buktiHtml}</strong></div>
        <div class="full-col" style="grid-column:1/-1;"><span>Catatan</span><strong>${escHtml(row.catatan || '-')}</strong></div>
        <div class="full-col" style="grid-column:1/-1;"><span>Waktu Daftar</span><strong>${row.waktu_daftar || '-'}</strong></div>
      `;
      document.getElementById('resModalStatus').value = row.status;
      document.getElementById('resModal').style.display = 'flex';
    }

    function closeResModal() {
      document.getElementById('resModal').style.display = 'none';
    }

    async function saveResStatus() {
      if (selectedResIndex === -1) return;
      const id        = reservasiList[selectedResIndex].id;
      const newStatus = document.getElementById('resModalStatus').value;

      try {
        const res = await fetch(`${API_BASE}/reservasi.php?id=${id}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          credentials: 'same-origin',
          body: JSON.stringify({ status: newStatus })
        });
        const result = await res.json();
        if (!res.ok) throw new Error(result.error || 'Gagal mengubah status');

        showToast('success', `Status berhasil diubah ke "${newStatus}"`);
        closeResModal();
        loadReservasi(true);
      } catch (err) {
        showToast('error', 'Gagal: ' + err.message);
      }
    }

    async function quickUpdateStatus(id, newStatus, idx) {
      try {
        const res = await fetch(`${API_BASE}/reservasi.php?id=${id}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          credentials: 'same-origin',
          body: JSON.stringify({ status: newStatus })
        });
        const result = await res.json();
        if (!res.ok) throw new Error(result.error || 'Gagal');

        showToast('success', `Reservasi #${id} → ${newStatus}`);
        loadReservasi(true);
      } catch (err) {
        showToast('error', 'Gagal: ' + err.message);
      }
    }

    async function deleteRes(id) {
      if (!confirm(`Yakin ingin menghapus reservasi #${id}?`)) return;
      try {
        const res = await fetch(`${API_BASE}/reservasi.php?id=${id}`, {
          method: 'DELETE',
          credentials: 'same-origin'
        });
        const result = await res.json();
        if (!res.ok) throw new Error(result.error || 'Gagal menghapus');

        showToast('success', `Reservasi #${id} berhasil dihapus`);
        loadReservasi(true);
      } catch (err) {
        showToast('error', 'Gagal: ' + err.message);
      }
    }

    function updateLastUpdated() {
      const now = new Date();
      document.getElementById('lastUpdated').textContent =
        `• ${now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })}`;
    }

    function escHtml(str) {
      if (!str) return '';
      return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function showToast(type, message) {
      const icons = { success:'fa-circle-check', error:'fa-circle-xmark', info:'fa-circle-info' };
      const container = document.getElementById('toast-container');
      const toast = document.createElement('div');
      toast.className = `toast ${type}`;
      toast.innerHTML = `<i class="fa ${icons[type] || 'fa-info'}"></i> ${message}`;
      container.appendChild(toast);
      setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(60px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
      }, 3500);
    }

    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('collapsed');
    }
  </script>
</body>

</html>
