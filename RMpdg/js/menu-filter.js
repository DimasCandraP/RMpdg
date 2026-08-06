/**
 * menu-filter.js
 * Filter, pencarian & paginasi menu (maksimal 7 menu per halaman)
 * Kategori tersimpan di URL & localStorage sehingga tidak kembali ke "semua" saat refresh.
 */

let activeFilter = 'semua';
let activeKeyword = '';
let currentPage = 1;
const ITEMS_PER_PAGE = 7;
const VALID_CATEGORIES = ['semua', 'paket-nasi', 'lauk', 'sayur', 'minuman'];

// -----------------------------------------------
// Mapping nama kategori DB → data-category slug
// -----------------------------------------------
function getCategorySlug(kategoriNama) {
  if (!kategoriNama) return 'lauk';
  const k = kategoriNama.toLowerCase().trim();
  if (k.includes('paket nasi') || k.includes('nasi')) return 'paket-nasi';
  if (k.includes('lauk'))         return 'lauk';
  if (k.includes('sayur'))        return 'sayur';
  if (k.includes('minuman'))      return 'minuman';
  return 'lauk';
}

// -----------------------------------------------
// Inisialisasi Kategori Aktif (dibaca dari head inline script)
// -----------------------------------------------
function initActiveCategory() {
  // window.__MENU_INIT_CATEGORY__ diset oleh inline script di <head>
  // SEBELUM halaman dirender, sehingga tidak ada flash ke kategori "semua"
  const cat = window.__MENU_INIT_CATEGORY__ || 'semua';
  activeFilter = VALID_CATEGORIES.includes(cat) ? cat : 'semua';
  updateCategoryUI(activeFilter);
}

// -----------------------------------------------
// Ubah Kategori Aktif & Simpan ke URL/Storage
// -----------------------------------------------
function setCategory(cat, resetPage = true) {
  activeFilter = VALID_CATEGORIES.includes(cat) ? cat : 'semua';
  
  // Simpan ke localStorage
  localStorage.setItem('menu_active_category', activeFilter);

  // Update URL query string tanpa reload halaman
  try {
    const newUrl = new URL(window.location.href);
    if (activeFilter === 'semua') {
      newUrl.searchParams.delete('cat');
      newUrl.searchParams.delete('kategori');
      newUrl.searchParams.delete('category');
    } else {
      newUrl.searchParams.set('cat', activeFilter);
    }
    window.history.replaceState(null, '', newUrl.toString());
  } catch (e) {}

  updateCategoryUI(activeFilter);
  applyFilter(resetPage);
}

// -----------------------------------------------
// Update UI Tab & Sidebar Link Aktif
// -----------------------------------------------
function updateCategoryUI(cat) {
  const tabBtns = document.querySelectorAll('.tab-btn');
  const sidebarLinks = document.querySelectorAll('.sidebar-link');

  tabBtns.forEach(btn => {
    const filter = btn.dataset.filter || 'semua';
    btn.classList.toggle('active', filter === cat);
  });

  sidebarLinks.forEach(link => {
    const catAttr = link.dataset.cat || 'semua';
    link.classList.toggle('active', catAttr === cat);
  });
}

// -----------------------------------------------
// Terapkan filter kategori, keyword & paginasi
// -----------------------------------------------
function applyFilter(resetPage = false) {
  if (resetPage) {
    currentPage = 1;
  }

  const allItems = document.querySelectorAll('#menuList .menu-item');
  const menuEmpty = document.getElementById('menuEmpty');
  const matchedItems = [];

  // 1. Filter item yang sesuai
  allItems.forEach(item => {
    const cat  = (item.getAttribute('data-category') || '').trim();
    const h3   = item.querySelector('h3');
    const p    = item.querySelector('p');
    const name = h3 ? h3.textContent.toLowerCase() : '';
    const desc = p  ? p.textContent.toLowerCase()  : '';

    const matchCat = (activeFilter === 'semua') || (cat === activeFilter);
    const matchKw  = !activeKeyword || name.includes(activeKeyword) || desc.includes(activeKeyword);

    if (matchCat && matchKw) {
      matchedItems.push(item);
    }
  });

  const totalItems = matchedItems.length;
  const totalPages = Math.max(1, Math.ceil(totalItems / ITEMS_PER_PAGE));

  // Validasi rentang currentPage
  if (currentPage > totalPages) currentPage = totalPages;
  if (currentPage < 1) currentPage = 1;

  const startIndex = (currentPage - 1) * ITEMS_PER_PAGE;
  const endIndex   = startIndex + ITEMS_PER_PAGE;

  // 2. Tampilkan HANYA 7 menu untuk halaman aktif
  allItems.forEach(item => {
    const matchedIndex = matchedItems.indexOf(item);
    if (matchedIndex >= startIndex && matchedIndex < endIndex) {
      item.style.removeProperty('display');
    } else {
      item.style.display = 'none';
    }
  });

  // Pesan jika menu kosong
  if (menuEmpty) {
    menuEmpty.style.display = totalItems === 0 ? 'block' : 'none';
  }

  // Hapus FOUC shield setelah filter pertama kali diterapkan
  const shield = document.getElementById('menu-fouc-shield');
  if (shield) shield.remove();

  // 3. Render tombol paginasi
  renderPagination(totalPages);
}

// -----------------------------------------------
// Render Tombol Paginasi (Nomor 1, 2, dst. selalu terlihat)
// -----------------------------------------------
function renderPagination(totalPages) {
  const paginationEl = document.querySelector('.pagination');
  if (!paginationEl) return;

  paginationEl.style.display = 'flex';

  if (totalPages <= 1) {
    paginationEl.innerHTML = `<button class="page-btn active" data-page="1">1</button>`;
    return;
  }

  let html = '';

  // Tombol Prev (<)
  const prevDisabled = currentPage === 1 ? 'disabled' : '';
  html += `<button class="page-btn nav-prev" data-page="${currentPage - 1}" ${prevDisabled} title="Halaman Sebelumnya"><i class="fa fa-chevron-left"></i></button>`;

  // Tombol Nomor Halaman
  for (let i = 1; i <= totalPages; i++) {
    const activeClass = i === currentPage ? 'active' : '';
    html += `<button class="page-btn ${activeClass}" data-page="${i}">${i}</button>`;
  }

  // Tombol Next (>)
  const nextDisabled = currentPage === totalPages ? 'disabled' : '';
  html += `<button class="page-btn nav-next" data-page="${currentPage + 1}" ${nextDisabled} title="Halaman Berikutnya"><i class="fa fa-chevron-right"></i></button>`;

  paginationEl.innerHTML = html;

  // Event listener tombol halaman
  paginationEl.querySelectorAll('.page-btn').forEach(btn => {
    btn.onclick = (e) => {
      e.preventDefault();
      if (btn.hasAttribute('disabled')) return;
      const page = parseInt(btn.getAttribute('data-page'), 10);
      if (!isNaN(page) && page !== currentPage) {
        window.goToPage(page);
      }
    };
  });
}

// Navigasi Halaman Global
window.goToPage = function (page) {
  currentPage = page;
  applyFilter(false);

  const menuHeader = document.querySelector('.menu-header');
  if (menuHeader) {
    menuHeader.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
};

// -----------------------------------------------
// Event Listener Filter & Search
// -----------------------------------------------
function setupFilterEvents() {
  const tabBtns      = document.querySelectorAll('.tab-btn');
  const sidebarLinks = document.querySelectorAll('.sidebar-link');
  const searchInput  = document.getElementById('searchInput');

  tabBtns.forEach(btn => {
    btn.onclick = () => {
      const cat = btn.dataset.filter || 'semua';
      setCategory(cat, true);
    };
  });

  sidebarLinks.forEach(link => {
    link.onclick = (e) => {
      e.preventDefault();
      const cat = link.dataset.cat || 'semua';
      setCategory(cat, true);
    };
  });

  if (searchInput) {
    searchInput.oninput = () => {
      activeKeyword = searchInput.value.toLowerCase().trim();
      applyFilter(true);
    };
  }
}

// -----------------------------------------------
// Load Data dari API
// -----------------------------------------------
async function loadMenuFromAPI() {
  const apiBase    = window.API_BASE || '/MPTI/rmpdg-backend/api';
  const menuListEl = document.getElementById('menuList');
  if (!menuListEl) return;

  try {
    const res = await fetch(`${apiBase}/menu.php`);
    if (!res.ok) return;

    const text = await res.text();
    let items;
    try {
      items = JSON.parse(text);
    } catch {
      return;
    }

    if (!Array.isArray(items) || items.length === 0) return;

    const menuEmptyEl = document.getElementById('menuEmpty');

    menuListEl.innerHTML = items.map(menu => {
      const catSlug       = getCategorySlug(menu.kategori_nama || '');
      const hargaFmt      = Number(menu.harga).toLocaleString('id-ID');
      const imgSrc        = menu.gambar_utama || 'img/071114000_1522751934-Resep-Rendang-Ayam-Kering.jpg';
      const deskripsi     = menu.deskripsi || '';
      const kategoriLabel = menu.kategori_nama || 'Menu';

      return `<div class="menu-item" data-category="${catSlug}">
          <img src="${imgSrc}" alt="${menu.nama}" loading="lazy"/>
          <div class="menu-item-info">
            <h3>${menu.nama}</h3>
            <p>${deskripsi}</p>
            <a href="#" class="menu-cat-tag">${kategoriLabel}</a>
          </div>
          <div class="menu-item-action">
            <span class="menu-price">Rp${hargaFmt}</span>
            <a href="menu_detail.html?id=${menu.slug}" class="btn-detail">Lihat Detail</a>
          </div>
        </div>`;
    }).join('');

    if (menuEmptyEl) menuListEl.appendChild(menuEmptyEl);

  } catch (e) {
    console.warn('[menu-filter] API tidak tersedia, menggunakan data statis:', e.message);
  } finally {
    applyFilter(true);
  }
}

document.addEventListener('DOMContentLoaded', () => {
  initActiveCategory();
  setupFilterEvents();
  applyFilter(true);
  loadMenuFromAPI();
});