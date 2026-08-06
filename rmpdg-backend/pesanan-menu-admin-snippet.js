/**
 * CONTOH untuk admin/pesanan-menu.html
 * -----------------------------------------------------------------
 * Menggantikan pembacaan localStorage('rm_menu_orders') dengan
 * pemanggilan API, termasuk menampilkan rincian item per pesanan.
 */

const API_BASE = 'http://localhost/rmpdg-backend/api';

async function muatDataPesananMenu() {
  try {
    const response = await fetch(`${API_BASE}/pesanan_menu.php`);
    const list = await response.json();

    const container = document.getElementById('daftarPesananMenu'); // sesuaikan id di HTML kamu
    container.innerHTML = '';

    list.forEach(pesanan => {
      // Rincian item ditampilkan sebagai daftar kecil di dalam kartu pesanan
      const itemsHtml = pesanan.items.map(it =>
        `<li>${it.qty}x ${it.nama_menu} - Rp${Number(it.subtotal).toLocaleString('id-ID')}</li>`
      ).join('');

      const card = document.createElement('div');
      card.className = 'pesanan-card';
      card.dataset.status = pesanan.status;
      card.innerHTML = `
        <h4>${pesanan.kode_pesanan} - ${pesanan.nama_pemesan}</h4>
        <p>${pesanan.telepon} | ${pesanan.order_type} | ${pesanan.lokasi}</p>
        <ul>${itemsHtml}</ul>
        <p>Subtotal: Rp${Number(pesanan.subtotal).toLocaleString('id-ID')}
           | Pajak: Rp${Number(pesanan.pajak).toLocaleString('id-ID')}
           | Total: <strong>Rp${Number(pesanan.total).toLocaleString('id-ID')}</strong></p>
        <p>Metode bayar: ${pesanan.metode_bayar}
           | Bukti: <a href="../uploads/${pesanan.bukti_bayar}" target="_blank">lihat bukti</a></p>
        <select onchange="ubahStatusPesanan(${pesanan.id}, this.value)">
          <option value="pending"    ${pesanan.status === 'pending'    ? 'selected' : ''}>Pending</option>
          <option value="diproses"   ${pesanan.status === 'diproses'   ? 'selected' : ''}>Diproses</option>
          <option value="selesai"    ${pesanan.status === 'selesai'    ? 'selected' : ''}>Selesai</option>
          <option value="dibatalkan" ${pesanan.status === 'dibatalkan' ? 'selected' : ''}>Dibatalkan</option>
        </select>
      `;
      container.appendChild(card);
    });
  } catch (err) {
    alert('Gagal memuat data pesanan: ' + err.message);
  }
}

async function ubahStatusPesanan(id, statusBaru) {
  try {
    const response = await fetch(`${API_BASE}/pesanan_menu.php?id=${id}`, {
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

document.addEventListener('DOMContentLoaded', muatDataPesananMenu);
