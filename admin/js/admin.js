import {
  api
} from "./api.js";
import { renderCatalog } from "./pages/products.js";

// import { renderCatalog } from "./pages/products.js";

export let pageData = {
  brands: [],
}

let products = [];
let brand = {};
let categories = [];
let product = {};
let presaveproduct = {};
let lastSearchCat = '0';

document.addEventListener('DOMContentLoaded', () => {
  initSidebar();
  initEvents();
  initPickupTerminal();
  renderCatalog();
});

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
async function openModal(modalId, targetBtn) {
  let modalBox = document.getElementById(modalId);
  if (!modalBox) {
    let mb = document.createElement('div');
    mb.id = modalId;
    mb.className = 'modal-backdrop';
    document.body.append(mb);
    modalBox = mb;
  }
  switch (modalId) {
    case 'product-modal':{
      
      const productId = targetBtn.getAttribute('data-modal-product-id');
      product = await api.get('/product', {
        id: productId
      });
      modalBox.innerHTML = `<div class="admin-modal modal-xl">
      <div class="modal-header">
        <div class="modal-title">
          <i class="bi bi-pencil-fill" style="color: var(--brand-yellow);"></i>
          <span>Редактирование товара</span>
        </div>
        <button class="modal-close-btn" type="button" modal-action-button='close'>
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <div class="modal-body">
        <!-- Tabs Navigation -->
        <div class="nav-tabs">
          <button class="tab-btn active" data-tab="tab-general" data-tab-level='1'>
            <i class="bi bi-info-circle"></i> Основное
          </button>
          <button class="tab-btn" data-tab="tab-variants" data-tab-level='1'>
            <i class="bi bi-tags"></i> Модификации и цены (${Object.keys(product.variants).length})
          </button>
          <button class="tab-btn" data-tab="tab-attributes" data-tab-level='1' modal-action-button='reload-attributes'>
            <i class="bi bi-sliders"></i> Характеристики
          </button>
          <button class="tab-btn" data-tab="tab-gallery" data-tab-level='1' modal-action-button='reload-images'>
            <i class="bi bi-images"></i> Фото и медиа (${Object.keys(product.images).length})
          </button>
        </div>

        <!-- TAB 1: GENERAL -->
        <div class="tab-pane active" id="tab-general" data-tab-level='1'>
          <form id="product-general-form">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
              <div class="form-group">
                <label class="form-label">Полное название товара <span class="required">*</span></label>
                <input type="text" class="form-control" name="general-id" value="${product.id}" style='display:none;' required>
                <input type="text" class="form-control" name="general-title" value="${product.productTitle}" required>
                <div class="form-hint">Отображается в заголовке страницы каталога и поиске</div>
              </div>

              <div class="form-group">
                <label class="form-label">Алиас (Slug) <span class="required">*</span></label>
                <input type="text" class="form-control" name="general-slug" value="${product.slug}"
                  style="font-family: var(--font-mono);" required>
              </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
              <div class="form-group">
                <label for="data-modal-brand-id" class="form-label">Бренд производителя <span class="required">*</span></label>
                <input class="form-control" id='data-modal-brand-id' data-modal-target="select-brand" value="${product.brand}" data-modal-brand-id="${product.brandId}" name="general-brand-${product.brandId}" required>
              </div>

              <div class="form-group">
                <label for="data-modal-category-id" class="form-label">Категория каталога <span class="required">*</span></label>
                <input class="form-control" id='data-modal-category-id' data-modal-target="select-category" value="${product.category}" data-modal-category-parent='0' name="general-category-${product.categoryId}" required>
              </div>

              <div class="form-group">
                <label class="form-label">Модельный код производителя</label>
                <input type="text" class="form-control" name="general-modelCode" value="${product.modelCode}"
                  style="font-family: var(--font-mono);">
              </div>
            </div>

            <div class="form-group">
              <label class="form-label">Краткое описание (для сниппетов и карточек каталога)</label>
              <textarea class="form-control" name="general-shortDescription"
                rows="2">${product.shortDescription}</textarea>
            </div>

            <div class="form-group">
              <label class="form-label">Полное описание с разметкой</label>
              <textarea class="form-control" name="general-description"
                rows="4">${product.description}</textarea>
            </div>

            <div style="display: flex; gap: 32px; padding: 12px 0;">
              <label class="switch-label">
                <input type="checkbox" class="switch-input" name="general-isActive" ${product.isActive == '1' ? 'checked' : ''}>
                <span class="switch-slider"></span>
                <span>Отображать на сайте (Активен)</span>
              </label>

              <label class="switch-label">
                <input type="checkbox" class="switch-input" name="general-isFeatured" ${product.isFeatured == '1' ? 'checked' : ''}>
                <span class="switch-slider"></span>
                <span>Хит продаж / Рекомендованный товар</span>
              </label>
            </div>
          </form>
        </div>

        <!-- TAB 2: VARIANTS & PRICING -->
        <div class="tab-pane" id="tab-variants" data-tab-level='1'>
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <p style="color: var(--text-secondary); font-size: 0.88rem;">Модификации определяют цвета, сопротивление
              (Ом) и индивидуальные складские остатки.</p>
            <button class="btn btn-brand btn-sm" type="button" modal-action-button='add-variant' modal-action-data='${product.id}'>
              <i class="bi bi-plus-lg"></i> Добавить модификацию
            </button>
          </div>

          
          <div class="table-responsive">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Название варианта</th>
                  <th>Артикул (SKU)</th>
                  <th>Штрих-код (EAN-13)</th>
                  <th>Цена (₽)</th>
                  <th>Старая цена (₽)</th>
                  <th>Остаток (шт)</th>
                  <th>Активен</th>
                  <th>Действие</th>
                </tr>
              </thead>
              <tbody id='product-variants'></tbody>
            </table>
          </div>
          
        </div>

        <!-- TAB 3: ATTRIBUTES -->
        <div class="tab-pane" id="tab-attributes" data-tab-level='1'>
          <p style="color: var(--text-secondary); font-size: 0.88rem; margin-bottom: 20px;">
            Технические характеристики участвуют в фильтрах каталога интернет-магазина.
          </p>
          <div id='attributes-box'></div>
        </div>

        <!-- TAB 4: GALLERY & MEDIA -->
        <div class="tab-pane" id="tab-gallery" data-tab-level='1'>
            <div id='gallery-box'></div>
        </div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" modal-action-button='close'>Отмена</button>
        <button class="btn btn-brand" type="button" modal-action-button='save-product'>
          <i class="bi bi-check2"></i> Сохранить изменения
        </button>
      </div>
    </div>`
      renderProductVariants(product);
      renderVariantAttributes(product);
      renderVariantGallery(product);
      break;
    }

    case 'brand-modal':
      const brandId = targetBtn.getAttribute('data-modal-brand-id');
      if (brandId == '0') {
        brand = {
          "id": 0,
          "title": "",
          "slug": "",
          "country": "",
          "logoUrl": "Добавьте Изображение",
          "description": "",
          "isActive": 1
        }
      } else {
        brand = pageData.brands.find(b => b.id == brandId);
      }
      console.log(brand);
      modalBox.innerHTML = `
          <div class="admin-modal modal-lg">
      <div class="modal-header">
        <div class="modal-title">
          <i class="bi bi-patch-check-fill" style="color: var(--brand-yellow);"></i>
          <span>Редактирование бренда</span>
        </div>
        <button class="modal-close-btn" type="button" modal-action-button='close'>
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <div class="modal-body">
        <form id="brand-form">
          <input type="text" class="form-control" name="id" value="${brand.id}" required style='display: none'>
          <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px;">
            <div class="form-group">
              <label class="form-label">Название бренда <span class="required">*</span></label>
              <input type="text" class="form-control" name="title" value="${brand.title}" required>
            </div>

            <div class="form-group">
              <label class="form-label">Алиас (Slug) <span class="required">*</span></label>
              <input type="text" class="form-control" name="slug" value="${brand.slug}" style="font-family: var(--font-mono);" required>
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
              <label class="form-label">Страна происхождения</label>
              <input type="text" class="form-control" name="country" value="${brand.country}" placeholder="Например: Япония, США">
            </div>

            <div class="form-group">
              <label class="form-label">Логотип (только svg)</label>
              <button type='button' class="form-control" id='logo-select-button'>${brand.logoUrl}</button>
              <input type="file" class="form-control" accept=".svg, image/svg+xml" name="logo_input" id='logo-select-input' style='display: none'>
              <input type="text" name="logoUrl" id='logo-select-url' value='${brand.logoUrl}' style='display: none'>
            </div>
          </div>

          <div class="form-group">
            <label class="form-label">Описание бренда для витрины</label>
            <textarea class="form-control" name="description" rows="3">${brand.description}</textarea>
          </div>

          <div class="form-group">
            <label class="switch-label">
              <input type="checkbox" class="switch-input" name="is_active" ${brand.isActive == 1 ? 'checked' : ''}>
              <span class="switch-slider"></span>
              <span>Бренд активен и отображается в фильтрах каталога</span>
            </label>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" modal-action-button='close'>Отмена</button>
        <button class="btn btn-brand" type="button" modal-action-button='${brand.id == 0 ? 'add' : 'save'}-brand'>
          <i class="bi bi-check2"></i> Сохранить бренд </button>
      </div>
    </div>
      `
      break;

    case 'category-modal':
      const categoryId = targetBtn.getAttribute('data-modal-category-id');
      if (categoryId == '0') {
        category = {
          "id": 0,
          "title": "",
          "slug": "",
          "parentId": "",
          "description": "",
          "isActive": 1
        }
      } else {
        category = categories.find(b => b.id == categoryId);
      }
      modalBox.innerHTML = `
        <div class="admin-modal modal-lg">
      <div class="modal-header">
        <div class="modal-title">
          <i class="bi bi-diagram-3-fill" style="color: var(--brand-yellow);"></i>
          <span>Управление категорией каталога</span>
        </div>
        <button class="modal-close-btn" type="button" modal-action-button='close'>
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <div class="modal-body">
        <form id="category-form">
          <input type="text" class="form-control" name="id" value="${category.id}" required style='display: none'>
          <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px;">
            <div class="form-group">
              <label class="form-label">Название категории <span class="required">*</span></label>
              <input type="text" class="form-control" name="title" value="${category.title}"
                placeholder="Например: Студийные мониторы" required>
            </div>

            <div class="form-group">
              <label class="form-label">Алиас (Slug) <span class="required">*</span></label>
              <input type="text" class="form-control" name="slug" value="${category.slug}"
                style="font-family: var(--font-mono);" required>
            </div>
          </div>

          <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div class="form-group">
              <label class="form-label">Родительская категория</label>
                <button type='button' class="form-control" data-modal-target="select-category" data-modal-category-parent='1' id='parent-category-title'>${category.parentTitle || 'Корневая'}</button>
              <input type="text" name="parentCategoryId" id='parent-category-id' value='${category.parentId || '0'}' style='display: none'>
          </div>

          <div class="form-group">
            <label class="form-label">Описание раздела (для SEO и сниппетов)</label>
            <textarea class="form-control" name="description"
              rows="3">${category.description}</textarea>
          </div>

          <div class="form-group">
            <label class="switch-label">
              <input type="checkbox" class="switch-input" name="is_active" ${category.isActive == 1 ? 'checked' : ''}>
              <span class="switch-slider"></span>
              <span>Категория активна и видна в каталоге</span>
            </label>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" modal-action-button='close'>Отмена</button>
        <button class="btn btn-brand" type="button" modal-action-button='${category.id == 0 ? 'add' : 'save'}-category'>
        Сохранить категорию
        </button>
      </div>
    </div>
      `
      break;

    case 'select-brand':
      modalBox.innerHTML = `  
      <div class="admin-modal modal-lg">
        <div class="modal-header">
        <div class="modal-title">
          <i class="bi bi-patch-check-fill" style="color: var(--brand-yellow);"></i>
          <span>Выбор бренда</span>
        </div>
        <button class="modal-close-btn" type="button" modal-action-button='close'>
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <div class="modal-body">
        <form id="brand-form">
          <div class="form-group">
            <label class="form-label">Введите название бренда</label>
            <input type="text" class="form-control" name="title" value="." required>
          </div>
        </form>
        <button class="brand-card" data-brand-id="2" onclick="closeModal('select-brand', 2)">
          <div>
            <div class="brand-card-select">
              <div class="brand-card-select">
                <div class="brand-logo-preview">
                  <i class="bi bi-mic-fill"></i>
                </div>
                <div class="brand-card-title">Shure</div>
                <div class="brand-card-country"><i class="bi bi-geo-alt-fill"></i> США</div>
              </div>
            </div>
          </div>
        </button>

        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" modal-action-button='close'>Отмена</button>
        </div>
      </div>
    </div>`
      break;

    case 'select-category':
      modalBox.innerHTML = `  
      <div class="admin-modal modal-lg">
        <div class="modal-header">
        <div class="modal-title">
          <i class="bi bi-patch-check-fill" style="color: var(--brand-yellow);"></i>
          <span>Выберите категорию</span>
        </div>
        <button class="modal-close-btn" type="button" modal-action-button='close'>
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <div class="modal-body">
        <form id="select-category-form">
          <div class="form-group">
            <label class="form-label">Введите название категории</label>
            <input type="text" class="form-control" name="title" modal-action-input='select-category' value="" palaceholder='Название' required>
          </div>
        </form>
        <table class="admin-table">
              <thead>
                <tr>
                  <th style="width: 50px;">ID</th>
                  <th>Название категории</th>
                  <th>Алиас (Slug)</th>
                  <th>Статус</th>
                </tr>
              </thead>
              <tbody id="select-category-list"></tbody>
            </table>
          <div class="modal-footer">
          <button class="btn btn-secondary" type="button" modal-action-button='select-category' modal-action-data='0'>Сделать корневой</button>
          <button class="btn btn-secondary" type="button" modal-action-button='close'>Отмена</button>
        </div>
      </div>
    </div>`
      lastSearchCat = targetBtn.getAttribute('data-modal-category-parent') == '1';
      renderSelectCategories();
      break;

    case 'select-attribute':
      modalBox.innerHTML = `  
      <div class="admin-modal modal-lg">
        <div class="modal-header">
        <div class="modal-title">
          <i class="bi bi-patch-check-fill" style="color: var(--brand-yellow);"></i>
          <span>Выберите атрибут</span>
        </div>
        <button class="modal-close-btn" type="button" modal-action-button='close'>
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <div class="modal-body">
        <form id="select-atribute-form">
          <div class="form-group">
            <label class="form-label">Введите название категории</label>
            <input type="text" class="form-control" name="title" modal-action-input='select-atribute' value="" palaceholder='Название' required>
          </div>
        </form>
        <table class="admin-table">
              <thead>
                <tr>
                  <th style="width: 50px;">ID</th>
                  <th>Название категории</th>
                  <th>Алиас (Slug)</th>
                  <th>Статус</th>
                </tr>
              </thead>
              <tbody id="select-category-list"></tbody>
            </table>
          <div class="modal-footer">
          <button class="btn btn-secondary" type="button" modal-action-button='close'>Отмена</button>
        </div>
      </div>
    </div>`;
      renderSelectAttributes();
      break;

    case 'remove-attribute':
      removingIds = targetBtn.getAttribute('data-modal-attr-id').split('-');
      productVariant = product.variants[removingIds[0]];
      attr = productVariant.attributes[removingIds[1]];
      modalBox.innerHTML = `  
      <div class="admin-modal modal-sm">
        <div class="modal-header">
        <div class="modal-title">
          <i class="bi bi-patch-check-fill" style="color: var(--brand-yellow);"></i>
          <span>Подтверждаете удаление <br> ${attr.title} ?</span>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" modal-action-button='close'>Отмена</button>
        <button class="btn btn-brand" type="button" modal-action-button='remove-attr' modal-action-data='${removingIds[0]}-${removingIds[1]}'>
          <i class="bi bi-check2"></i> Удалить атрибут</button>
      </div>`;
      break;

    case 'remove-brand':
      removingBrandId = targetBtn.getAttribute('data-modal-brand-id');
      brand = brands.find(b => b.id == removingBrandId);
      modalBox.innerHTML = `  
      <div class="admin-modal modal-sm">
        <div class="modal-header">
        <div class="modal-title">
          <i class="bi bi-patch-check-fill" style="color: var(--brand-yellow);"></i>
          <span>Подтверждаете удаление ${brand.title} ?</span>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" modal-action-button='close'>Отмена</button>
        <button class="btn btn-brand" type="button" modal-action-button='remove-brand' modal-action-data='${brand.id}'>
          <i class="bi bi-check2"></i> Удалить бренд </button>
      </div>`;
      break;

    case 'remove-category':{
      let removingCategoryId = targetBtn.getAttribute('data-modal-category-id');
      let category = categories.find(c => c.id == removingCategoryId);
      modalBox.innerHTML = `  
      <div class="admin-modal modal-sm">
        <div class="modal-header">
        <div class="modal-title">
          <i class="bi bi-patch-check-fill" style="color: var(--brand-yellow);"></i>
          <span>Подтверждаете удаление ${category.title} ?</span>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" modal-action-button='close'>Отмена</button>
        <button class="btn btn-brand" type="button" modal-action-button='remove-category' modal-action-data='${category.id}'>
          <i class="bi bi-check2"></i> Удалить бренд </button>
      </div>`;
      break;
    }
    case 'remove-variant':{
      let removingVariantId = targetBtn.getAttribute('data-modal-variant-id');
      let variant = product.variants[removingVariantId];
      modalBox.innerHTML = `  
      <div class="admin-modal modal-sm">
        <div class="modal-header">
        <div class="modal-title">
          <i class="bi bi-patch-check-fill" style="color: var(--brand-yellow);"></i>
          <span>Подтверждаете удаление ${variant.variant} ?</span>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" modal-action-button='close'>Отмена</button>
        <button class="btn btn-brand" type="button" modal-action-button='remove-variant' modal-action-data='${removingVariantId}'>
          <i class="bi bi-check2"></i> Удалить вариант </button>
      </div>`;
      break;
    }
    case 'remove-image':{
      let removingImageId = targetBtn.getAttribute('data-modal-image-id');
      let image = product.images[removingImageId];
      modalBox.innerHTML = `  
      <div class="admin-modal modal-sm">
        <div class="modal-header">
        <div class="modal-title">
          <i class="bi bi-patch-check-fill" style="color: var(--brand-yellow);"></i>
          <span>Удалить изображение?</span>
        </div>
      </div>
      <div class='modal-body'>
        <img style='width: 100px; height: 100px;' src=${image.imageUrl}/>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" modal-action-button='close'>Отмена</button>
        <button class="btn btn-brand" type="button" modal-action-button='remove-image' modal-action-data='${removingImageId}'>
          <i class="bi bi-check2"></i> Удалить изображение </button>
      </div>`;
      break;
    }
      
  }
  modalBox.style.display = 'flex';
  setTimeout(() => modalBox.classList.add('show'), 10);
  document.body.style.overflow = 'hidden';
}

function closeModal(modalId, save = 'none') {
  if (save != 'none') {
    switch (modalId) {
      case 'select-brand':
        document.querySelector('input#data-modal-brand-id').setAttribute('data-modal-brand-id', save);
        break;
    }
  }

  const backdrop = document.getElementById(modalId);
  if (!backdrop) return;
  backdrop.classList.remove('show');
  setTimeout(() => {
    backdrop.style.display = 'none';
    document.body.style.overflow = '';
  }, 250);
}

function initEvents() {
  document.addEventListener('click', (e) => {
    const tabButton = e.target.closest('.tab-btn');
    if (tabButton) {
      const navtab = e.target.closest('.nav-tabs');
      const tabButtons = navtab.querySelectorAll('.tab-btn');
      const tabLevel = tabButton.getAttribute('data-tab-level');
      const targetId = tabButton.getAttribute('data-tab');
      const container = navtab.closest('.modal-body') || navtab.closest('.admin-card') || document;

      tabButtons.forEach(btn => {
        if (btn.getAttribute('data-tab-level') == tabLevel) btn.classList.remove('active');
      });
      tabButton.classList.add('active');

      const panes = container.querySelectorAll('.tab-pane');
      panes.forEach(pane => {
        if (pane.getAttribute('data-tab-level') == tabLevel) {
          if (pane.id === targetId) {
            pane.classList.add('active');
          } else {
            pane.classList.remove('active');
          }
        }
      });
    }

    const trigger = e.target.closest('[data-modal-target]');
    if (trigger) {
      e.preventDefault();
      const targetId = trigger.getAttribute('data-modal-target');
      openModal(targetId, trigger);
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
    //   selectInput.addEventListener('change', changeUrl);
    //   if (selectInput) selectInput.click();
    //   return;
    // }

  });

  document.addEventListener('input', (e) => {
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
  });
};

async function obtainModal(action, btn) {
  switch (action) {
    case 'save-brand':
      frm = document.querySelector('#brand-form');
      f = Object.fromEntries(new FormData(frm));
      response = await Brands(f, 'update');
      showToast('Успешно', response.message, 'success');
      renderBrands();
      closeModal('brand-modal');
      break;

    case 'add-brand':
      frm = document.querySelector('#brand-form');
      f = Object.fromEntries(new FormData(frm));
      f['type'] = 'add';
      response = await Brands(f, 'add');
      showToast('Успешно', response.message, 'success');
      renderBrands();
      closeModal('brand-modal');
      break;

    case 'remove-brand':
      bid = btn.getAttribute('modal-action-data');
      response = await Brands({
        id: bid
      }, 'remove');
      showToast('Успешно', response.message, 'success');
      renderBrands();
      closeModal('remove-brand');
      break;

    case 'select-category':
      cid = btn.getAttribute('modal-action-data');
      if (lastSearchCat) {
        if (cid == 0) {
          title = 'Корневая';
        } else {
          category = categories.find(c => c.id == cid);
          title = category.title;
        }
        document.querySelector('#parent-category-title').innerText = title;
        document.querySelector('#parent-category-id').value = cid;
      } else {
        category = categories.find(c => c.id == cid);
        title = category.title;
        document.querySelector('#parent-category-title').innerText = title;
        document.querySelector('#parent-category-id').value = cid;
      }

      closeModal('select-category');
      break;

    case 'select-product-category':
      cid = btn.getAttribute('modal-action-data');
      if (cid == 0) {
        title = 'Корневая';
      } else {
        category = categories.find(c => c.id == cid);
        title = category.title;
      }
      document.querySelector('#parent-category-title').innerText = title;
      document.querySelector('#parent-category-id').value = cid;
      closeModal('select-category');
      break;

    case 'save-category':
      frm = document.querySelector('#category-form');
      f = Object.fromEntries(new FormData(frm));
      response = await Categories(f, 'update');
      showToast('Успешно', response.message, 'success');
      renderCategories();
      closeModal('category-modal');
      break;

    case 'add-category':
      frm = document.querySelector('#category-form');
      f = Object.fromEntries(new FormData(frm));
      f['type'] = 'add';
      response = await Categories(f, 'add');
      showToast('Успешно', response.message, 'success');
      renderCategories();
      closeModal('category-modal');
      break;

    case 'remove-category':{
      let cid = btn.getAttribute('modal-action-data');
      let response = await Categories({
        id: cid
      }, 'remove');
      showToast('Успешно', response.message, 'success');
      renderCategories();
      closeModal('remove-category');
      break;
    }   
    case 'reload-attributes':{
      presave();
      renderVariantAttributes(product);
      break;
    }
    case 'add-variant':{
      let pid = btn.getAttribute('modal-action-data');
      let new_id = 'new' + (Object.keys(product.variants).length + 1);
      product.variants[new_id] = {
        'action': 'add',
        "variantId": new_id,
        "variant": "",
        "article": "",
        "barcode": "",
        "price": 0,
        "oldPrice": 0,
        "amount": 0,
      };
      renderProductVariants(product);
      break;
    }
    case 'copy-variant':{
      let vid = btn.getAttribute('modal-action-data');
      let new_id = 'copy' + (Object.keys(product.variants).length + 1);
      let variant = {
        ...product.variants[vid],
        variantId: new_id,
        'action': 'add',
      };
      product.variants[new_id] = variant;
      renderProductVariants(product);
      break;
    }
    case 'remove-variant':{
      let vid = btn.getAttribute('modal-action-data');
      if (vid.includes('copy') || vid.includes('new')) {
        delete product.variants[vid];
      } else {
        product.variants[vid].action = 'remove';
      }
      renderProductVariants(product);
      closeModal('remove-variant');
      break;
    }
    case 'remove-attr':{
      let ids = btn.getAttribute('modal-action-data').split('-');
      product.variants[ids[0]].attributes[ids[1]].action = 'remove';
      renderVariantAttributes(product, setactive = ids[0]);
      closeModal('remove-attribute');
      break;
    }
    case 'select-main-image':{
      let id = btn.getAttribute('modal-action-data');
      for (let i of Object.keys(product.images)) {
        product.images[i].isMain = '0';
      }
      product.images[id].isMain = '1';
      renderVariantGallery(product, product.images[id].variantId);
      break;
    }
    case 'remove-image':{
      let id = btn.getAttribute('modal-action-data');
      product.images[id].action = 'remove';
      let vid = product.images[id].variantId;
      let l = [];
      for (let i of Object.keys(product.images)) {
        if (product.images[i].action != 'remove') l.push(i);
      }
      if (l.length == 0) {
        showToast('Предупреждение', 'Добавьте хотя бы одно изображение', 'info')
      }
      if (product.images[id].isMain == '1') {
        showToast('Предупреждение', 'Выберите главное изображение', 'info')
      }

      renderVariantGallery(product, product.images[id].variantId);
      closeModal('remove-image');
      break;
    }
    case 'reload-images':{
      presave();
      renderVariantGallery(product);
      break;
    }
    case 'save-product': {
      presave();
      let response = await api.post('/product', product);
      showToast('Успешно', response.message, 'success');
      renderCatalog();
      closeModal('product-modal');
      break;
    }
    case 'close':{
      let backdrop = btn.closest('.modal-backdrop');
      if (backdrop) closeModal(backdrop.id);
      break;
    }
      
  }

}

function presave() {
  const modal = document.querySelector('#product-modal')
  const inputs = modal.querySelectorAll('input, select, textarea');
  const prs = {
    variants: {},
    images: {}
  };
  let vars = []
  for (let input of inputs) {
    let i = input.name;
    let val = '';

    if (input.type == 'checkbox') {
      val = input.checked ? '1' : '0';
    } else {
      val = input.value;
    };

    if (i.includes('variant')) {
      let s = i.split('-');
      if (typeof (prs['variants'][s[1]]) != 'object') prs['variants'][s[1]] = {
        variantId: s[1],
        attributes: {},
        action: 'update'
      };
      prs['variants'][s[1]][s[2]] = val;
      if (s[2] == 'action') prs['variants'][s[1]].action = val == 'undefined' ? 'update' : val;
      continue;
    }
    if (i.includes('image')) {
      let v = i.split('-');
      prs['images'][v[1]] = {
        id: v[1],
        isMain: v[4],
        imageUrl: val,
        sortOrder: v[3],
        variantId: v[2],
        action: v[5]
      };
      continue;
    }
    if (i.includes('attr')) {
      let a = i.split('-');
      prs['variants'][a[1]]['attributes'][a[2]] = {
        attributeId: a[2],
        title: a[3],
        value: val,
        action: a[4] || 'update',
        unit: input.getAttribute('unit'),
        type: input.getAttribute('itype')
      };
      continue
    }
    if (i.includes('category') || i.includes('brand')) {
      let bc = i.split('-');
      prs[bc[1] + 'Id'] = bc[2];
      prs[bc[1]] = val;
      continue
    }

    if (i.includes('general')) {
      let g = i.split('-');
      prs[g[1]] = val;
      continue
    }
  }
  product = prs;
}

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

  let closeBtn = toast.querySelector('.toast-close');
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
      showToast('Заказ не найден', `
      Заказ с номером "${cleanNum}"
      отсутствует в системе шоурумов `, 'danger');
      return;
    }

    renderPickupOrder(order);
    showToast('Заказ найден', `
      Загружен заказ $ {
        order.orderNumber
      }($ {
        order.customer.name
      })
      `, 'info');
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
    badgeElem.className = `
      badge $ {
        order.statusBadge
      }
      `;
    badgeElem.textContent = order.statusTitle;
  }

  // Customer info
  const nameElem = document.getElementById('customer-display-name');
  if (nameElem) nameElem.textContent = order.customer.name;

  const phoneElem = document.getElementById('customer-display-phone');
  if (phoneElem) phoneElem.textContent = order.customer.phone;

  const bonusElem = document.getElementById('customer-display-bonuses');
  if (bonusElem) bonusElem.textContent = `
      $ {
        order.customer.bonuses
      }
      баллов `;

  // Total
  const totalElem = document.getElementById('display-order-total');
  if (totalElem) totalElem.textContent = `
      $ {
        order.total.toLocaleString('ru-RU')
      }₽
      `;

  // Items List
  const itemsContainer = document.getElementById('order-items-checklist');
  if (itemsContainer) {
    itemsContainer.innerHTML = '';
    order.items.forEach(item => {
      const row = document.createElement('div');
      row.className = 'check-item-row';
      row.innerHTML = ` <
      div class = "check-item-left" >
        <
        input type = "checkbox"
      class = "check-checkbox"
      id = "item-chk-${item.id}" >
        <
        img src = "${item.image}"
      alt = "${item.title}"
      class = "check-item-img" >
        <
        div >
        <
        div class = "check-item-title" > $ {
          item.title
        } < /div> <
        div class = "check-item-meta" > $ {
          item.variant
        }•
      Артикул: < strong > $ {
          item.article
        } < /strong> • Штрих-код: ${item.barcode}</div >
        <
        /div> <
        /div> <
        div class = "check-item-qty" > $ {
          item.amount
        }
      шт. < /div>
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
    receiptItems.innerHTML = order.items.map(item => ` <
      div class = "receipt-line" >
        <
        span > $ {
          item.title
        }($ {
          item.variant
        }) < /span> <
        /div> <
        div class = "receipt-line"
      style = "color: #666; font-size: 11px;" >
        <
        span > $ {
          item.amount
        }
      шт x $ {
        item.price.toLocaleString('ru-RU')
      }₽ < /span> <
      span > $ {
          (item.amount * item.price).toLocaleString('ru-RU')
        }₽ < /span> <
        /div>
      `).join('');
  }

  const receiptTotal = document.getElementById('receipt-total-sum');
  if (receiptTotal) receiptTotal.textContent = `
      $ {
        order.total.toLocaleString('ru-RU')
      }₽
      `;
}

function addOrderToTodayLog() {
  const todayTable = document.getElementById('today-log-tbody');
  if (!todayTable) return;

  const timeStr = new Date().toLocaleTimeString('ru-RU', {
    hour: '2-digit',
    minute: '2-digit'
  });
  const newRow = document.createElement('tr');
  newRow.innerHTML = ` <
      td > < strong > SW - 2026 - 0002 < /strong></td >
        <
        td > $ {
          timeStr
        } < /td> <
        td > Анна Меломанова < /td> <
        td > 1 товар < /td> <
        td > 41 990₽ < /td> <
        td > < span class = "badge badge-success" > Выдан < /span></td >
        <
        td >
        <
        button class = "table-action-btn"
      title = "Печать чека"
      onclick = "openModal('receipt-modal')" >
        <
        i class = "bi bi-printer" > < /i> <
        /button> <
        /td>
      `;
  todayTable.insertBefore(newRow, todayTable.firstChild);
}

async function getProducts(params) {

  const response = fetch('/api/v1/catalog', {
      method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
      return data
    })
    .catch(error => {
      showToast('Ошибка', error, 'danger');
    });

  return response
}

async function Brands(params, type) {
  params['type'] = type;
  const response = fetch('/api/v1/brands', {
      method: 'POST',
      body: prepareData(params),
    })
    .then(response => response.json())
    .then(data => {
      return data
    })
    .catch(error => {
      showToast('Ошибка', error, 'danger');
    });

  return response
}

async function Categories(params, type, parentOnly = false) {
  params['type'] = type;
  if (parentOnly) params['parent'] = '1';
  const response = fetch('/api/categories.php', {
      method: 'POST',
      body: prepareData(params),
    })
    .then(response => response.json())
    .then(data => {
      return data
    })
    .catch(error => {
      showToast('Ошибка', error, 'danger');
    });

  return response
}

async function Attributes(params, type) {
  params['type'] = type;
  const response = fetch('/api/attributes.php', {
      method: 'POST',
      body: prepareData(params),
    })
    .then(response => response.json())
    .then(data => {
      return data
    })
    .catch(error => {
      showToast('Ошибка', error, 'danger');
    });

  return response
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
      showToast('Ошибка', error, 'danger');
    });

  return response
}

async function renderSelectCategories(query) {
  categories = await Categories(getFilterParams(query), 'get', lastSearchCat);
  const box = document.querySelector('#select-category-list');
  let html = '';
  for (const c of categories) {
    if (c.parentId == null) {
      html += `<tr style="background: rgba(255, 255, 255, 0.02); cursor: pointer;" modal-action-button='select-category' modal-action-data='${c.id}'>
                  <td><strong style="color: var(--text-muted); font-family: var(--font-mono);">#${c.id}</strong></td>
                  <td>
                    <div style="font-weight: 700; color: var(--text-primary); font-size: 1rem; display: flex; align-items: center; gap: 8px;">
                      ${c.title}
                    </div>
                  </td>
                  <td><code style="color: var(--brand-yellow); font-family: var(--font-mono);">${c.slug}</code></td>
                  <td>${c.isActive == 1 ? '<span class="badge badge-success">Активна</span>' : '<span class="badge badge-muted">Скрыта</span>'}</td>
                </tr>`
    } else {
      html += `<tr>
                  <td><strong style="color: var(--text-muted); font-family: var(--font-mono);">#${c.id}</strong></td>
                  <td>
                    <div style="padding-left: 28px; display: flex; align-items: center; gap: 8px; font-weight: 600; color: var(--text-primary);">
                      <i class="bi bi-arrow-return-right" style="color: var(--border-highlight);"></i>
                      ${c.title}
                    </div>
                  </td>
                  <td><code style="color: var(--text-secondary); font-family: var(--font-mono);">${c.slug}</code></td>
                  <td>${c.isActive == 1 ? '<span class="badge badge-success">Активна</span>' : '<span class="badge badge-muted">Скрыта</span>'}</td>
                </tr>`
    }
  }
  box.innerHTML = html;
}

async function renderSelectAttributes(query) {
  let attributes = api.get('/attributes', getFilterParams(query));
  const box = document.querySelector('#select-category-list');
  let html = '';
  for (const c of categories) {
    if (c.parentId == null) {
      html += `<tr style="background: rgba(255, 255, 255, 0.02); cursor: pointer;" modal-action-button='select-category' modal-action-data='${c.id}'>
                  <td><strong style="color: var(--text-muted); font-family: var(--font-mono);">#${c.id}</strong></td>
                  <td>
                    <div style="font-weight: 700; color: var(--text-primary); font-size: 1rem; display: flex; align-items: center; gap: 8px;">
                      ${c.title}
                    </div>
                  </td>
                  <td><code style="color: var(--brand-yellow); font-family: var(--font-mono);">${c.slug}</code></td>
                  <td>${c.isActive == 1 ? '<span class="badge badge-success">Активна</span>' : '<span class="badge badge-muted">Скрыта</span>'}</td>
                </tr>`
    } else {
      html += `<tr>
                  <td><strong style="color: var(--text-muted); font-family: var(--font-mono);">#${c.id}</strong></td>
                  <td>
                    <div style="padding-left: 28px; display: flex; align-items: center; gap: 8px; font-weight: 600; color: var(--text-primary);">
                      <i class="bi bi-arrow-return-right" style="color: var(--border-highlight);"></i>
                      ${c.title}
                    </div>
                  </td>
                  <td><code style="color: var(--text-secondary); font-family: var(--font-mono);">${c.slug}</code></td>
                  <td>${c.isActive == 1 ? '<span class="badge badge-success">Активна</span>' : '<span class="badge badge-muted">Скрыта</span>'}</td>
                </tr>`
    }
  }
  box.innerHTML = html;
}

function renderProductVariants(product) {
  let box = document.getElementById('product-variants');
  let html = ''
  for (let v of Object.keys(product.variants)) {
    v = product.variants[v];
    html += `
                <tr ${v.action == 'remove' ? "style='display:none'" : ''}>                    
                  <td>
                    <input type="text" class="form-control" name='variant-${v.variantId}-action' value="${v.action}" style="display:none;">
                    <input type="text" class="form-control" name='variant-${v.variantId}-variant' value="${v.variant}" style="min-width: 170px;">
                  </td>
                  <td><input type="text" class="form-control" placeholder='SKU...' name='variant-${v.variantId}-article' value="${v.article}"
                      style="font-family: var(--font-mono); width: 140px;"></td>
                  <td><input type="text" class="form-control" placeholder='EAN-13...' name='variant-${v.variantId}-barcode' value="${v.barcode}"
                      style="font-family: var(--font-mono); width: 140px;"></td>
                  <td><input type="number" class="form-control" name='variant-${v.variantId}-price' value="${v.price}" placeholder='Цена...' style="width: 110px; font-weight: 700;">
                  </td>
                  <td><input type="number" class="form-control" name='variant-${v.variantId}-oldPrice' value="${v.oldPrice}" placeholder='Старая уена...' style="width: 110px;"></td>
                  <td><input type="number" class="form-control" name='variant-${v.variantId}-amount' value="${v.amount}" placeholder='Остаток...' style="width: 80px; font-weight: 700;"></td>
                  <td> 
                      <label class="switch-label">
                        <input type="checkbox" class="switch-input" name="variant-${v.variantId}-isActive" ${v.isActive == 1 ? 'checked' : ''}> 
                        <span class="switch-slider"></span>
                      </label>
                  </td>
                  <td>
                    <div class="table-actions" style="justify-content: flex-end;">
                     <button class="table-action-btn" title="Копировать вариант" modal-action-button='copy-variant' modal-action-data='${v.variantId}'>
                        <i class="bi bi-copy"></i>
                      </button>
                    <button class="table-action-btn delete-btn" title="Удалить вариант" data-modal-target="remove-variant" data-modal-variant-id='${v.variantId}'><i
                        class="bi bi-trash"></i></button>
                        </div>
                  </td>
                </tr>`

  };
  box.innerHTML = html;
}

function renderVariantAttributes(product, setactive = '') {
  let box = document.getElementById('attributes-box');
  let navtabs = document.createElement('div');
  navtabs.className = 'nav-tabs';
  let variantTabs = document.createElement('div');
  for (let v of Object.keys(product.variants)) {

    let vid = v;
    v = product.variants[v];
    let active = setactive == vid ? ' active' : '';
    navtabs.innerHTML += `<button class="tab-btn${active}" data-tab="tab-variant-${vid}" data-tab-level='2'>
                            <i class="bi bi-info-circle"></i> ${v.variant}
                          </button>`;

    let variantTab = document.createElement('div');
    variantTab.className = "tab-pane" + active;
    variantTab.id = 'tab-variant-' + v.variantId;
    variantTab.setAttribute('data-tab-level', '2');

    let grid = document.createElement('div');
    grid.className = 'attr-grid';
    variantTab.innerHTML = `
          <div style="display: flex; justify-content: end; align-items: center; margin-bottom: 16px;">
            <button class="btn btn-brand btn-sm" type="button" data-modal-target='select-attribute' modal-action-data='${vid}'>
              <i class="bi bi-plus-lg"></i> Добавить атрибут
            </button>    
    `

    for (let a of Object.keys(v.attributes)) {
      a = v.attributes[a];
      let display = a.action == 'remove' ? "style='display:none;'" : '';
      switch (a.type) {
        case 'number':
          grid.innerHTML += `
          <div class="form-group" ${display}>
              <label class="form-label">${a.title} ${a.unit ? '(' + a.unit + ')' : ''}</label>
              <div class='attr-input'>
              <input type="number" class="form-control" name='attr-${v.variantId}-${a.attributeId}-${a.title}${a.action ? '-' + a.action : ''}' title="${a.title}" unit="${a.unit}" itype='${a.type}' value="${a.value}" placeholder='Значение...'>
                <button class="table-action-btn delete-btn" title="Удалить" data-modal-target="remove-attribute" data-modal-attr-id='${v.variantId}-${a.attributeId}'><i class="bi bi-trash-fill"></i></button>
              </div>
            </div>
            `
          break;

        case 'string':
          grid.innerHTML += `
          <div class="form-group" ${display}>
              <label class="form-label">${a.title}</label>
              <div class='attr-input'>
              <input type="text" class="form-control" name='attr-${v.variantId}-${a.attributeId}-${a.title}${a.action ? '-' + a.action : ''}' title="${a.title}" unit="${a.unit}" itype='${a.type}' value="${a.value}" placeholder='Значение...'>
                <button class="table-action-btn delete-btn" title="Удалить" data-modal-target="remove-attribute" data-modal-attr-id='${v.variantId}-${a.attributeId}'><i class="bi bi-trash-fill"></i></button>
              </div>
            </div>
            `
          break;

        case 'boolean':
          grid.innerHTML += `
          <div class="form-group" ${display}>
              <label class="form-label">${a.title}</label>
              <div class='attr-input'>
              <select class="form-control" title="${a.title}" unit="${a.unit}" itype='${a.type}' name='attr-${v.variantId}-${a.attributeId}-${a.title}${a.action ? '-' + a.action : ''}'>
                <option value="1" ${a.value == 1 ? 'selected' : ''}>Да</option>
                <option value="0" ${a.value == 0 ? 'selected' : ''}>Нет</option>
              </select>
                <button class="table-action-btn delete-btn" title="Удалить" data-modal-target="remove-attribute" data-modal-attr-id='${v.variantId}-${a.attributeId}'><i class="bi bi-trash-fill"></i></button>
            </div>
                </div>
            `
          break;
      }
      variantTab.append(grid);
    }
    variantTabs.append(variantTab);
  }
  box.innerHTML = '';
  box.append(navtabs);
  box.append(variantTabs);
}

function renderVariantGallery(product, setactive = '') {
  let box = document.getElementById('gallery-box');
  box.innerHTML = '';
  let navtabs = document.createElement('div');
  navtabs.className = 'nav-tabs';
  let variantTabs = document.createElement('div');
  for (let v of Object.keys(product.variants)) {
    let vid = v;
    v = product.variants[v];
    let active = setactive == vid ? ' active' : '';
    navtabs.innerHTML += `<button class="tab-btn${active}" data-tab="tab-variant-${v.variantId}" data-tab-level='2'>
                            <i class="bi bi-info-circle"></i> ${v.variant}
                          </button>`;

    let variantTab = document.createElement('div');
    variantTab.className = "tab-pane" + active;
    variantTab.id = 'tab-variant-' + v.variantId;
    variantTab.setAttribute('data-tab-level', '2');

    let dropzone = document.createElement('div');
    dropzone.className = "dropzone-box";
    dropzone.innerHTML = `
            <i class="bi bi-cloud-arrow-up-fill dropzone-icon"></i>
            <div class="dropzone-text">Перетащите фотографии сюда или нажмите для выбора</div>
            <div class="dropzone-subtext">Поддерживаются WebP, PNG, JPG высокой четкости</div>
            `;

    let grid = document.createElement('div');
    grid.className = 'gallery-grid';

    for (let i of Object.keys(product.images)) {
      i = product.images[i];
      if (i.variantId !== v.variantId) continue;

      grid.innerHTML += `
            <div class="gallery-item ${i.isMain == 1 ? 'is-main' : ''}" ${i.action == 'remove' ? "style='display:none;'" : ''}>
              <img src="${i.imageUrl}" modal-action-button='select-main-image' modal-action-data='${i.id}' style='cursor:pointer;'>
              ${i.isMain == 1 ? '<span class="gallery-item-badge">Главное</span>' : ''}
              <button class="gallery-item-delete" type="button" data-modal-target='remove-image'  data-modal-image-id='${i.id}' title="Удалить"><i class="bi bi-trash"></i></button>
              <input type="text" name="image-${i.id}-${v.variantId}-${i.sortOrder}-${i.isMain}${i.action ? '-' + i.action : '-update'}" id='image-url' 
               value='${i.imageUrl}' style='display: none'>
            </div>
      `
    }
    variantTab.append(dropzone);
    variantTab.append(grid);
    variantTabs.append(variantTab);
  }

  box.append(navtabs);
  box.append(variantTabs);
}

export function formatPrice(num) {
  return (num || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + " ₽";
}

export function getFilterParams(query) {
  const params = new URLSearchParams(window.location.search);
  const filterParams = {
    query: query || params.get('q'),
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

export async function loadSvg(url) {
  try {
    const response = await fetch(url);
    if (!response.ok) throw new Error(`
      Ошибка загрузки лого `);
    return await response.text();
  } catch (error) {
    console.error(error);
    return '<span class="error-logo">X</span>';
  }
}