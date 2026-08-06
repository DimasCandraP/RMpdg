const CartSystem = {
  items: [],

  init() {
    // Load from localStorage
    try {
      this.items = JSON.parse(localStorage.getItem('rm_cart')) || [];
    } catch (e) {
      this.items = [];
    }

    // Inject DOM elements
    this.injectDOM();
    this.updateUI();
  },

  save() {
    localStorage.setItem('rm_cart', JSON.stringify(this.items));
  },

  addItem(item) {
    const existing = this.items.find(x => x.id === item.id);
    if (existing) {
      existing.qty += item.qty;
    } else {
      this.items.push(item);
    }
    this.save();
    this.updateUI();

    // Show toast
    this.showToast(`${item.nama} ditambahkan ke keranjang.`);

    // Add scale bounce animation to FAB
    const fab = document.getElementById('cartFab');
    if (fab) {
      fab.style.transform = 'scale(1.25)';
      setTimeout(() => {
        fab.style.transform = '';
      }, 300);
    }
  },

  changeQty(id, delta) {
    const item = this.items.find(x => x.id === id);
    if (item) {
      item.qty += delta;
      if (item.qty <= 0) {
        this.removeItem(id);
      } else {
        this.save();
        this.updateUI();
      }
    }
  },

  removeItem(id) {
    this.items = this.items.filter(x => x.id !== id);
    this.save();
    this.updateUI();
  },

  clear() {
    this.items = [];
    this.save();
    this.updateUI();
  },

  getTotal() {
    return this.items.reduce((sum, x) => sum + (x.harga * x.qty), 0);
  },

  getTotalCount() {
    return this.items.reduce((sum, x) => sum + x.qty, 0);
  },

  injectDOM() {
    // 1. Inject FAB (Floating Action Button)
    if (!document.getElementById('cartFab')) {
      const fab = document.createElement('button');
      fab.id = 'cartFab';
      fab.className = 'cart-fab';
      fab.setAttribute('aria-label', 'Keranjang Belanja');
      fab.innerHTML = `
        <i class="fa fa-shopping-cart"></i>
        <span class="cart-badge" id="cartBadge">0</span>
      `;
      fab.addEventListener('click', () => this.openModal());
      document.body.appendChild(fab);
    }

    // 2. Inject Toast Container
    if (!document.getElementById('cartToastContainer')) {
      const tc = document.createElement('div');
      tc.id = 'cartToastContainer';
      tc.className = 'cart-toast-container';
      document.body.appendChild(tc);
    }

    // 3. Inject Modal Overlay
    if (!document.getElementById('cartModalOverlay')) {
      const modal = document.createElement('div');
      modal.id = 'cartModalOverlay';
      modal.className = 'cart-modal-overlay';
      modal.addEventListener('click', () => this.closeModal());
      
      modal.innerHTML = `
        <div class="cart-modal-box" onclick="event.stopPropagation()">
          <div class="cart-modal-header">
            <h3><i class="fa fa-shopping-cart"></i> Detail Pesanan Anda</h3>
            <button class="cart-close-btn" onclick="CartSystem.closeModal()"><i class="fa fa-times"></i></button>
          </div>
          <div class="cart-modal-body" id="cartModalBody">
            <!-- RENDER DATA CART -->
          </div>
          <div class="cart-modal-footer">
            <div class="cart-total-row">
              <span>Total Pembayaran</span>
              <strong id="cartModalTotal">Rp0</strong>
            </div>
            <button class="cart-btn-confirm" onclick="CartSystem.confirmOrder()">
              <i class="fa fa-credit-card"></i> Konfirmasi &amp; Bayar
            </button>
          </div>
        </div>
      `;
      document.body.appendChild(modal);
    }
  },

  updateUI() {
    const totalCount = this.getTotalCount();
    const fab = document.getElementById('cartFab');
    const badge = document.getElementById('cartBadge');

    if (fab && badge) {
      if (totalCount > 0) {
        badge.textContent = totalCount;
        fab.classList.add('visible');
      } else {
        fab.classList.remove('visible');
      }
    }

    // Update modal body
    const body = document.getElementById('cartModalBody');
    const totalEl = document.getElementById('cartModalTotal');
    
    if (body) {
      if (this.items.length === 0) {
        body.innerHTML = `
          <div class="cart-empty-state">
            <i class="fa fa-shopping-basket"></i>
            <p>Keranjang belanja Anda masih kosong.</p>
          </div>
        `;
        if (totalEl) totalEl.textContent = 'Rp0';
        const confirmBtn = document.querySelector('.cart-btn-confirm');
        if (confirmBtn) confirmBtn.style.display = 'none';
      } else {
        const confirmBtn = document.querySelector('.cart-btn-confirm');
        if (confirmBtn) confirmBtn.style.display = 'flex';

        body.innerHTML = `
          <div class="cart-items-list">
            ${this.items.map(item => `
              <div class="cart-item-row">
                <img src="${item.img}" alt="${item.nama}" class="cart-item-img" />
                <div class="cart-item-info">
                  <h4 class="cart-item-name">${item.nama}</h4>
                  <span class="cart-item-price">Rp${item.harga.toLocaleString('id-ID')}</span>
                </div>
                <div class="cart-item-controls">
                  <div class="cart-qty-btn-group">
                    <button onclick="CartSystem.changeQty('${item.id}', -1)">-</button>
                    <span class="cart-qty-val">${item.qty}</span>
                    <button onclick="CartSystem.changeQty('${item.id}', 1)">+</button>
                  </div>
                  <button class="cart-item-delete" onclick="CartSystem.removeItem('${item.id}')" title="Hapus Item">
                    <i class="fa fa-trash"></i>
                  </button>
                </div>
              </div>
            `).join('')}
          </div>
        `;
        if (totalEl) {
          totalEl.textContent = `Rp${this.getTotal().toLocaleString('id-ID')}`;
        }
      }
    }
  },

  showToast(msg) {
    const container = document.getElementById('cartToastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = 'cart-toast';
    toast.innerHTML = `
      <i class="fa fa-check-circle"></i>
      <span>${msg}</span>
      <a onclick="CartSystem.openModal(); this.parentElement.remove();">Lihat</a>
    `;

    container.appendChild(toast);

    // Fade out and remove after delay
    setTimeout(() => {
      toast.classList.add('fade-out');
      setTimeout(() => toast.remove(), 300);
    }, 4000);
  },

  openModal() {
    this.updateUI();
    const overlay = document.getElementById('cartModalOverlay');
    if (overlay) overlay.classList.add('active');
  },

  closeModal() {
    const overlay = document.getElementById('cartModalOverlay');
    if (overlay) overlay.classList.remove('active');
  },

  confirmOrder() {
    this.closeModal();
    window.location.href = 'pembayaran.html';
  }
};

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', () => {
  CartSystem.init();
  window.CartSystem = CartSystem;
});
