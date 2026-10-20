<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Корзина | SOUNDWAVE</title>
  <meta name="description" content="Оформление заказа в магазине музыкальных инструментов SOUNDWAVE.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap-grid.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/common.css">
  <link rel="stylesheet" href="css/cart.css">
</head>

<body>

  <?php include('./components/header.php') ?>

  <nav class="site-breadcrumbs">
    <div class="container">
      <ul class="breadcrumbs-list">
        <li><a href="/">Главная</a></li>
        <li>Корзина</li>
      </ul>
    </div>
  </nav>

  <main>
    <div class="container">
      <div style="padding-top: 36px;">
        <h1 class="section-title mb-1">Корзина</h1>
        <p class="section-desc">Проверьте состав заказа перед переходом к оплате</p>
      </div>

      <div class="empty-cart-view" id="empty-cart-view">
        <i class="bi bi-bag-x"></i>
        <h2>Ваша корзина пуста</h2>
        <p style="color: var(--text-secondary); margin-bottom: 24px;">Выберите подходящий инструмент из нашего каталога
        </p>
        <a href="catalog.php" class="btn-brand">Перейти в каталог</a>
      </div>

      <div class="cart-layout" id="cart-layout-container">
        <div class="cart-items-list" id="cart-items-container"></div>

        <aside class="cart-summary-card">
          <div class="summary-title">Ваш заказ</div>

          <div class="summary-row">
            <span>Товары (<span id="summary-items-count">0</span>):</span>
            <strong id="summary-subtotal">0 ₽</strong>
          </div>

          <div class="summary-row">
            <span>Доставка по РФ:</span>
            <strong style="color: var(--accent-green);">Бесплатно</strong>
          </div>

          <div class="summary-row" id="discount-row" style="display: none;">
            <span>Скидка по промокоду:</span>
            <strong style="color: var(--yellow-main);" id="discount-amount">-0 ₽</strong>
          </div>

          <div class="summary-divider"></div>

          <div class="summary-total-row">
            <span>Итого:</span>
            <span class="summary-total-price" id="summary-grand-total">0 ₽</span>
          </div>

          <div class="promo-box">
            <div style="font-size: 13px; font-weight: 600; margin-bottom: 8px;">Промокод на скидку:</div>
            <form class="promo-form" id="promo-form" onsubmit="applyPromo(event)">
              <input type="text" id="promo-input" placeholder="Введите SOUNDWAVE">
              <button type="submit" class="btn-wenge">Применить</button>
            </form>
          </div>

          <a href="checkout.html" class="btn-brand" style="width: 100%;">
            Оформить заказ
            <i class="bi bi-arrow-right"></i>
          </a>

          <a href="catalog.php" class="btn-wenge" style="width: 100%; text-align: center;">
            Продолжить покупки
          </a>
        </aside>
      </div>
    </div>
  </main>

  <?php include('./components/footer.php') ?>

  <script src="js/main.js"></script>
  <script>
    let appliedDiscountPercent = 0;

    async function renderCartPage() {
      const cart = await getCart();
      const container = document.querySelector('#cart-items-container');
      const emptyView = document.querySelector('#empty-cart-view');
      const layout = document.querySelector('#cart-layout-container');
      if (!cart || cart.length === 0) {
        if (emptyView) emptyView.style.display = 'block';
        if (layout) layout.style.display = 'none';
        return;
      }

      if (emptyView) emptyView.style.display = 'none';
      if (layout) layout.style.display = 'grid';

      let html = '';
      let subtotal = 0;
      let totalItems = 0;

      cart.forEach(item => {
        const itemTotal = item.price * item.amount;
        subtotal += itemTotal;
        totalItems += item.amount;

        html += `
          <div class="cart-item-card" pid='${item.productId}-${item.variantId}'>
            <div class="cart-item-thumb">
              <img src="${item.image}" alt="soundwave">
            </div>
            <div class="cart-item-info">
              <div class="cart-item-category">${item.category}</div>
              <a href="product.php?id=${item.productId}" class="cart-item-title">${item.productTitle}</a>
              <div class="cart-item-price-each">${formatPrice(item.price)} / шт.</div>
            </div>
            <div class="quantity-picker">
              <button class="qty-btn" type="button" onclick="updateItemQty(${item.variant_id}, -1)"><i class="bi bi-dash"></i></button>
              <input type="number" value="${item.amount}" readonly>
              <button class="qty-btn" type="button" onclick="updateItemQty(${item.variant_id}, 1)"><i class="bi bi-plus"></i></button>
            </div>
            <div class="cart-item-controls">
              <div class="cart-item-total">${formatPrice(itemTotal)}</div>
              <div class="cart-item-actions">
                <button class="cart-action-btn" card-btn="trans-fav" title="В избранное">
                  <i class="bi bi-heart"></i>
                </button>
                <button class="cart-action-btn btn-remove" card-btn="cart-remove" title="Удалить">
                  <i class="bi bi-trash3"></i>
                </button>
              </div>
            </div>
          </div>
        `;
      });

      container.innerHTML = html;

      const discountVal = Math.round(subtotal * (appliedDiscountPercent / 100));
      const grandTotal = subtotal - discountVal;

      document.querySelector('#summary-items-count').textContent = totalItems;
      document.querySelector('#summary-subtotal').textContent = formatPrice(subtotal);
      document.querySelector('#summary-grand-total').textContent = formatPrice(grandTotal);

      const discountRow = document.querySelector('#discount-row');
      if (appliedDiscountPercent > 0) {
        discountRow.style.display = 'flex';
        document.querySelector('#discount-amount').textContent = '-' + formatPrice(discountVal);
      } else {
        discountRow.style.display = 'none';
      }
    }

    async function updateItemQty(id, delta) {
      const item = 

      fetch('/api/v1/cart/update', {
        method: 'POST',
        body: prepareData({
        'variant_id': id,
        'delta': delta
      }),
      })
        .then(response => response.json())
        .then(data => {
          renderCartPage();
        })
        .catch(error => {
          showToast(error);
        });
    }

    function transferToFavs(id) {
      removeCartItem(id);
      let favs = getStoredFavs();
      if (!favs.includes(id)) {
        favs.push(id);
        setStoredFavs(favs);
      }
      showToast('Перенесено в избранное');
    }

    function applyPromo(e) {
      e.preventDefault();
      const code = document.querySelector('#promo-input').value.trim().toUpperCase();
      if (code === 'SOUNDWAVE' || code === 'PROMO') {
        appliedDiscountPercent = 10;
        showToast('Промокод применён: скидка 10%');
        renderCartPage();
      } else {
        showToast('Неверный промокод');
      }
    }

    document.addEventListener('DOMContentLoaded', () => {
      renderCartPage();
    });
  </script>
</body>

</html>