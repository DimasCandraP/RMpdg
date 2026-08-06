const paketData = {
  A: { nama: 'Paket A', porsi: '50 Porsi', harga: 'Rp1.500.000', img: 'img/diskon20_.jpg' },
  B: { nama: 'Paket B', porsi: '100 Porsi', harga: 'Rp2.800.000', img: 'img/30825f62038ff446435a521c0237f561.jpg' },
  C: { nama: 'Paket C', porsi: '150 Porsi', harga: 'Rp4.000.000', img: 'img/d3f1a9ad74bb9ce6050ae1f6ee87763a.jpg' },
};

function updateRingkasan() {
  const paket  = document.getElementById('paketCatering').value;
  const tamu   = document.getElementById('jumlahTamu').value;
  const jenis  = document.getElementById('jenisAcara').value;
  const tgl    = document.getElementById('tglAcara')?.value;

  if (paket && paketData[paket]) {
    const p = paketData[paket];
    document.getElementById('ringkasanNama').textContent  = p.nama;
    document.getElementById('ringkasanPorsi').textContent = p.porsi;
    document.getElementById('ringkasanHarga').textContent = p.harga;
    document.getElementById('ringkasanImg').src           = p.img;
    document.getElementById('rdTotal').textContent        = p.harga;
  }

  if (tgl) {
    const d = new Date(tgl);
    document.getElementById('rdTanggal').textContent =
      d.toLocaleDateString('id-ID', { day:'2-digit', month:'long', year:'numeric' });
  }

  document.getElementById('rdTamu').textContent = tamu ? tamu + ' orang' : '-';
  document.getElementById('rdJenis').textContent = jenis || '-';
}

function previewFile(input) {
  const file = input.files[0];
  if (file) {
    document.getElementById('fileName').textContent = '✓ ' + file.name;
  }
}

async function submitCatering() {
  const nama       = document.getElementById('nama').value.trim();
  const telp       = document.getElementById('telp').value.trim();
  const email      = document.getElementById('email')?.value.trim() || '';
  const tgl        = document.getElementById('tglAcara')?.value;
  const jenisAcara = document.getElementById('jenisAcara')?.value || '';
  const jumlahTamu = document.getElementById('jumlahTamu')?.value || '';
  const paketCode  = document.getElementById('paketCatering').value;
  const catatan    = document.getElementById('catatan')?.value.trim() || '';
  const fileInput  = document.getElementById('fileBukti');

  if (!nama || !telp || !paketCode || !tgl) {
    alert('Mohon lengkapi semua field yang wajib diisi (*).');
    return;
  }

  if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
    alert('Mohon upload foto bukti pembayaran transfer.');
    return;
  }

  // Map paketCode 'A','B','C' ke paket_id 1, 2, 3
  const paketMap = { 'A': 1, 'B': 2, 'C': 3 };
  const paketId = paketMap[paketCode] || 1;

  const formData = new FormData();
  formData.append('nama', nama);
  formData.append('telepon', telp);
  formData.append('email', email);
  formData.append('tanggal_acara', tgl);
  formData.append('jenis_acara', jenisAcara);
  formData.append('jumlah_tamu', jumlahTamu);
  formData.append('paket_id', paketId);
  formData.append('catatan', catatan);
  formData.append('fileBukti', fileInput.files[0]);

  const apiBase = window.API_BASE || '/MPTI/rmpdg-backend/api';

  try {
    const response = await fetch(`${apiBase}/catering.php`, {
      method: 'POST',
      body: formData
    });

    const result = await response.json();
    if (!response.ok) throw new Error(result.error || 'Gagal menyimpan pesanan catering');

    alert(`Pesanan catering berhasil dikirim!\nKode Pesanan: ${result.kode_pesanan}\nTerima kasih, tim kami akan segera menghubungi Anda.`);
    window.location.href = 'profil.html';
  } catch (err) {
    alert('Terjadi kesalahan: ' + err.message);
  }
}

// Auto-set paket dari URL query string & Enforce login check
window.addEventListener('DOMContentLoaded', () => {
  const isLoggedIn = (localStorage.getItem('is_logged_in') === 'true' || sessionStorage.getItem('is_logged_in') === 'true') || (localStorage.getItem('admin_logged_in') === 'true' || sessionStorage.getItem('admin_logged_in') === 'true');
  if (!isLoggedIn) {
    alert('⚠️ Perhatian!\n\nAnda wajib melakukan Login / Daftar akun terlebih dahulu sebelum memesan Paket Catering.');
    localStorage.setItem('redirect_after_login', window.location.href);
    window.location.href = 'login.html';
    return;
  }

  // Autofill data dari akun terdaftar
  const uName = (localStorage.getItem('user_name') || sessionStorage.getItem('user_name'));
  const uContact = (localStorage.getItem('user_contact') || sessionStorage.getItem('user_contact'));
  if (uName) {
    const elNama = document.getElementById('nama');
    if (elNama && !elNama.value) elNama.value = uName;
  }
  if (uContact) {
    if (uContact.includes('@')) {
      const elEmail = document.getElementById('email');
      if (elEmail && !elEmail.value) elEmail.value = uContact;
    } else {
      const elTelp = document.getElementById('telp');
      if (elTelp && !elTelp.value) elTelp.value = uContact;
    }
  }

  const params = new URLSearchParams(window.location.search);
  const paket  = params.get('paket');
  if (paket && document.getElementById('paketCatering')) {
    document.getElementById('paketCatering').value = paket;
    updateRingkasan();
  }
});
