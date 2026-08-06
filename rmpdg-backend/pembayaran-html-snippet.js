/**
 * CONTOH PERUBAHAN pada <script> di pembayaran.html
 * -----------------------------------------------------------------
 * Menggantikan bagian yang tadinya menyimpan pesanan ke
 * localStorage.setItem('rm_menu_orders', ...).
 *
 * Karena ada file upload (bukti transfer), request dikirim sebagai
 * FormData (multipart), BUKAN JSON biasa.
 */

const API_BASE = 'http://localhost/rmpdg-backend/api';

async function submitPembayaran() {
  // cartItems didapat dari localStorage 'rm_cart' seperti kode asli kamu
  let cartItems = [];
  try {
    cartItems = JSON.parse(localStorage.getItem('rm_cart')) || [];
  } catch (e) {
    cartItems = [];
  }

  if (cartItems.length === 0) {
    alert('Keranjang Anda kosong.');
    return;
  }

  const nama         = document.getElementById('nama').value.trim();
  const telp         = document.getElementById('telp').value.trim();
  const orderType    = document.getElementById('orderType').value;
  const noMeja       = document.getElementById('noMeja').value.trim();
  const alamat       = document.getElementById('alamat').value.trim();
  const fileBukti    = document.getElementById('fileBukti');
  const paymentMethod = document.getElementById('paymentMethod').value;
  const catatan      = document.getElementById('catatan').value.trim();

  // Validasi (sama seperti kode asli)
  if (!nama || !telp) {
    alert('Mohon lengkapi Nama dan No. WhatsApp Anda.');
    return;
  }
  if (orderType === 'dinein' && !noMeja) {
    alert('Mohon masukkan Nomor Meja Anda.');
    return;
  }
  if (orderType === 'delivery' && !alamat) {
    alert('Mohon masukkan Alamat Pengiriman Anda.');
    return;
  }
  if (!fileBukti.files || fileBukti.files.length === 0) {
    alert('Mohon upload bukti pembayaran/transfer Anda.');
    return;
  }

  const lokasi = orderType === 'dinein'
    ? 'Meja ' + noMeja
    : orderType === 'delivery'
      ? alamat
      : 'Ambil Sendiri';

  // Susun FormData - gabungan field teks + file
  const formData = new FormData();
  formData.append('nama', nama);
  formData.append('telp', telp);
  formData.append('orderType', orderType);
  formData.append('lokasi', lokasi);
  formData.append('metodeBayar', paymentMethod === 'qris' ? 'QRIS' : 'Transfer BCA');
  formData.append('catatan', catatan);
  // Kirim isi keranjang sebagai JSON string di satu field
  formData.append('items', JSON.stringify(
    cartItems.map(x => ({ id: x.id, qty: x.qty }))   // harga TIDAK dikirim, server yang hitung ulang
  ));
  formData.append('fileBukti', fileBukti.files[0]);

  try {
    const response = await fetch(`${API_BASE}/pesanan_menu.php`, {
      method: 'POST',
      body: formData   // JANGAN set header Content-Type manual, browser yang atur otomatis untuk FormData
    });

    const result = await response.json();
    if (!response.ok) throw new Error(result.error || 'Gagal mengirim pesanan');

    // Hapus keranjang setelah berhasil
    localStorage.removeItem('rm_cart');

    // Tampilkan popup sukses (pakai UI yang sudah ada di pembayaran.html)
    alert(`Pesanan berhasil! Kode pesanan: ${result.kode_pesanan}, Total: Rp${result.total.toLocaleString('id-ID')}`);
    window.location.href = 'index.html';

  } catch (err) {
    alert('Terjadi kesalahan: ' + err.message);
  }
}
