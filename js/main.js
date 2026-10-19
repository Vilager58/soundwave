


async function getUser() {
  response = await fetch('/api/v1/user', {
      method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
      return data;
    })
    .catch(error => {
      showToast(error);
    });
  return response;
}

async function getUserCounts() {
  response = await fetch('/api/v1/user/counts', {
      method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
      return data;
    })
    .catch(error => {
      showToast(error);
    });
  return response;
}

async function getCart() {
  response = await fetch('/api/v1/cart', {
      method: 'GET',
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

async function addToCart(variantId, quantity = 1) {
  response = await fetch('/api/v1/cart/add', {
      method: 'POST',
      body: prepareData({
        variantId: variantId,
        amount: quantity
      })
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

async function removeFromCart(variantId) {
  response = await fetch('/api/v1/cart/remove', {
      method: 'POST',
      body: prepareData({
        variantId: variantId
      })
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

async function getFavs() {
  response = await fetch('/api/v1/favorites', {
      method: 'GET',
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

async function addToFavs(variantId) {
  response = await fetch('/api/v1/favorites/add', {
      method: 'POST',
      body: prepareData({
        variantId: variantId
      })
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

async function removeFromFavs(variantId) {
  response = await fetch('/api/v1/favorites/remove', {
      method: 'POST',
      body: prepareData({
        variantId: variantId
      })
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

async function getProducts(params) {
  const url = new URL(window.location.origin + '/api/v1/catalog');
  url.search = new URLSearchParams(params);
  const response = fetch(
      url, {
        method: 'GET',
      }
    )
    .then(response => response.json())
    .then(data => {
      return data
    })
    .catch(error => {
      showToast(error);
    });

  return response
}

async function getProduct(id) {
  const url = new URL(window.location.origin + '/api/v1/product');
  url.search = new URLSearchParams({
    id: id
  });
  const response = fetch(
      url, {
        method: 'GET',
      }
    )
    .then(response => response.json())
    .then(data => {
      return data
    })
    .catch(error => {
      showToast(error);
    });

  return response
}

async function getFilters(params) {

  const response = fetch('/api/v1/catalog/filters', {
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

async function updateHeaderBadges(handledIds) {
  document.querySelectorAll(".fav-count-badge").forEach((badge) => {
    badge.textContent = handledIds.favIds.length;
    badge.style.display = handledIds.favIds.length > 0 ? "flex" : "none";
  });
  document.querySelectorAll(".cart-count-badge").forEach((badge) => {
    badge.textContent = handledIds.cartIds.length;
    badge.style.display = handledIds.cartIds.length > 0 ? "flex" : "none";
  });
}

async function getCurrentUser() {
  response = await fetch('/api/v1/user', {
      method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
      return data
    })
    .catch(error => {
      showToast(error);
    });
  return response
};


async function getOrders() {
  response = await fetch('/api/v1/user/orders', {
      method: 'GET',
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

function MakeOrder(order) {

}

function repeatOrder(orderId) {
  const orders = getOrders();
  const order = orders.find(o => o.id === orderId);
  if (!order) return;
  const cart = getStoredCart();
  order.items.forEach(it => {
    const ex = cart.find(c => c.id === it.id);
    if (ex) {
      ex.quantity += it.quantity;
    } else {
      cart.push({
        id: it.id,
        quantity: it.quantity
      });
    }
  });
  showToast("Товары из заказа добавлены в корзину!");
  setTimeout(() => {
    window.location.href = "cart.php";
  }, 700);
}

function getRecentProducts() {
  const data = localStorage.getItem("soundwave_recent");
  if (data) {
    try {
      return JSON.parse(data);
    } catch (e) {
      return [];
    }
  }
}

function addRecentProduct(productId) {
  let list = getRecentProducts();
  list = list.filter(id => id !== productId);
  list.unshift(productId);
  if (list.length > 8) list = list.slice(0, 8);
  localStorage.setItem("soundwave_recent", JSON.stringify(list));
}

function getProductReviews(productId) {
  const defaultReviews = {
    "fender-strat": [{
        author: "Михаил Р.",
        date: "2 марта 2026",
        rating: 5,
        text: "Гитара превзошла все ожидания! Звук потрясающий, сустейн бесконечный. Отделка 3-Color Sunburst идеальная.",
        verified: true
      },
      {
        author: "Артём Ковалев",
        date: "24 февраля 2026",
        rating: 5,
        text: "Классический стратовский звук с датчиками Alnico 5. Гриф приятный, лады закатаны без нареканий.",
        verified: true
      },
      {
        author: "Сергей В.",
        date: "15 января 2026",
        rating: 4,
        text: "Отличный инструмент для сцены и студии. Заводская отстройка почти идеальная, чуть опустил струны.",
        verified: false
      }
    ],
    "gibson-les-paul": [{
        author: "Денис Г.",
        date: "5 марта 2026",
        rating: 5,
        text: "Настоящий Gibson с плотным мощным низом. В перегрузе поёт великолепно!",
        verified: true
      },
      {
        author: "Владимир",
        date: "18 февраля 2026",
        rating: 5,
        text: "Сустейн и хамбакеры 490R/498T творят чудеса. Очень доволен покупкой в SOUNDWAVE.",
        verified: true
      }
    ],
    "yamaha-p225": [{
        author: "Елена Смирнова",
        date: "27 февраля 2026",
        rating: 5,
        text: "Клавиатура GHC очень отзывчивая, звук рояля CFX глубокий и естественный. Для занятий дома — лучший выбор.",
        verified: true
      },
      {
        author: "Павел К.",
        date: "10 февраля 2026",
        rating: 5,
        text: "Компактный размер, качественная сборка, подключение по USB без задержек.",
        verified: true
      }
    ],
    "roland-juno-d": [{
      author: "Максим DJ",
      date: "4 марта 2026",
      rating: 5,
      text: "Движок ZEN-Core выдаёт невероятные текстуры! Лёгкий, питание от USB-C позволяет репетировать везде.",
      verified: true
    }],
    "shure-sm58": [{
        author: "Игорь Новиков",
        date: "4 марта 2026",
        rating: 5,
        text: "Легенда! Неубиваемый микрофон, вокал звучит плотно и пробивно в любом миксе.",
        verified: true
      },
      {
        author: "Анна Вокальная",
        date: "19 февраля 2026",
        rating: 5,
        text: "Использую в студии и на лайвах. Никакой обратной связи, надёжность 100%.",
        verified: true
      }
    ],
    "marshall-mg30gfx": [{
      author: "Дмитрий рок",
      date: "1 марта 2026",
      rating: 5,
      text: "Отличный комбарь для квартиры и гаража. Эффекты звучат сочно, 30 Вт хватает с запасом.",
      verified: true
    }],
    "tama-imperialstar": [{
      author: "Константин Ударный",
      date: "6 марта 2026",
      rating: 5,
      text: "Тополь звучит сбалансированно, стойки массивные. Комплект тарелок Meinl в комплекте — супербонус!",
      verified: true
    }],
    "yamaha-pacifica": [{
      author: "Олег Б.",
      date: "20 февраля 2026",
      rating: 5,
      text: "Лучшая первая и рабочая гитара. Отсечка на бриджевом хамбакере даёт массу вариантов звучания.",
      verified: true
    }]
  };

  const stored = localStorage.getItem("soundwave_reviews");
  let allStored = {};
  if (stored) {
    try {
      allStored = JSON.parse(stored);
    } catch (e) {}
  }

  const custom = allStored[productId] || [];
  const base = defaultReviews[productId] || [{
    author: "Покупатель SOUNDWAVE",
    date: "1 марта 2026",
    rating: 5,
    text: "Отличный инструмент, всё соответствует заявленным характеристикам!",
    verified: true
  }];

  return [...custom, ...base];
}

function addProductReview(productId, review) {
  const stored = localStorage.getItem("soundwave_reviews");
  let allStored = {};
  if (stored) {
    try {
      allStored = JSON.parse(stored);
    } catch (e) {}
  }
  if (!allStored[productId]) allStored[productId] = [];
  allStored[productId].unshift({
    author: review.author || "Музыкант SOUNDWAVE",
    date: "Сегодня",
    rating: Number(review.rating) || 5,
    text: review.text,
    verified: true
  });
  localStorage.setItem("soundwave_reviews", JSON.stringify(allStored));
  showToast("Спасибо! Ваш отзыв опубликован");
}

function formatPrice(num) {
  return (num || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + " ₽";
}

function showToast(message) {
  let container = document.querySelector(".toast-container");
  if (!container) {
    container = document.createElement("div");
    container.className = "toast-container";
    document.body.appendChild(container);
  }
  const toast = document.createElement("div");
  toast.className = "toast-msg";
  toast.innerHTML = `<i class="bi bi-check-circle-fill"></i><span>${message}</span>`;
  container.appendChild(toast);
  setTimeout(() => {
    toast.style.transition = "opacity 0.4s ease, transform 0.4s ease";
    toast.style.opacity = "0";
    toast.style.transform = "translateY(20px)";
    setTimeout(() => toast.remove(), 400);
  }, 2400);
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

async function initEvents() {
  document.addEventListener('click', async (e) => {
    // const tabButton = e.target.closest('.tab-btn');
    // if (tabButton) {
    //   const navtab = e.target.closest('.nav-tabs');
    //   const tabButtons = navtab.querySelectorAll('.tab-btn');
    //   const tabLevel = tabButton.getAttribute('data-tab-level');
    //   const targetId = tabButton.getAttribute('data-tab');
    //   const container = navtab.closest('.modal-body') || navtab.closest('.admin-card') || document;

    //   tabButtons.forEach(btn => {
    //     if (btn.getAttribute('data-tab-level') == tabLevel) btn.classList.remove('active');
    //   });
    //   tabButton.classList.add('active');

    //   const panes = container.querySelectorAll('.tab-pane');
    //   panes.forEach(pane => {
    //     if (pane.getAttribute('data-tab-level') == tabLevel) {
    //       if (pane.id === targetId) {
    //         pane.classList.add('active');
    //       } else {
    //         pane.classList.remove('active');
    //       }
    //     }
    //   });
    // }

    cardBtn = e.target.closest('[card-btn]');
    if (cardBtn) {
      productData = cardBtn.closest('[pid]');
      if (productData) {
        productData = productData.getAttribute('pid').split('-');
        productId = productData[0];
        variantId = productData[1];
      };

      switch (cardBtn.getAttribute('card-btn')) {
        case 'cart-add':
          add = await addToCart(variantId);
          cardBtn.closest('.incart-text').innerHTML = `
              В корзине
              <button class="btn-add-cart incart-btn" card-btn='cart-go' title="В корзину">
                <i class="bi bi-bag-plus"></i>
              </button>
            `
          if (add.status != 'success') {
            cardBtn.closest('.incart-text').innerHTML = `
              <button class="btn-add-cart" card-btn='cart-add' title="В корзину">
                <i class="bi bi-bag-plus"></i>
              </button>
            `
          }
          break;

        case 'fav-add':
          add = await addToFavs(variantId);
          fvbtn = cardBtn.closest('.product-fav-btn');
          fvbtn.classList.toggle('active');
          fvbtn.setAttribute('card-btn', 'fav-remove');
          fvbtn.innerHTML = `<i class="bi bi-heart-fill"></i>`
          if (add.status != 'success') {
            fvbtn.setAttribute('card-btn', 'fav-add');
            fvbtn = cardBtn.closest('.product-fav-btn');
            fvbtn.classList.toggle('active');
            fvbtn.innerHTML = `<i class="bi bi-heart"></i>`
          }
          break;

        case 'fav-remove':
          rem = await removeFromFavs(variantId);
          fvbtn = cardBtn.closest('.product-fav-btn');
          fvbtn.classList.toggle('active');
          fvbtn.setAttribute('card-btn', 'fav-add');
          fvbtn.innerHTML = `<i class="bi bi-heart"></i>`
          if (rem.status != 'success') {
            fvbtn.setAttribute('card-btn', 'fav-remove');
            fvbtn = cardBtn.closest('.product-fav-btn');
            fvbtn.classList.toggle('active');
            fvbtn.innerHTML = `<i class="bi bi-heart-fill"></i>`
          }
          break;

        case 'cart-go':
          window.location = './cart.php';
          break;

        case 'cart-remove':
          await removeFromCart(variantId);
          break;

        case 'trans-fav':
          await removeFromCart(variantId);
          await addToFavs(variantId);
          break;

        case 'trans-cart':
          await removeFromFavs(variantId);
          await addToCart(variantId);
          break;
      }
      if (typeof (renderCartPage) == 'function') await renderCartPage();
      if (typeof (renderFavoritesPage) == 'function') await renderFavoritesPage();
      await updateHandled();
      e.preventDefault();
      return;
    }

    const actionBtn = e.target.closest('[modal-action-button]');
    if (actionBtn) {
      e.preventDefault();
      const modalType = actionBtn.getAttribute('modal-action-button');
      obtainModal(modalType, actionBtn);
      return;
    }

    function changeUrl(i) {
      if (i.target.files[0]) {
        uploadBtn.innerHTML = i.target.files[0].name;
        document.querySelector('#logo-select-url').value = '';
      }
      i.target.removeEventListener('change', changeUrl);
    }

    const uploadBtn = e.target.closest('#logo-select-button');
    if (uploadBtn) {
      const selectInput = document.querySelector('#logo-select-input');
      selectInput.addEventListener('change', changeUrl);
      if (selectInput) selectInput.click();
      return;
    }
    // const selectCatBtn = e.target.closest('#category-select-button');
    // if (selectCatBtn) {
    //   const selectInput = document.querySelector('#logo-select-input');
    //   console.log(selectInput);
    //   selectInput.addEventListener('change', changeUrl);
    //   if (selectInput) selectInput.click();
    //   return;
    // }


    const input = e.target.closest('[modal-action-input]');
    if (input) {
      const inputType = input.getAttribute('modal-action-input');
      switch (inputType) {
        case 'select-category':
          renderSelectCategories(input.value);
          break;

        case 'brand':
          renderBrands(input.value);
          break;

        case 'category':
          renderCategories(input.value);
          break;

      }
    }
    const addBtn = e.target.closest("[data-add-cart]");
    if (addBtn) {
      e.preventDefault();
      const id = addBtn.getAttribute("data-add-cart");
      const qtyInput = document.querySelector("#product-quantity-input");
      const qty = qtyInput ? parseInt(qtyInput.value, 10) || 1 : 1;
      addToCart(id, qty);
    }
  });
};

let user = {};
let handledIds = {message: {favIds: [], cartIds: []}};

async function updateHandled() {
  handled = await getUserCounts();
  if(typeof handled.message != 'string'){
    handledIds = handled;
    updateHeaderBadges(handledIds.message);
  }
}

document.addEventListener("DOMContentLoaded", async () => {
  let user = await getUser();

  updateHandled();
  initEvents();

  const searchInput = document.querySelector("#header-search-input");
  const searchResults = document.querySelector("#search-autocomplete-box");

  if (searchInput && searchResults) {
    searchInput.addEventListener("input", () => {
      const val = searchInput.value.trim().toLowerCase();
      if (val.length >= 2) {
        const matches = SOUNDWAVE_DATA.products.filter(
          (p) =>
          p.title.toLowerCase().includes(val) ||
          p.category.toLowerCase().includes(val) ||
          (p.brand && p.brand.toLowerCase().includes(val))
        );

        if (matches.length > 0) {
          let html = "";
          matches.slice(0, 4).forEach((item) => {
            html += `
              <a href="product.php?id=${item.id}" class="autocomplete-item">
                <div>
                  <strong>${item.title}</strong>
                  <div style="font-size: 11px; color: var(--text-muted);">${item.category} • ${item.brand || ''}</div>
                </div>
                <span style="font-weight: 700; color: var(--yellow-main);">${formatPrice(item.price)}</span>
              </a>
            `;
          });
          html += `<a href="catalog.php?q=${encodeURIComponent(val)}" class="autocomplete-footer">Показать все результаты (${matches.length})</a>`;
          searchResults.innerHTML = html;
          searchResults.classList.add("active");
        } else {
          searchResults.innerHTML = `<div class="autocomplete-item" style="color: var(--text-muted);">Ничего не найдено</div>`;
          searchResults.classList.add("active");
        }
      } else {
        searchResults.classList.remove("active");
      }
    });

    document.addEventListener("click", (e) => {
      if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.classList.remove("active");
      }
    });
  }

  const mobileToggle = document.querySelector("#mobile-menu-toggle");
  const mobileDrawer = document.querySelector("#mobile-nav-drawer");
  const mobileClose = document.querySelector("#mobile-drawer-close");
  const drawerOverlay = document.querySelector("#drawer-overlay");

  if (mobileToggle && mobileDrawer && drawerOverlay) {
    const openDrawer = () => {
      mobileDrawer.classList.add("open");
      drawerOverlay.classList.add("active");
    };
    const closeDrawer = () => {
      mobileDrawer.classList.remove("open");
      drawerOverlay.classList.remove("active");
    };

    mobileToggle.addEventListener("click", openDrawer);
    if (mobileClose) mobileClose.addEventListener("click", closeDrawer);
    drawerOverlay.addEventListener("click", closeDrawer);
  }
});