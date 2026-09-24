<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Каталог музыкальных инструментов | SOUNDWAVE</title>
  <meta name="description"
    content="Полный каталог музыкальных инструментов и студийного оборудования в магазине SOUNDWAVE.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap-grid.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/common.css">
  <link rel="stylesheet" href="css/catalog.css">
</head>

<body>

  <?php require('./components/header.php') ?>

  <nav class="site-breadcrumbs">
    <div class="container">
      <ul class="breadcrumbs-list">
        <li><a href="/">Главная</a></li>
        <li>Каталог</li>
      </ul>
    </div>
  </nav>

  <main>
    <div class="container">
      <div class="catalog-layout">
        <aside class="filters-sidebar" id="catalog-filters-sidebar">
          <div class="filters-title">
            <span>Фильтры</span>
            <button class="filter-reset-btn" id="reset-filters">Сбросить</button>
          </div>

          <div class="filter-group">
            <div class="filter-group-title">Категория</div>
            <ul class="filter-list" id="category-filter-list"></ul>
          </div>

          <div class="filter-group">
            <div class="filter-group-title">Цена, ₽</div>
            <div class="price-inputs-row">
              <div class="price-input-box">
                <span>от</span>
                <input type="number" id="price-min" value="0" min="0">
              </div>
              <div class="price-input-box">
                <span>до</span>
                <input type="number" id="price-max" value="300000" max="500000">
              </div>
            </div>
            <input type="range" class="price-slider" id="price-slider" min="0" max="300000" step="100" value="300000">
          </div>

          <div class="filter-group">
            <div class="filter-group-title">Бренд</div>
            <ul class="filter-list" id="brand-filter-list"></ul>
          </div>

          <div class="filter-group">
            <div class="filter-group-title">Наличие</div>
            <ul class="filter-list">
              <li>
                <label class="custom-checkbox">
                  <input type="checkbox" id="filter-instock" checked>
                  <span>Только в наличии</span>
                </label>
              </li>
            </ul>
          </div>

          <div class="filter-group">
            <div class="filter-group-title">Рейтинг</div>
            <ul class="filter-list">
              <li>
                <label class="custom-checkbox">
                  <input type="checkbox" class="rating-checkbox" data-rating="4.8">
                  <span>Рейтинг от 4.8 ★</span>
                </label>
              </li>
            </ul>
          </div>

          <button class="btn-brand mb-2" style="width: 100%;" id="btn-apply-filters">Применить фильтры</button>
          <button class="btn-wenge" style="width: 100%;" id="btn-reset-filters">Сбросить фильтры</button>
        </aside>

        <section class="catalog-content">
          <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
              <h1 class="section-title mb-1" style="font-size: 32px;">Музыкальные инструменты</h1>
              <p class="section-desc mb-0">Качественные инструменты от ведущих мировых брендов</p>
            </div>
            <button class="mobile-filter-btn" id="mobile-filter-toggle">
              <i class="bi bi-funnel"></i>
              Фильтры
            </button>
          </div>

          <div class="catalog-top-bar">
            <div class="catalog-count">
              Показано <strong>8</strong> из <strong>450</strong> товаров
            </div>

            <div class="sort-wrapper">
              <label for="catalog-sort">Сортировка:</label>
              <select id="catalog-sort" class="sort-select">
                <option value="pop">По популярности</option>
                <option value="price_asc">По цене: сначала дешёвые</option>
                <option value="price_desc">По цене: сначала дорогие</option>
                <option value="new">По новизне</option>
                <option value="rating">По рейтингу</option>
              </select>
            </div>
          </div>

          <div class="row g-4" id="catalog-products-grid">

          </div>

          <nav class="pagination-nav" id="catalog-pagination">
            <a href="#" class="page-btn active">1</a>
            <a href="#" class="page-btn">2</a>
            <a href="#" class="page-btn"><i class="bi bi-chevron-right"></i></a>
          </nav>
        </section>
      </div>
    </div>
  </main>

  <?php require('./components/footer.php') ?>

  <script src="js/main.js"></script>
  <script>
    let currentSort = 'pop';

    function renderStars(rating) {
      let stars = '';
      for (let i = 1; i <= 5; i++) {
        if (rating >= i) {
          stars += '<i class="bi bi-star-fill"></i>';
        } else if (rating >= i - 0.5) {
          stars += '<i class="bi bi-star-half"></i>';
        } else {
          stars += '<i class="bi bi-star"></i>';
        }
      }
      return stars;
    }

    function getSelectedCategories() {
      return Array.from(document.querySelectorAll('.category-checkbox:checked')).map(cb => cb.getAttribute('data-cat'));
    }

    function getSelectedBrands() {
      return Array.from(document.querySelectorAll('.brand-checkbox:checked')).map(cb => cb.getAttribute('data-brand'));
    }
    function setSlider(min, max, cur) {
      const pMax = document.querySelector('#price-max');
      const pMin = document.querySelector('#price-min');
      const slider = document.querySelector('#price-slider');
      console.log(cur);
      if (slider) {
        slider.min = min;
        slider.max = max;
        slider.value = cur;
      };

      if (pMax) pMax.value = max;
      if (pMin) pMin.value = min;
    }

    function renderFilters(filters) {
      console.log(filters);

      const currentBrands = getSelectedBrands();
      const currentCategories = getSelectedCategories();

      const brandList = document.querySelector('#brand-filter-list');
      const categoryList = document.querySelector('#category-filter-list');

      if (Object.keys(filters.availableBrands).length === 0) {
        brandList.innerHTML = 'Бренды не найдены';
      } else {
        const brandListCheckboxes = brandList.querySelectorAll('.brand-checkbox');
        if (brandListCheckboxes.length === 0) {
          brandList.innerHTML = '';
          for (const [id, value] of Object.entries(filters.availableBrands)) {
            brandList.innerHTML += `
                    <li>
                        <label class="custom-checkbox">
                            <input type="checkbox" class="brand-checkbox" data-brand="${id}">
                            <span>${value}</span>
                        </label>
                    </li>`;
          }
          setSlider(filters.availableMin, filters.availableMax, filters.availableMax);

        } else {
          brandListCheckboxes.forEach((c) => {
            const elid = c.getAttribute('data-brand');
            const isAvailable = Object.keys(filters.availableBrands).includes(elid);
            c.disabled = !isAvailable;
            c.checked = currentBrands.includes(elid);
          });
        }
      }

      if (Object.keys(filters.availableCategories).length === 0) {
        categoryList.innerHTML = 'Категории не найдены';
      } else {
        const categoryListCheckboxes = categoryList.querySelectorAll('.category-checkbox');
        if (categoryListCheckboxes.length === 0) {
          categoryList.innerHTML = '';
          for (const [id, value] of Object.entries(filters.availableCategories)) {
            categoryList.innerHTML += `
                    <li>
                        <label class="custom-checkbox">
                            <input type="checkbox" class="category-checkbox" data-cat="${id}">
                            <span>${value}</span>
                        </label>
                    </li>`;
          }
        } else {
          categoryListCheckboxes.forEach((c) => {
            const elid = c.getAttribute('data-cat');
            const isAvailable = Object.keys(filters.availableCategories).includes(elid);
            c.disabled = !isAvailable;
            c.checked = currentCategories.includes(elid);
          });
        }
      };
      const pf = JSON.parse(localStorage.getItem('lastFilter'));
      if (pf == null || JSON.stringify(currentCategories) != JSON.stringify(pf.selectedCats) ||
       JSON.stringify(currentBrands) != JSON.stringify(pf.selectedBrands)) {
        setSlider(filters.availableMin, filters.availableMax, filters.availableMax);
        localStorage.setItem('lastFilter', JSON.stringify({selectedBrands: currentBrands, selectedCats: currentCategories}));
      }
      
      document.querySelectorAll('.category-checkbox, .brand-checkbox, #filter-instock, .rating-checkbox').forEach(cb => {
        cb.removeEventListener('change', renderCatalog);
        cb.addEventListener('change', renderCatalog);
      });
    }


    function getFilterParams() {
      const params = new URLSearchParams(window.location.search);
      const priceMinInput = document.querySelector('#price-min');
      const priceMaxInput = document.querySelector('#price-max');

      const filterParams = {
        query: params.get('q'),
        category: params.get('cat'),
        brand: params.get('brand'),
        sort: params.get('sort'),
        selectedCats: getSelectedCategories(),
        selectedBrands: getSelectedBrands(),
        minP: priceMinInput ? parseInt(priceMinInput.value, 10) || 0 : 0,
        maxP: priceMaxInput ? parseInt(priceMaxInput.value, 10) || 1000000 : 1000000,
        onlyStock: document.querySelector('#filter-instock').checked,
        aboveRating: document.querySelector('.rating-checkbox').checked,
      }
      return filterParams;
    }

    async function renderCatalog() {
      const container = document.querySelector('#catalog-products-grid');
      const countEl = document.querySelector('.catalog-count');
      if (!container) return;

      // const products = getFilteredAndSortedProducts();
      const filters = await getFilters(getFilterParams());
      renderFilters(filters);
      const products = await getProducts(getFilterParams());
      const favs = getStoredFavs();

      if (countEl) {
        countEl.innerHTML = `Показано <strong>${products.length}</strong> из <strong>${SOUNDWAVE_DATA.products.length}</strong> товаров`;
      }

      if (products.length === 0) {
        container.innerHTML = `
          <div class="col-12 text-center py-5">
            <i class="bi bi-funnel" style="font-size: 48px; color: var(--yellow-main); margin-bottom: 16px; display: inline-block;"></i>
            <h3 style="font-weight: 800; margin-bottom: 8px;">Товары не найдены</h3>
            <p style="color: var(--text-secondary); margin-bottom: 24px;">Попробуйте изменить параметры фильтров или сбросить их</p>
            <button class="btn-brand" onclick="resetFilters()">Сбросить фильтры</button>
          </div>
        `;
        return;
      }

      let html = '';
      products.forEach(p => {
        const isFav = favs.includes(p.id);
        html += `
          <div class="col-xl-4 col-md-6">
            <div class="product-card">
              <div class="product-image-box">
                <div class="product-badges">
                  ${p.badge ? `<span class="badge-${p.badgeType || 'hit'}">${p.badge}</span>` : ''}
                </div>
                <button class="product-fav-btn ${isFav ? 'active' : ''}" data-id="${p.id}" aria-label="В избранное">
                  <i class="bi bi-heart${isFav ? '-fill' : ''}"></i>
                </button>
                <a href="product.php?id=${p.id}">
                  <img src="${p.image}" alt="${p.productTitle}" loading="lazy">
                </a>
              </div>
              <div class="product-content">
                <div class="product-category-tag">${p.category}</div>
                <a href="product.php?id=${p.id}" class="product-title">${p.productTitle}</a>
                <p class="product-desc">${p.shortDescription}</p>
                <div class="product-rating">
                  <div class="stars">
                    ${renderStars(p.rating)}
                  </div>
                  <span class="rating-count">${p.rating == null ? 0 : p.rating} (${p.reviewsCount})</span>
                </div>
                <div class="product-price-row">
                  <div class="price-box">
                    ${p.oldPrice ? `<span class="old-price">${formatPrice(p.oldPrice)}</span>` : ''}
                    <span class="current-price">${formatPrice(p.price)}</span>
                  </div>
                  <button class="btn-add-cart" data-add-cart="${p.id}" title="В корзину">
                    <i class="bi bi-bag-plus"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        `;
      });

      container.innerHTML = html;
      // updateFavBadgess();
    }

    function resetFilters() {
      document.querySelectorAll('.category-checkbox').forEach(cb => cb.checked = false);
      document.querySelectorAll('.brand-checkbox').forEach(cb => cb.checked = false);
      const stock = document.querySelector('#filter-instock');
      if (stock) stock.checked = true;
      const rating = document.querySelector('.rating-checkbox');
      if (rating) rating.checked = false;

      const pMin = document.querySelector('#price-min');
      const pMax = document.querySelector('#price-max');
      const slider = document.querySelector('#price-slider');
      if (pMin) pMin.value = 0;
      if (pMax) pMax.value = 300000;
      if (slider) slider.value = 300000;

      const sortSelect = document.querySelector('#catalog-sort');
      if (sortSelect) {
        sortSelect.value = 'pop';
        currentSort = 'pop';
      }

      if (window.location.search) {
        window.history.replaceState({}, '', window.location.pathname);
      }

      renderCatalog();
      showToast('Фильтры сброшены');
    }

    document.addEventListener('DOMContentLoaded', () => {
      const sortSelect = document.querySelector('#catalog-sort');
      if (sortSelect) {
        sortSelect.addEventListener('change', () => {
          currentSort = sortSelect.value;
          renderCatalog();
        });
      }

      const slider = document.querySelector('#price-slider');
      const pMax = document.querySelector('#price-max');
      const pMin = document.querySelector('#price-min');

      if (slider && pMax) {
        slider.addEventListener('mouseup', () => {
          pMax.value = slider.value;
          renderCatalog();
        });
      }

      if (pMax && slider) {
        pMax.addEventListener('change', () => {
          slider.value = pMax.value;
          renderCatalog();
        });
      }

      if (pMin) {
        pMin.addEventListener('change', () => {
          renderCatalog();
        });
      }

      const applyBtn = document.querySelector('#btn-apply-filters');
      if (applyBtn) {
        applyBtn.addEventListener('click', () => {
          renderCatalog();
          showToast('Фильтры применены');
          const filterSidebar = document.querySelector('#catalog-filters-sidebar');
          const overlay = document.querySelector('#drawer-overlay');
          if (filterSidebar && filterSidebar.classList.contains('active')) {
            filterSidebar.classList.remove('active');
            if (overlay) overlay.classList.remove('active');
          }
        });
      }

      const resetBtn = document.querySelector('#btn-reset-filters');
      if (resetBtn) {
        resetBtn.addEventListener('click', resetFilters);
      }

      const resetTopBtn = document.querySelector('#reset-filters');
      if (resetTopBtn) {
        resetTopBtn.addEventListener('click', resetFilters);
      }

      const filterBtn = document.querySelector('#mobile-filter-toggle');
      const filterSidebar = document.querySelector('#catalog-filters-sidebar');
      const overlay = document.querySelector('#drawer-overlay');
      if (filterBtn && filterSidebar && overlay) {
        filterBtn.addEventListener('click', () => {
          filterSidebar.classList.toggle('active');
          overlay.classList.toggle('active');
        });
        overlay.addEventListener('click', () => {
          filterSidebar.classList.remove('active');
        });
      }

      renderCatalog();
    });
  </script>
</body>

</html>