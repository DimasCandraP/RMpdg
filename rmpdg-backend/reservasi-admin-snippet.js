/**
 * CONTOH untuk admin/reservasi-admin.html
 * -----------------------------------------------------------------
 * Menggantikan RMData.getReservasi() dan RMData.updateStatusReservasi()
 * dengan pemanggilan API PHP.
 */

const API_BASE = 'http://localhost/rmpdg-backend/api';

// Ambil dan tampilkan semua data reservasi saat halaman dibuka
async function muatDataReservasi() {
  try {
    const response = await fetch(`${API_BASE}/reservasi.php`);
    const list = await response.json();

    const tbody = document.getElementById('tabelReservasi'); // sesuaikan id tabel di halaman kamu
    tbody.innerHTML = '';

    list.forEach(row => {
      const tr = document.createElement('tr');
      tr.dataset.status = row.status;
      tr.innerHTML = `
        <td>${row.kode_reservasi}</td>
        <td>${row.nama}</td>
        <td>${row.telepon}</td>
        <td>${row.tanggal} ${row.jam}</td>
        <td>${row.jumlah_tamu} orang</td>
        <td>${row.status}</td>
        <td>
          <select onchange="ubahStatus(${row.id}, this.value)">
            <option value="pending"      ${row.status === 'pending'      ? 'selected' : ''}>Pending</option>
            <option value="dikonfirmasi" ${row.status === 'dikonfirmasi' ? 'selected' : ''}>Dikonfirmasi</option>
            <option value="selesai"      ${row.status === 'selesai'      ? 'selected' : ''}>Selesai</option>
            <option value="dibatalkan"   ${row.status === 'dibatalkan'   ? 'selected' : ''}>Dibatalkan</option>
          </select>
        </td>
      `;
      tbody.appendChild(tr);
    });
  } catch (err) {
    alert('Gagal memuat data reservasi: ' + err.message);
  }
}

// Update status ketika admin memilih opsi baru di dropdown
async function ubahStatus(id, statusBaru) {
  try {
    const response = await fetch(`${API_BASE}/reservasi.php?id=${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ status: statusBaru })
    });
    const result = await response.json();
    if (!response.ok) throw new Error(result.error);
  } catch (err) {
    alert('Gagal mengubah status: ' + err.message);
  }
}

// Panggil saat halaman admin dimuat
document.addEventListener('DOMContentLoaded', muatDataReservasi);
