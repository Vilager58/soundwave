/**
 * SoundWave Admin Panel - Core Interactive Logic
 * Vanilla JS (No dependencies)
 */

document.addEventListener('DOMContentLoaded', () => {
  initSidebar();
  initModals();
  initTabs();
  // initTableSearchAndFilters();
  initPickupTerminal();
});

/* ==========================================================================
   SIDEBAR & MOBILE NAVIGATION
   ========================================================================== */
function initSidebar() {
  const sidebar = document.querySelector('.admin-sidebar');
  const collapseBtn = document.querySelector('.sidebar-collapse-btn');
  const mobileToggleBtn = document.querySelector('.mobile-menu-toggle');

  if (collapseBtn && sidebar) {
    collapseBtn.addEventListener('click', () => {
      document.body.classList.toggle('sidebar-collapsed');
      const icon = collapseBtn.querySelector('i');
      if (icon) {
        icon.classList.toggle('bi-chevron-left');
        icon.classList.toggle('bi-chevron-right');
      }
    });
  }

  if (mobileToggleBtn && sidebar) {
    mobileToggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('show-mobile');
    });

    // Close on backdrop click if mobile
    document.addEventListener('click', (e) => {
      if (window.innerWidth <= 960) {
        if (!sidebar.contains(e.target) && !mobileToggleBtn.contains(e.target)) {
          sidebar.classList.remove('show-mobile');
        }
      }
    });
  }
}

/* ==========================================================================
   MODAL MANAGEMENT
   ========================================================================== */
function openModal(modalId) {
  const backdrop = document.getElementById(modalId);
  if (!backdrop) return;
  backdrop.style.display = 'flex';
  setTimeout(() => backdrop.classList.add('show'), 10);
  document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
  const backdrop = document.getElementById(modalId);
  if (!backdrop) return;
  backdrop.classList.remove('show');
  setTimeout(() => {
    backdrop.style.display = 'none';
    document.body.style.overflow = '';
  }, 250);
}

function initModals() {
  // Modal trigger buttons with data-modal-target
  document.querySelectorAll('[data-modal-target]').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const targetId = btn.getAttribute('data-modal-target');
      openModal(targetId);
    });
  });

  // Modal close buttons
  document.querySelectorAll('.modal-close-btn, [data-modal-close]').forEach(btn => {
    btn.addEventListener('click', () => {
      const backdrop = btn.closest('.modal-backdrop');
      if (backdrop) {
        closeModal(backdrop.id);
      }
    });
  });

  // Close on clicking outer backdrop
  document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
    backdrop.addEventListener('click', (e) => {
      if (e.target === backdrop) {
        closeModal(backdrop.id);
      }
    });
  });

  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const activeBackdrop = document.querySelector('.modal-backdrop.show');
      if (activeBackdrop) {
        closeModal(activeBackdrop.id);
      }
    }
  });
}

/* ==========================================================================
   TABS SWITCHER
   ========================================================================== */
function initTabs() {
  document.querySelectorAll('.nav-tabs').forEach(tabNav => {
    const tabButtons = tabNav.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const targetId = button.getAttribute('data-tab');
        const container = tabNav.closest('.modal-body') || tabNav.closest('.admin-card') || document;

        // Reset active buttons in this group
        tabButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');

        // Toggle panes
        const panes = container.querySelectorAll('.tab-pane');
        panes.forEach(pane => {
          if (pane.id === targetId) {
            pane.classList.add('active');
          } else {
            pane.classList.remove('active');
          }
        });
      });
    });
  });
}

/* ==========================================================================
   TABLE SEARCH AND CLIENT FILTERING
   ========================================================================== */
function initTableSearchAndFilters() {
  const searchInputs = document.querySelectorAll('.toolbar-search input, [data-table-search]');

  searchInputs.forEach(input => {
    const targetTableSelector = input.getAttribute('data-table-search') || '.admin-table';
    const table = document.querySelector(targetTableSelector);
    if (!table) return;

    input.addEventListener('input', () => {
      const query = input.value.toLowerCase().trim();
      const rows = table.querySelectorAll('tbody tr');

      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
      });
    });
  });

  // Category and Brand selects
  const filterSelects = document.querySelectorAll('[data-table-filter]');
  filterSelects.forEach(select => {
    const colIndex = parseInt(select.getAttribute('data-table-filter'), 10);
    const table = document.querySelector(select.getAttribute('data-filter-table') || '.admin-table');
    if (!table || isNaN(colIndex)) return;

    select.addEventListener('change', () => {
      const val = select.value.toLowerCase();
      const rows = table.querySelectorAll('tbody tr');

      rows.forEach(row => {
        const cell = row.querySelectorAll('td')[colIndex];
        if (!cell) return;
        const text = cell.textContent.toLowerCase();
        if (val === '' || val === 'all' || text.includes(val)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  });
}

/* ==========================================================================
   TOAST NOTIFICATION ENGINE
   ========================================================================== */
function showToast(title, message, type = 'success') {
  let container = document.querySelector('.toast-container');
  if (!container) {
    container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = `admin-toast toast-${type}`;

  const iconMap = {
    success: 'bi-check-circle-fill',
    info: 'bi-info-circle-fill',
    warning: 'bi-exclamation-triangle-fill',
    danger: 'bi-x-circle-fill'
  };

  toast.innerHTML = `
    <i class="bi ${iconMap[type] || 'bi-info-circle-fill'} toast-icon"></i>
    <div class="toast-content">
      <div class="toast-title">${title}</div>
      <div class="toast-message">${message}</div>
    </div>
    <button class="toast-close" type="button" aria-label="Close">
      <i class="bi bi-x"></i>
    </button>
  `;

  container.appendChild(toast);

  const closeBtn = toast.querySelector('.toast-close');
  closeBtn.addEventListener('click', () => {
    removeToast(toast);
  });

  setTimeout(() => {
    removeToast(toast);
  }, 4000);
}

function removeToast(toast) {
  toast.style.opacity = '0';
  toast.style.transform = 'translateX(50px)';
  setTimeout(() => {
    if (toast.parentElement) {
      toast.parentElement.removeChild(toast);
    }
  }, 300);
}

/* ==========================================================================
   PICKUP POINT (POS / ORDER FULFILLMENT) ENGINE
   ========================================================================== */
const sampleOrdersDB = {
  'SW-2026-0002': {
    orderNumber: 'SW-2026-0002',
    date: '10.09.2026 11:45',
    status: 'ready_pickup',
    statusTitle: 'Готов к выдаче',
    statusBadge: 'badge-info',
    customer: {
      name: 'Анна Игоревна Меломанова',
      phone: '+7 (999) 666-77-88',
      email: 'anna.music@yandex.ru',
      bonuses: 1250
    },
    showroom: 'Шоурум Soundwave СПб (Невский пр., 54)',
    items: [{
      id: 4,
      title: 'Беспроводная колонка Marshall Stanmore III',
      variant: 'Классический черный (Black)',
      article: 'MSH-STAN3-BLK',
      barcode: '7340055385671',
      price: 41990,
      amount: 1,
      image: 'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=150&auto=format&fit=crop&q=80'
    }],
    isPaid: false,
    paymentMethod: 'cash_on_delivery',
    paymentMethodTitle: 'Оплата при получении в шоуруме',
    total: 41990
  },
  'SW-2026-0001': {
    orderNumber: 'SW-2026-0001',
    date: '01.09.2026 14:20',
    status: 'delivered',
    statusTitle: 'Успешно выдан',
    statusBadge: 'badge-success',
    customer: {
      name: 'Михаил Олегович Аудиофилов',
      phone: '+7 (999) 555-66-77',
      email: 'customer@gmail.com',
      bonuses: 3800
    },
    showroom: 'Флагманский салон Arbat (Новый Арбат, 21)',
    items: [{
      id: 1,
      title: 'Беспроводные наушники Sony WH-1000XM5',
      variant: 'Черный матовый (Black)',
      article: 'SNY-WHXM5-BLK',
      barcode: '4548736132566',
      price: 37990,
      amount: 1,
      image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=150&auto=format&fit=crop&q=80'
    }],
    isPaid: true,
    paymentMethod: 'card_online',
    paymentMethodTitle: 'Оплачено онлайн банковской картой',
    total: 37990
  },
  'SW-2026-0003': {
    orderNumber: 'SW-2026-0003',
    date: '12.09.2026 18:30',
    status: 'ready_pickup',
    statusTitle: 'Готов к выдаче',
    statusBadge: 'badge-info',
    customer: {
      name: 'Михаил Олегович Аудиофилов',
      phone: '+7 (999) 555-66-77',
      email: 'customer@gmail.com',
      bonuses: 3800
    },
    showroom: 'Шоурум Soundwave Flacon (Дизайн-завод Flacon)',
    items: [{
      id: 9,
      title: 'Студийные наушники Beyerdynamic DT 770 PRO',
      variant: '80 Ом (Универсальные)',
      article: 'BYR-DT770-80',
      barcode: '4010118459048',
      price: 18990,
      amount: 1,
      image: 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=150&auto=format&fit=crop&q=80'
    }],
    isPaid: true,
    paymentMethod: 'sbp',
    paymentMethodTitle: 'Оплачено онлайн по СБП',
    total: 18990
  }
};

function initPickupTerminal() {
  const searchInput = document.getElementById('pickup-order-search');
  const searchBtn = document.getElementById('pickup-search-btn');
  const quickChips = document.querySelectorAll('.scan-chip');

  if (!searchInput) return;

  function loadOrder(orderNum) {
    const cleanNum = orderNum.trim().toUpperCase();
    const order = sampleOrdersDB[cleanNum];

    if (!order) {
      showToast('Заказ не найден', `Заказ с номером "${cleanNum}" отсутствует в системе шоурумов`, 'danger');
      return;
    }

    renderPickupOrder(order);
    showToast('Заказ найден', `Загружен заказ ${order.orderNumber} (${order.customer.name})`, 'info');
  }

  if (searchBtn) {
    searchBtn.addEventListener('click', () => {
      loadOrder(searchInput.value);
    });
  }

  searchInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      loadOrder(searchInput.value);
    }
  });

  quickChips.forEach(chip => {
    chip.addEventListener('click', () => {
      const code = chip.getAttribute('data-order-code');
      searchInput.value = code;
      loadOrder(code);
    });
  });

  // Payment Option selection buttons
  const paymentOptionBtns = document.querySelectorAll('.payment-option-btn');
  paymentOptionBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      paymentOptionBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
    });
  });

  // Fulfillment confirmation button
  const completeBtn = document.getElementById('complete-pickup-btn');
  if (completeBtn) {
    completeBtn.addEventListener('click', () => {
      // Check if all checkboxes are ticked
      const checkboxes = document.querySelectorAll('.check-checkbox');
      const allChecked = Array.from(checkboxes).every(cb => cb.checked);

      if (!allChecked) {
        showToast('Проверьте комплектацию', 'Отметьте галочками все позиции перед передачей клиенту!', 'warning');
        return;
      }

      // Success
      completeBtn.disabled = true;
      completeBtn.innerHTML = '<i class="bi bi-check2-circle"></i> Заказ успешно выдан!';
      completeBtn.classList.remove('btn-brand');
      completeBtn.classList.add('btn-success');

      // Update badge
      const statusBadge = document.getElementById('order-status-badge');
      if (statusBadge) {
        statusBadge.className = 'badge badge-success';
        statusBadge.innerHTML = '<i class="bi bi-check-all"></i> Выдан покупателю';
      }

      // Enable receipt print button
      const printBtn = document.getElementById('print-receipt-btn');
      if (printBtn) {
        printBtn.removeAttribute('disabled');
      }

      showToast('Заказ выдан!', 'Статус обновлен в базе, остатки списаны, бонусные баллы начислены покупателю', 'success');

      // Add to today's log table dynamically
      addOrderToTodayLog();
    });
  }

  // Print receipt trigger
  const printBtn = document.getElementById('print-receipt-btn');
  if (printBtn) {
    printBtn.addEventListener('click', () => {
      openModal('receipt-modal');
    });
  }
}

function renderPickupOrder(order) {
  // Update order number
  const numElem = document.getElementById('display-order-number');
  if (numElem) numElem.textContent = order.orderNumber;

  // Status badge
  const badgeElem = document.getElementById('order-status-badge');
  if (badgeElem) {
    badgeElem.className = `badge ${order.statusBadge}`;
    badgeElem.textContent = order.statusTitle;
  }

  // Customer info
  const nameElem = document.getElementById('customer-display-name');
  if (nameElem) nameElem.textContent = order.customer.name;

  const phoneElem = document.getElementById('customer-display-phone');
  if (phoneElem) phoneElem.textContent = order.customer.phone;

  const bonusElem = document.getElementById('customer-display-bonuses');
  if (bonusElem) bonusElem.textContent = `${order.customer.bonuses} баллов`;

  // Total
  const totalElem = document.getElementById('display-order-total');
  if (totalElem) totalElem.textContent = `${order.total.toLocaleString('ru-RU')} ₽`;

  // Items List
  const itemsContainer = document.getElementById('order-items-checklist');
  if (itemsContainer) {
    itemsContainer.innerHTML = '';
    order.items.forEach(item => {
      const row = document.createElement('div');
      row.className = 'check-item-row';
      row.innerHTML = `
        <div class="check-item-left">
          <input type="checkbox" class="check-checkbox" id="item-chk-${item.id}">
          <img src="${item.image}" alt="${item.title}" class="check-item-img">
          <div>
            <div class="check-item-title">${item.title}</div>
            <div class="check-item-meta">${item.variant} • Артикул: <strong>${item.article}</strong> • Штрих-код: ${item.barcode}</div>
          </div>
        </div>
        <div class="check-item-qty">${item.amount} шт.</div>
      `;

      // Checkbox visual toggle
      const chk = row.querySelector('.check-checkbox');
      chk.addEventListener('change', () => {
        row.classList.toggle('verified', chk.checked);
      });

      itemsContainer.appendChild(row);
    });
  }

  // Payment Status Box
  const paymentStatusBadge = document.getElementById('payment-status-badge');
  const paymentOptionsBlock = document.getElementById('payment-choice-block');
  if (paymentStatusBadge) {
    if (order.isPaid) {
      paymentStatusBadge.className = 'badge badge-success';
      paymentStatusBadge.innerHTML = '<i class="bi bi-shield-check"></i> ' + order.paymentMethodTitle;
      if (paymentOptionsBlock) paymentOptionsBlock.style.display = 'none';
    } else {
      paymentStatusBadge.className = 'badge badge-warning';
      paymentStatusBadge.innerHTML = '<i class="bi bi-clock"></i> Ожидает оплаты на кассе';
      if (paymentOptionsBlock) paymentOptionsBlock.style.display = 'block';
    }
  }

  // Reset complete button state
  const completeBtn = document.getElementById('complete-pickup-btn');
  if (completeBtn) {
    if (order.status === 'delivered') {
      completeBtn.disabled = true;
      completeBtn.innerHTML = '<i class="bi bi-check2-circle"></i> Заказ уже был выдан ранее';
      completeBtn.className = 'btn btn-secondary btn-complete-fulfillment';
    } else {
      completeBtn.disabled = false;
      completeBtn.innerHTML = '<i class="bi bi-box-seam"></i> Подтвердить и выдать заказ';
      completeBtn.className = 'btn btn-brand btn-complete-fulfillment';
    }
  }

  // Update receipt modal contents
  updateReceiptModal(order);
}

function updateReceiptModal(order) {
  const receiptNum = document.getElementById('receipt-order-num');
  if (receiptNum) receiptNum.textContent = order.orderNumber;

  const receiptDate = document.getElementById('receipt-date');
  if (receiptDate) receiptDate.textContent = new Date().toLocaleString('ru-RU');

  const receiptItems = document.getElementById('receipt-items-list');
  if (receiptItems) {
    receiptItems.innerHTML = order.items.map(item => `
      <div class="receipt-line">
        <span>${item.title} (${item.variant})</span>
      </div>
      <div class="receipt-line" style="color: #666; font-size: 11px;">
        <span>${item.amount} шт x ${item.price.toLocaleString('ru-RU')} ₽</span>
        <span>${(item.amount * item.price).toLocaleString('ru-RU')} ₽</span>
      </div>
    `).join('');
  }

  const receiptTotal = document.getElementById('receipt-total-sum');
  if (receiptTotal) receiptTotal.textContent = `${order.total.toLocaleString('ru-RU')} ₽`;
}

function addOrderToTodayLog() {
  const todayTable = document.getElementById('today-log-tbody');
  if (!todayTable) return;

  const timeStr = new Date().toLocaleTimeString('ru-RU', {
    hour: '2-digit',
    minute: '2-digit'
  });
  const newRow = document.createElement('tr');
  newRow.innerHTML = `
    <td><strong>SW-2026-0002</strong></td>
    <td>${timeStr}</td>
    <td>Анна Меломанова</td>
    <td>1 товар</td>
    <td>41 990 ₽</td>
    <td><span class="badge badge-success">Выдан</span></td>
    <td>
      <button class="table-action-btn" title="Печать чека" onclick="openModal('receipt-modal')">
        <i class="bi bi-printer"></i>
      </button>
    </td>
  `;
  todayTable.insertBefore(newRow, todayTable.firstChild);
}

async function getProducts(params) {

  const response = fetch('/api/catalog.php', {
      method: 'POST',
      body: prepareData(params),
    })
    .then(response => response.json())
    .then(data => {
      return data
    })
    .catch(error => {
      showToast(error);
    });

  return response
}

function getFilterParams() {
  const params = new URLSearchParams(window.location.search);

  const filterParams = {
    query: params.get('q'),
    category: params.get('cat'),
    brand: params.get('brand'),
    sort: params.get('sort'),
    selectedCats: '',
    selectedBrands: '',
    minP: 0,
    maxP: 1000000,
  }
  return filterParams;
}

async function getFilters(params) {

  const response = fetch('/api/catalog_filters.php', {
      method: 'POST',
      body: prepareData(params),
    })
    .then(response => response.json())
    .then(data => {
      return data
    })
    .catch(error => {
      showToast(error);
    });

  return response
}

function prepareData(data) {
  const formData = new FormData();
  for (const key in data) {
    if (data.hasOwnProperty(key)) {
      formData.append(key, data[key]);
    }
  }
  return formData
}