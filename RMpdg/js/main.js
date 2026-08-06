// Global API Base URL helper
window.API_BASE = (() => {
  const path = window.location.pathname;
  if (path.includes('/MPTI/')) return '/MPTI/rmpdg-backend/api';
  return '/rmpdg-backend/api';
})();

// Helper: baca dari localStorage ATAU sessionStorage (backward compat)
function getAuthItem(key) {
  return localStorage.getItem(key) || sessionStorage.getItem(key) || null;
}

// Update tampilan Navbar ketika user/admin terautentikasi
function updateNavbarUserStatus() {
  const isAdmin = getAuthItem('admin_logged_in') === 'true';
  const isUser  = getAuthItem('is_logged_in') === 'true';

  const existingWrapper = document.getElementById('navUserDropdownWrapper');
  if (existingWrapper) {
    existingWrapper.remove();
  }

  if (!isAdmin && !isUser) return; // Belum login

  const loginBtn = document.querySelector('.btn-login');

  const rawName     = getAuthItem('user_name') || getAuthItem('admin_email') || 'Pengguna';
  const userContact = getAuthItem('user_contact') || getAuthItem('admin_email') || '';
  const userPhoto   = getAuthItem('user_photo');
  const displayName = isAdmin ? 'Admin RM' : (rawName.split('@')[0]);
  const initialChar = (displayName.charAt(0) || 'U').toUpperCase();

  const path         = window.location.pathname;
  const isInAdminDir = path.includes('/admin/');
  const profilUrl    = isInAdminDir ? '../profil.html' : 'profil.html';
  const adminUrl     = isInAdminDir ? 'dashboard.php' : 'admin/dashboard.php';

  const wrapper = document.createElement('div');
  wrapper.id = 'navUserDropdownWrapper';
  wrapper.style.cssText = 'position:relative; display:inline-flex; align-items:center; vertical-align:middle;';

  const avatarHtml = userPhoto 
    ? `<img src="${userPhoto}" alt="${displayName}" style="width:34px; height:34px; border-radius:50%; object-fit:cover; border:2px solid var(--secondary,#FFC107);" />`
    : `<div style="width:34px; height:34px; border-radius:50%; background:var(--secondary,#FFC107); color:var(--primary-dark,#8B0000); font-weight:800; font-size:1.05rem; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 8px rgba(0,0,0,0.25); flex-shrink:0;">${initialChar}</div>`;

  wrapper.innerHTML = `
    <button id="btnUserMenu" type="button" title="Menu Profil Saya" style="background:rgba(255,255,255,0.15); border:1.5px solid rgba(255,255,255,0.4); display:flex; align-items:center; gap:8px; cursor:pointer; padding:4px 12px 4px 4px; border-radius:30px; transition:all 0.2s;">
      ${avatarHtml}
      <span style="color:#fff; font-weight:700; font-size:0.88rem; max-width:120px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${displayName}</span>
      <i class="fa fa-chevron-down" style="color:rgba(255,255,255,0.8); font-size:0.68rem;"></i>
    </button>

    <div id="userDropdownMenu" style="display:none; position:absolute; right:0; left:auto; top:48px; background:#fff; min-width:230px; box-shadow:0 12px 32px rgba(0,0,0,0.2); border-radius:12px; overflow:hidden; z-index:9999; border:1px solid #eee;">
      <div style="padding:14px 16px; background:#FAF6F4; border-bottom:1px solid #eee;">
        <strong style="display:block; color:#222; font-size:0.9rem; font-weight:800;">${rawName}</strong>
        <span style="font-size:0.78rem; color:#777;">${userContact || (isAdmin ? 'Administrator' : 'Pelanggan')}</span>
      </div>

      <a href="${profilUrl}" style="display:flex; align-items:center; gap:10px; padding:12px 16px; color:#333; text-decoration:none; font-size:0.88rem; font-weight:600; border-bottom:1px solid #f5f5f5;">
        <i class="fa-solid fa-user-gear" style="color:var(--primary,#8B0000); width:18px; text-align:center;"></i>
        Kelola Profil &amp; Riwayat
      </a>

      ${isAdmin ? `
      <a href="${adminUrl}" style="display:flex; align-items:center; gap:10px; padding:12px 16px; color:#333; text-decoration:none; font-size:0.88rem; font-weight:600; border-bottom:1px solid #f5f5f5;">
        <i class="fa-solid fa-chart-pie" style="color:#27ae60; width:18px; text-align:center;"></i>
        Panel Admin
      </a>` : ''}

      <a href="#" class="btnLogoutAction" style="display:flex; align-items:center; gap:10px; padding:12px 16px; color:#e74c3c; text-decoration:none; font-size:0.88rem; font-weight:700;">
        <i class="fa-solid fa-right-from-bracket" style="width:18px; text-align:center;"></i>
        Sign Out (Keluar)
      </a>
    </div>
  `;

  const navbar = document.querySelector('.navbar');
  if (loginBtn) {
    loginBtn.parentNode.replaceChild(wrapper, loginBtn);
  } else if (navbar) {
    const navToggle = document.getElementById('navToggle');
    if (navToggle) {
      navbar.insertBefore(wrapper, navToggle);
    } else {
      navbar.appendChild(wrapper);
    }
  }

  const btnMenu  = document.getElementById('btnUserMenu');
  const dropdown = document.getElementById('userDropdownMenu');
  if (btnMenu && dropdown) {
    btnMenu.addEventListener('click', (e) => {
      e.stopPropagation();
      dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });
    document.addEventListener('click', () => {
      if (dropdown) dropdown.style.display = 'none';
    });
  }

  document.querySelectorAll('.btnLogoutAction').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      performLogout();
    });
  });
}

async function performLogout() {
  try {
    await fetch(`${window.API_BASE}/logout.php`, { credentials: 'same-origin' });
  } catch (e) {}
  localStorage.clear();
  sessionStorage.clear();
  alert('Anda telah berhasil keluar dari akun (Sign Out).');
  const isInAdmin = window.location.pathname.includes('/admin/');
  window.location.href = isInAdmin ? '../index.html' : 'index.html';
}

function initMain() {
  const toggle = document.getElementById('navToggle');
  const navLinks = document.querySelector('.nav-links');
  if (toggle && navLinks) {
    toggle.addEventListener('click', () => navLinks.classList.toggle('open'));
  }

  const currentPage = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-links a').forEach(link => {
    if (link.getAttribute('href') === currentPage) {
      link.classList.add('active');
    }
  });

  updateNavbarUserStatus();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initMain);
} else {
  initMain();
}