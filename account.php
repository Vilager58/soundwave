<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Личный кабинет | SOUNDWAVE</title>
  <meta name="description"
    content="Управление заказами, бонусами и профилем в интернет-магазине музыкальных инструментов SOUNDWAVE.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap-grid.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/common.css">
  <link rel="stylesheet" href="css/account.css">
</head>

<body>
  <?php require('./components/header.php') ?>

  <nav class="site-breadcrumbs">
    <div class="container">
      <ul class="breadcrumbs-list">
        <li><a href="/">Главная</a></li>
        <li>Личный кабинет</li>
      </ul>
    </div>
  </nav>

  <main>
    <div class="container">
      <div class="account-layout">
        <aside class="account-sidebar">
          <div class="user-profile-badge">
            <div style="min-width: 0;">
              <strong id="user-display-name" style="display: block; font-size: 15px; word-break: break-word;"></strong>
              <span id="user-display-email"
                style="font-size: 12px; color: var(--text-muted); word-break: break-all;"></span>
            </div>
          </div>

          <ul class="account-menu">
            <li><a href="#" class="active" onclick="switchAccountPane(event, 'orders-pane')"><i class="bi bi-box2"></i>
                Мои заказы (<span id="menu-orders-count">0</span>)</a></li>
            <li><a href="#" onclick="switchAccountPane(event, 'profile-pane')"><i class="bi bi-person-gear"></i>
                Настройки профиля</a></li>
            <li><a href="#" onclick="switchAccountPane(event, 'recent-pane')"><i class="bi bi-clock-history"></i>
                Недавно смотрели</a></li>
            <li><a href="favorites.html"><i class="bi bi-heart"></i> Избранное (<span class="fav-count-badge"
                  style="display: inline-flex; font-size: 11px;">0</span>)</a></li>
            <li><a href="cart.html"><i class="bi bi-bag"></i> Корзина (<span class="cart-count-badge"
                  style="display: inline-flex; font-size: 11px;">0</span>)</a></li>
            <li><a href="#" onclick="logoutUser(event)" style="color: var(--accent-red); margin-top: 16px;"><i
                  class="bi bi-box-arrow-right"></i> Выйти</a></li>
          </ul>
        </aside>

        <section style="min-width: 0;">
          <div style="margin-bottom: 28px;">
            <h1 class="section-title mb-1" id="account-welcome-heading" style="font-size: 28px;">Личный кабинет</h1>
            <p class="section-desc mb-0" id="account-welcome-sub"></p>
          </div>

          <div class="account-stats-grid">
            <div class="stat-card">
              <span class="stat-card-title">Всего заказов</span>
              <span class="stat-card-val" id="stat-orders-count">0</span>
            </div>
            <div class="stat-card">
              <span class="stat-card-title">Бонусный баланс</span>
              <span class="stat-card-val" id="stat-bonuses-val">0 ₽</span>
            </div>
            <div class="stat-card">
              <span class="stat-card-title">В избранном</span>
              <span class="stat-card-val" id="stat-favs-count">0</span>
            </div>
            <div class="stat-card">
              <span class="stat-card-title">Персональная скидка</span>
              <span class="stat-card-val" id="stat-personal-bonus">0%</span>
            </div>
          </div>

          <div class="account-section-pane active" id="orders-pane">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
              <h2 style="font-size: 20px; font-weight: 800; margin: 0;">История заказов</h2>
              <a href="catalog.html" class="btn-brand" style="font-size: 13px; padding: 8px 16px;">
                <i class="bi bi-plus-lg"></i> Новый заказ
              </a>
            </div>

            <div id="account-orders-list">

            </div>
          </div>
          <div class="account-section-pane" id="profile-pane">
            <div class="profile-card">
              <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 20px;">Редактирование профиля</h2>
              <form id="profile-edit-form" onsubmit="handleSaveProfile(event)">
                <div class="profile-form-grid">
                  <div class="form-field">
                    <label for="prof-name"
                      style="display: block; font-size: 13px; color: var(--text-secondary); margin-bottom: 6px;">ФИО
                      *</label>
                    <input type="text" id="prof-name" required
                      style="width: 100%; background: var(--wenge-surface); border: 1px solid var(--wenge-border); border-radius: var(--radius-sm); padding: 10px 14px; color: var(--text-primary); outline: none;">
                  </div>

                  <div class="form-field">
                    <label for="prof-phone"
                      style="display: block; font-size: 13px; color: var(--text-secondary); margin-bottom: 6px;">Номер
                      телефона</label>
                    <input type="text" id="prof-phone" placeholder="+7 (___) ___-__-__"
                      style="width: 100%; background: var(--wenge-surface); border: 1px solid var(--wenge-border); border-radius: var(--radius-sm); padding: 10px 14px; color: var(--text-primary); outline: none;">
                  </div>

                  <div class="form-field">
                    <label for="prof-instrument"
                      style="display: block; font-size: 13px; color: var(--text-secondary); margin-bottom: 6px;">Основной
                      инструмент</label>
                    <input type="text" id="prof-instrument" placeholder="Свирель"
                      style="width: 100%; background: var(--wenge-surface); border: 1px solid var(--wenge-border); border-radius: var(--radius-sm); padding: 10px 14px; color: var(--text-primary); outline: none;">
                  </div>

                  <div class="form-field">
                    <label for="prof-city"
                      style="display: block; font-size: 13px; color: var(--text-secondary); margin-bottom: 6px;">Город
                      доставки</label>
                    <input type="text" id="prof-city" placeholder="Сыктывкар"
                      style="width: 100%; background: var(--wenge-surface); border: 1px solid var(--wenge-border); border-radius: var(--radius-sm); padding: 10px 14px; color: var(--text-primary); outline: none;">
                  </div>

                  <div class="form-field">
                    <label for="prof-address"
                      style="display: block; font-size: 13px; color: var(--text-secondary); margin-bottom: 6px;">Адрес
                      (улица, дом, кв)</label>
                    <input type="text" id="prof-address" placeholder="ул. Суетная, д. 67"
                      style="width: 100%; background: var(--wenge-surface); border: 1px solid var(--wenge-border); border-radius: var(--radius-sm); padding: 10px 14px; color: var(--text-primary); outline: none;">
                  </div>

                  <div class="form-field">
                    <label for="prof-birthdate"
                      style="display: block; font-size: 13px; color: var(--text-secondary); margin-bottom: 6px;">Дата
                      рождения</label>
                    <input type="date" id="prof-birthdate" placeholder="10.07.2006"
                      style="width: 100%; background: var(--wenge-surface); border: 1px solid var(--wenge-border); border-radius: var(--radius-sm); padding: 10px 14px; color: var(--text-primary); outline: none;">
                  </div>
                </div>

                <div class="d-flex gap-3 align-items-center justify-content-between">
                  <button type="submit" class="btn-brand">
                    <i class="bi bi-floppy"></i> Сохранить изменения
                  </button>
                  <span style="font-size: 13px; color: var(--text-muted);">Данные автоматически синхронизируются с
                    оформлением заказов</span>
                </div>
              </form>
            </div>
          </div>

          <div class="account-section-pane" id="recent-pane">
            <h2 style="font-size: 20px; font-weight: 800; margin-bottom: 20px;">Недавно просмотренные товары</h2>
            <div class="row g-4" id="account-recent-grid"></div>
          </div>
        </section>
      </div>
    </div>
  </main>

  <footer class="site-footer">
    <div class="container">
      <div class="footer-bottom">
        <div>© 2026 SOUNDWAVE. Все права защищены.</div>
        <div>Сыктывкар, Суетная 67</div>
      </div>
    </div>
  </footer>

  <script src="js/main.js"></script>
  <script>

    async function logoutUser(e) {
      if (e) e.preventDefault();

      fetch('/api/logout.php', {
        method: 'POST',
        body: {}
      })
        .then(response => response.json())
        .then(data => {
          if (data.status == 'error') {
            showToast(data.message);
          } else {
            showToast(data.message);
            setTimeout(() => {
              window.location.href = 'login.php';
            }, 700);
          }
        })
        .catch(error => {
          showToast(error);
        });
    }

    async function switchAccountPane(e, paneId) {
      if (e) e.preventDefault();
      document.querySelectorAll('.account-menu a').forEach(a => a.classList.remove('active'));
      if (e && e.currentTarget) e.currentTarget.classList.add('active');

      document.querySelectorAll('.account-section-pane').forEach(p => p.classList.remove('active'));
      const target = document.querySelector('#' + paneId);
      if (target) target.classList.add('active');
    }

    async function copyTrackNumber(trackNum) {
      if (navigator.clipboard) {
        navigator.clipboard.writeText(trackNum).then(() => {
          showToast(`Трек-номер ${trackNum} скопирован!`);
        }).catch(() => {
          showToast(`Трек-номер: ${trackNum}`);
        });
      } else {
        showToast(`Трек-номер: ${trackNum}`);
      }
    }

    async function renderAccountOrders(orders) {
      const container = document.querySelector('#account-orders-list');
      const menuOrdersCount = document.querySelector('#menu-orders-count');
      const statOrdersCount = document.querySelector('#stat-orders-count');

      if (menuOrdersCount) menuOrdersCount.textContent = orders.length;
      if (statOrdersCount) statOrdersCount.textContent = orders.length;

      if (!container) return;

      if (orders.length === 0) {
        container.innerHTML = `
          <div style="text-align: center; padding: 60px 20px; background: var(--wenge-card); border-radius: var(--radius-lg); border: 1px solid var(--wenge-border);">
            <i class="bi bi-box-seam" style="font-size: 48px; color: var(--yellow-main); margin-bottom: 16px; display: inline-block;"></i>
            <h3>У вас пока нет заказов</h3>
            <p style="color: var(--text-secondary); margin-bottom: 24px;">Выберите подходящий инструмент в нашем каталоге</p>
            <a href="catalog.html" class="btn-brand">Перейти в каталог</a>
          </div>
        `;
        return;
      }

      let html = '';
      orders.forEach(order => {
        const isDelivered = order.status === 'delivered';
        const isDelivering = order.status === 'delivering';
        const isAssembled = order.status === 'assembled';
        const isProcessing = order.status === 'processing';

        let badgeClass = 'status-processing';
        if (isDelivered) badgeClass = 'status-delivered';
        else if (isDelivering) badgeClass = 'status-delivering';

        html += `
          <div class="order-single-card">
            <div class="order-header-row">
              <div>
                <strong style="font-size: 18px; margin-right: 12px; color: var(--text-primary);">Заказ № ${order.id}</strong>
                <span style="font-size: 13px; color: var(--text-muted);">от ${order.date}</span>
              </div>
              <span class="order-status-badge ${badgeClass}">${order.statusLabel || 'В обработке'}</span>
            </div>

            <div class="order-progress-track">
            <div class="track-step ${isProcessing || isAssembled || isDelivering || isDelivered ? 'done' : 'active'}">
                <div class="track-step-dot"><i class="bi bi-check"></i></div>
                <span>Оплачен</span>
              </div>
              <div class="track-step ${isProcessing || isAssembled || isDelivering || isDelivered ? 'done' : 'active'}">
                <div class="track-step-dot"><i class="bi bi-check"></i></div>
                <span>Оформлен</span>
              </div>
              <div class="track-step ${isAssembled || isDelivering || isDelivered ? 'done' : (isProcessing ? 'active' : '')}">
                <div class="track-step-dot"><i class="bi bi-box-seam"></i></div>
                <span>Собран</span>
              </div>
              <div class="track-step ${isDelivered ? 'done' : (isDelivering ? 'active' : '')}">
                <div class="track-step-dot"><i class="bi bi-truck"></i></div>
                <span>В доставке</span>
              </div>
              <div class="track-step ${isDelivered ? 'done active' : ''}">
                <div class="track-step-dot"><i class="bi bi-house-check"></i></div>
                <span>Получен</span>
              </div>
            </div>

            <!-- Состав заказа -->
            <div style="background: var(--wenge-surface); border-radius: var(--radius-md); padding: 16px; margin-bottom: 20px;">
              ${order.items.map(item => `
                <div class="d-flex justify-content-between align-items-center mb-2" style="font-size: 14px; gap: 12px;">
                  <div class="d-flex align-items-center gap-2" style="min-width: 0;">
                    ${item.image ? `<img src="${item.image}" alt="${item.title}" style="width: 32px; height: 32px; object-fit: cover;; background: #111; border-radius: 4px; flex-shrink: 0; margin-right: 8px;">` : ''}
                    <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${item.title} × ${item.quantity}</span>
                  </div>
                  <strong style="white-space: nowrap; color: var(--text-primary);">${formatPrice(item.price * item.quantity)}</strong>
                </div>
              `).join('')}
              <div style="font-size: 12px; color: var(--text-muted); border-top: 1px solid var(--wenge-border); padding-top: 10px; margin-top: 10px;">
                <i class="bi bi-geo-alt"></i> ${order.deliveryAddress || 'Доставка СДЭК'} • ${order.paymentMethod || 'Онлайн оплата'}
              </div>
            </div>

            <!-- Итог и кнопки действий -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
              <div style="font-size: 15px;">
                Сумма заказа: <strong style="font-size: 19px; color: var(--yellow-main);">${formatPrice(order.total)}</strong>
              </div>
              <div class="d-flex gap-2 flex-wrap">
                ${order.trackNumber ? `
                  <button class="btn-wenge" onclick="copyTrackNumber('${order.trackNumber}')">
                    <i class="bi bi-clipboard"></i> Трек-номер (${order.trackNumber})
                  </button>
                ` : ''}
                <button class="btn-brand" onclick="repeatOrder('${order.id}')" title="Добавить товары из заказа в корзину">
                  <i class="bi bi-arrow-repeat"></i> Повторить заказ
                </button>
              </div>
            </div>
          </div>
        `;
      });

      container.innerHTML = html;
    }

    async function renderAccountRecent() {
      const grid = document.querySelector('#account-recent-grid');
      if (!grid) return;

      const recentIds = getRecentProducts();
      if (recentIds.length === 0) {
        grid.innerHTML = '<div class="col-12" style="color: var(--text-secondary);">Вы пока не просматривали товары.</div>';
        return;
      }

      let html = '';
      recentIds.forEach(id => {
        const p = SOUNDWAVE_DATA.products.find(item => item.id === id);
        if (!p) return;
        html += `
          <div class="col-lg-4 col-sm-6">
            <div class="product-card">
              <div class="product-image-box" style="height: 180px;">
                <button class="product-fav-btn" data-id="${p.id}" aria-label="В избранное">
                  <i class="bi bi-heart"></i>
                </button>
                <a href="product.html?id=${p.id}">
                  <img src="${p.image}" alt="${p.title}" style="max-height: 100%; object-fit: cover;;">
                </a>
              </div>
              <div class="product-content">
                <span class="product-category-tag">${p.category}</span>
                <a href="product.html?id=${p.id}" class="product-title" style="font-size: 15px;">${p.title}</a>
                <div class="product-price-row">
                  <span class="current-price" style="font-size: 17px;">${formatPrice(p.price)}</span>
                  <button class="btn-add-cart" data-add-cart="${p.id}" title="В корзину">
                    <i class="bi bi-bag-plus"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        `;
      });
      grid.innerHTML = html;
      updateFavBadges();
    }

    async function handleSaveProfile(e) {
      e.preventDefault();
      const user = await getCurrentUser();
      const uname = document.querySelector('#prof-name').value.trim().split(' ');
      user.lastname = uname[0] || '';
      user.firstname = uname[1] || '';
      user.patronymic = uname[2] || '';
      user.phone = document.querySelector('#prof-phone').value.trim();
      user.city = document.querySelector('#prof-city').value.trim();
      user.adress = document.querySelector('#prof-address').value.trim();
      user.instrument = document.querySelector('#prof-instrument').value;
      user.birthdate = document.querySelector('#prof-birthdate').value;
      
      const formData = new FormData();
      for (const key in user) {
        if (user.hasOwnProperty(key)) {
          formData.append(key, user[key]);
        }
      }

      fetch('/api/user.php', {
        method: 'POST',
        body: formData,
      })
        .then(response => response.json())
        .then(data => {
          showToast('Данные профиля успешно сохранены');
        })
        .catch(error => {
          showToast(error);
        });

      loadUserProfile();

    }

    function loadUserProfile(user) {
      const firstName = user.name ? user.name.split(' ')[0] : 'Музыкант';
      const heading = document.querySelector('#account-welcome-heading');
      if (heading) heading.textContent = `Добро пожаловать, ${firstName}!`;

      const sub = document.querySelector('#account-welcome-sub');
      if (sub) sub.textContent = `Инструмент: ${user.instrument || 'Электрогитара'} • Город: ${user.city || 'Сыктывкар'}`;

      const nameEl = document.querySelector('#user-display-name');
      if (nameEl) nameEl.textContent = user.name.split(' ')[0] || 'Музыкант SOUNDWAVE';

      const emailEl = document.querySelector('#user-display-email');
      if (emailEl) emailEl.textContent = user.email || 'user@example.com';

      const initialsEl = document.querySelector('#user-avatar-initials');
      if (initialsEl && user.name) {
        const parts = user.name.split(' ');
        const initials = parts.length > 1 ? (parts[0][0] + parts[1][0]).toUpperCase() : parts[0][0].toUpperCase();
        initialsEl.textContent = initials;
      }

      const bonusVal = document.querySelector('#stat-bonuses-val');
      if (bonusVal) bonusVal.textContent = (user.bonuses || 0).toLocaleString() + ' ₽';

      const favs = getStoredFavs();
      const favsVal = document.querySelector('#stat-favs-count');
      if (favsVal) favsVal.textContent = favs.length;

      if (document.querySelector('#prof-name')) document.querySelector('#prof-name').value = user.name || '';
      if (document.querySelector('#prof-email')) document.querySelector('#prof-email').value = user.email || '';
      if (document.querySelector('#prof-phone')) document.querySelector('#prof-phone').value = user.phone || '';
      if (document.querySelector('#prof-city')) document.querySelector('#prof-city').value = user.city || '';
      if (document.querySelector('#prof-address')) document.querySelector('#prof-address').value = user.address || '';
      if (document.querySelector('#prof-instrument') && user.instrument) {
        document.querySelector('#prof-instrument').value = user.instrument;
      }
    }

    async function init() {
      loadUserProfile(await getCurrentUser());
      renderAccountOrders(await getStoredOrders());
      renderAccountRecent();
    }

    document.addEventListener('DOMContentLoaded', () => {
      init();
    });
  </script>
</body>

</html>