(() => {
    const TOKEN_KEY = 'kateringku_api_token';
    const API_BASE = '/api/v1/menus';

    class ApiError extends Error {
        constructor(message, status, errors = null) {
            super(message);
            this.name = 'ApiError';
            this.status = status;
            this.errors = errors;
        }
    }

    function esc(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function getToken() {
        return localStorage.getItem(TOKEN_KEY) || '';
    }

    async function apiRequest(url, options = {}) {
        const headers = {
            Accept: 'application/json',
            ...(options.headers || {}),
        };

        const token = getToken();
        if (token) {
            headers.Authorization = `Bearer ${token}`;
        }

        if (options.body && !(options.body instanceof FormData) && !headers['Content-Type']) {
            headers['Content-Type'] = 'application/json';
        }

        const response = await fetch(url, {
            ...options,
            headers,
        });

        let payload = null;
        const contentType = response.headers.get('content-type') || '';
        if (contentType.includes('application/json')) {
            payload = await response.json();
        }

        if (response.status === 401) {
            localStorage.removeItem(TOKEN_KEY);
            const tokenInput = document.getElementById('sanctum-token');
            if (tokenInput) {
                tokenInput.value = '';
            }
        }

        if (!response.ok) {
            const message = payload?.message || `Permintaan gagal dengan status ${response.status}.`;
            throw new ApiError(message, response.status, payload?.errors || null);
        }

        return payload;
    }

    function tampilkanAlert(pesan, tipe = 'info') {
        const box = document.getElementById('api-alert');
        if (!box) return;

        box.className = 'rounded-lg border px-4 py-3 text-sm';
        if (tipe === 'error') {
            box.classList.add('bg-red-50', 'border-red-200', 'text-red-800');
        } else if (tipe === 'success') {
            box.classList.add('bg-emerald-50', 'border-emerald-200', 'text-emerald-800');
        } else {
            box.classList.add('bg-stone-100', 'border-stone-200', 'text-stone-800');
        }

        box.textContent = pesan;
        box.classList.remove('hidden');
    }

    function tanganiError(err) {
        if (err instanceof ApiError) {
            if (err.status === 401) {
                tampilkanAlert('Sesi atau token API tidak valid (401). Token lokal telah dibersihkan.', 'error');
                return;
            }
            if (err.status === 422 && err.errors) {
                const detail = Object.values(err.errors).flat().join(' ');
                tampilkanAlert(`Validasi gagal: ${detail}`, 'error');
                return;
            }
            tampilkanAlert(err.message, 'error');
            return;
        }

        tampilkanAlert('Terjadi kesalahan jaringan saat menghubungi API.', 'error');
    }

    const tbody = document.getElementById('menu-table-body');
    const form = document.getElementById('api-menu-form');
    const menuIdInput = document.getElementById('menu-id');
    const nameInput = document.getElementById('api-name');
    const categoryInput = document.getElementById('api-category');
    const minOrderInput = document.getElementById('api-min-order');
    const priceInput = document.getElementById('api-price');
    const descInput = document.getElementById('api-description');
    const activeInput = document.getElementById('api-is-active');
    const formTitle = document.getElementById('form-title');
    const submitLabel = document.getElementById('btn-submit-label');
    const resetBtn = document.getElementById('btn-reset-form');
    const filterSearch = document.getElementById('filter-search');
    const filterCategory = document.getElementById('filter-category');
    const tokenInput = document.getElementById('sanctum-token');

    let cachedMenus = [];

    function resetForm() {
        menuIdInput.value = '';
        form.reset();
        minOrderInput.value = '10';
        activeInput.checked = true;
        formTitle.textContent = 'Tambah Menu via API';
        submitLabel.textContent = 'Kirim (POST)';
        resetBtn.classList.add('hidden');
    }

    function renderMenus(items) {
        cachedMenus = items;
        if (!items.length) {
            tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-8 text-center text-stone-500">Tidak ada data menu.</td></tr>';
            return;
        }

        tbody.innerHTML = items.map((menu) => `
            <tr class="hover:bg-stone-50">
                <td class="px-4 py-3 font-medium text-stone-900">${esc(menu.name)}</td>
                <td class="px-4 py-3">${esc(menu.category)}</td>
                <td class="px-4 py-3 text-right tabular-nums">Rp ${Number(menu.price).toLocaleString('id-ID')}</td>
                <td class="px-4 py-3 text-center tabular-nums">${esc(menu.min_order)}</td>
                <td class="px-4 py-3 text-right">
                    <div class="inline-flex gap-2">
                        <button type="button" data-action="edit" data-id="${esc(menu.id)}" class="px-2.5 py-1 rounded border border-stone-300 text-xs font-medium text-stone-700 hover:bg-stone-100">
                            <i class="bi bi-pencil-square"></i> Edit
                        </button>
                        <button type="button" data-action="delete" data-id="${esc(menu.id)}" class="px-2.5 py-1 rounded border border-red-200 text-xs font-medium text-red-700 hover:bg-red-50">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    async function loadMenus() {
        const params = new URLSearchParams();
        if (filterSearch.value.trim() !== '') {
            params.set('search', filterSearch.value.trim());
        }
        if (filterCategory.value !== '') {
            params.set('category', filterCategory.value);
        }

        const query = params.toString();
        const url = query ? `${API_BASE}?${query}` : API_BASE;

        try {
            const res = await apiRequest(url);
            renderMenus(res.data || []);
        } catch (err) {
            tanganiError(err);
        }
    }

    if (tokenInput) {
        tokenInput.value = getToken();
    }

    document.getElementById('btn-save-token')?.addEventListener('click', () => {
        localStorage.setItem(TOKEN_KEY, tokenInput.value.trim());
        tampilkanAlert('Token Sanctum disimpan di localStorage.', 'success');
    });

    document.getElementById('btn-clear-token')?.addEventListener('click', () => {
        localStorage.removeItem(TOKEN_KEY);
        tokenInput.value = '';
        tampilkanAlert('Token Sanctum dihapus.', 'info');
    });

    document.getElementById('btn-refresh')?.addEventListener('click', loadMenus);
    filterCategory?.addEventListener('change', loadMenus);
    resetBtn?.addEventListener('click', resetForm);

    form?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = menuIdInput.value;
        const body = JSON.stringify({
            name: nameInput.value.trim(),
            category: categoryInput.value,
            min_order: Number(minOrderInput.value),
            price: Number(priceInput.value),
            description: descInput.value.trim() || null,
            is_active: activeInput.checked,
        });

        try {
            if (id) {
                await apiRequest(`${API_BASE}/${encodeURIComponent(id)}`, { method: 'PUT', body });
                tampilkanAlert('Menu berhasil diperbarui melalui REST API.', 'success');
            } else {
                await apiRequest(API_BASE, { method: 'POST', body });
                tampilkanAlert('Menu berhasil ditambahkan melalui REST API.', 'success');
            }
            resetForm();
            await loadMenus();
        } catch (err) {
            tanganiError(err);
        }
    });

    tbody.onclick = async (e) => {
        const btn = e.target.closest('button[data-action]');
        if (!btn) return;

        const id = btn.getAttribute('data-id');
        const action = btn.getAttribute('data-action');

        if (action === 'edit') {
            const menu = cachedMenus.find((m) => String(m.id) === String(id));
            if (!menu) return;

            menuIdInput.value = menu.id;
            nameInput.value = menu.name;
            categoryInput.value = menu.category;
            minOrderInput.value = menu.min_order;
            priceInput.value = menu.price;
            descInput.value = menu.description || '';
            activeInput.checked = Boolean(menu.is_active);

            formTitle.textContent = `Edit Menu #${menu.id} via API`;
            submitLabel.textContent = 'Perbarui (PUT)';
            resetBtn.classList.remove('hidden');
            return;
        }

        if (action === 'delete') {
            if (!confirm('Hapus menu ini melalui REST API?')) return;
            try {
                await apiRequest(`${API_BASE}/${encodeURIComponent(id)}`, { method: 'DELETE' });
                tampilkanAlert('Menu berhasil dihapus melalui REST API.', 'success');
                await loadMenus();
            } catch (err) {
                tanganiError(err);
            }
        }
    };

    loadMenus();
})();
