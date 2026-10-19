    import { api } from "../api.js";
    import { getFilterParams } from "../admin.js";
    import { formatPrice } from "../admin.js";

    function renderFilters() {
        const currentCategory = document.querySelector('#filter-cat');
        const currentBrand = document.querySelector('#filter-brand');
    }

    export async function renderCatalog() {
        const box = document.querySelector('#products-list');
        if (!box) return;
        
        let products = await api.get('/catalog', getFilterParams());
        console.log(products);
        let html = '';
        products.forEach(p => {
            html += ` <tr>
                  <td><input type="checkbox"></td>
                  <td>
                    <div class="table-product-cell">
                      <img
                        src="${p.variants[0].image}"
                        alt="${p.brand}" class="product-thumbnail">
                      <div class="product-cell-info">
                        <span class="product-cell-title">${p.productTitle}</span>
                        <span class="product-cell-sku">${p.category}</span>
                      </div>
                    </div>
                  </td>
                  <td><strong>${p.brand}</strong></td>
                  <td class="price-cell">
                    <span class="price-current">${formatPrice(p.variants[0].price)}</span>
                    <span class="price-old">${formatPrice(p.variants[0].oldPrice)}</span>
                  </td>
                  <td><span class="stock-badge stock-in">${p.variants[0].amount} шт.</span></td>
                  <td>
                    ${p.isActive == '1' ? '<span class="badge badge-success">Активен</span>' : '<span class="badge badge-muted">Не активен</span>'}
                    ${p.isFeatured == '1' ? '<span class="badge badge-brand">Хит</span>' : ''}
                    
                  </td>
                  <td>
                    <div class="table-actions" style="justify-content: flex-end;">
                      <button class="table-action-btn edit-btn" title="Редактировать" data-modal-target="product-modal" data-modal-product-id='${p.id}'>
                        <i class="bi bi-pencil-fill"></i>
                      </button>
                      <button class="table-action-btn delete-btn" title="Удалить">
                        <i class="bi bi-trash-fill"></i>
                      </button>
                    </div>
                  </td>
                </tr>`
        });
        box.innerHTML = html;
    }