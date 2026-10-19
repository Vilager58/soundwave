import {
    api
} from "../api.js";
import { loadSvg } from "../admin.js";
import { pageData } from "../admin.js";

async function renderBrands(query) {
    pageData.brands = await api.get('/brands');
    const box = document.querySelector('#brand-list');
    let html = '';
    for (const b of pageData.brands) {
        html += `<div class="brand-card">
    <div>
        <div class="brand-card-top">
        <div class="brand-logo-preview">
            ${await loadSvg(b.logoUrl)}
        </div>
        <div>
            <div class="brand-card-title">${b.title}</div>
            <div class="brand-card-country"><i class="bi bi-geo-alt-fill"></i> ${b.country}</div>
        </div>
        </div>
        <p class="brand-card-desc">
        ${b.description}
        </p>
    </div>
    <div class="brand-card-footer">
        <span class="badge badge-purple">${b.amount} Товаров</span>
        <div class="table-actions">
        <button class="table-action-btn edit-btn" title="Редактировать" data-modal-target="brand-modal" data-modal-brand-id='${b.id}'><i class="bi bi-pencil-fill"></i></button>
        <button class="table-action-btn delete-btn" title="Удалить"     data-modal-target="remove-brand" data-modal-brand-id='${b.id}'><i class="bi bi-trash-fill"></i></button>
        </div>
    </div>
    </div>`;
    };
    box.innerHTML = html;
}

document.addEventListener('DOMContentLoaded', () => {
    renderBrands();
});