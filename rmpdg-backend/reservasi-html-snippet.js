/**
 * CONTOH PERUBAHAN pada <script> di reservasi.html
 * -----------------------------------------------------------------
 * Ini adalah versi baru fungsi submitReservasi().
 * Ganti fungsi lama (yang memanggil RMData.tambahReservasi) dengan ini.
 * Baris <script src="js/data.js"> boleh dihapus dari reservasi.html
 * karena localStorage tidak dipakai lagi untuk fitur ini.
 */

// Sesuaikan URL ini dengan lokasi folder /api/ di server kamu
const API_BASE = 'http://localhost/rmpdg-backend/api';

async function submitReservasi() {
  const nama    = document.getElementById('resNama').value.trim();
  const telepon = document.getElementById('resTelepon').value.trim();
  const email   = document.getElementById('resEmail').value.trim();
  const tanggal = document.getElementById('resTanggal').value;   // format: YYYY-MM-DD (cocok untuk kolom DATE)
  const jam     = document.getElementById('resJam').value;       // format: HH:MM
  const tamu    = document.getElementById('resTamu').value;
  const acara   = document.getElementById('resAcara').value;
  const catatan = document.getElementById('resCatatan').value.trim();

  if (!nama || !telepon || !tanggal || !jam || !tamu) {
    alert('Mohon lengkapi semua field yang wajib diisi (*).');
    return;
  }

  const btn = document.getElementById('btnReservasi');
  btn.disabled = true;
  btn.style.opacity = '0.6';

  try {
    const response = await fetch(`${API_BASE}/reservasi.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        nama,
        telepon,
        email,
        tanggal,                 // dikirim mentah YYYY-MM-DD, backend yang simpan ke kolom DATE
        jam,
        jumlah_tamu: tamu,
        jenis_acara: acara || 'Makan Biasa',
        catatan
      })
    });

    const result = await response.json();

    if (!response.ok) {
      throw new Error(result.error || 'Gagal mengirim reservasi');
    }

    // Sukses -> tampilkan notifikasi, sama seperti versi lama
    document.getElementById('resSuccess').style.display = 'block';

    setTimeout(() => {
      ['resNama', 'resTelepon', 'resEmail', 'resTanggal', 'resCatatan'].forEach(id => {
        document.getElementById(id).value = '';
      });
      document.getElementById('resJam').selectedIndex = 0;
      document.getElementById('resAcara').selectedIndex = 0;
      document.getElementById('resSuccess').style.display = 'none';
      btn.disabled = false;
      btn.style.opacity = '1';
    }, 4000);

  } catch (err) {
    alert('Terjadi kesalahan: ' + err.message);
    btn.disabled = false;
    btn.style.opacity = '1';
  }
}
