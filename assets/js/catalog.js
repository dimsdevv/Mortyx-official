/**
 * MORTYX Apparel — Client-side Catalog, QuickView & Cart Management
 * Clean Minimalist UI (CALLE Style)
 */

(function () {
    'use strict';

    // 1. Data & State Initialization
    const products = window.MORTYX_PRODUCTS || [];
    let cart = [];
    try {
        cart = JSON.parse(localStorage.getItem('mortyx_cart')) || [];
    } catch (e) {
        cart = [];
    }

    let activeCategory = 'all';
    let searchQuery = '';
    let activeSort = 'featured';

    // Format Rupiah helper
    function formatRupiah(number) {
        return 'Rp ' + Number(number).toLocaleString('id-ID');
    }

    // 2. DOM Elements
    const productGrid = document.getElementById('productGrid');
    const productCards = document.querySelectorAll('.product-card');
    const categoryBtns = document.querySelectorAll('.category-filter-btn');
    const sortSelect = document.getElementById('catalogSortSelect');
    const searchInput = document.getElementById('catalogSearchInput');
    const productCounterText = document.getElementById('productCounterText');
    const noProductsFound = document.getElementById('noProductsFound');
    const resetFiltersBtn = document.getElementById('resetFiltersBtn');

    // Drawer & Modals
    const cartDrawer = document.getElementById('cartDrawer');
    const cartDrawerBackdrop = document.getElementById('cartDrawerBackdrop');
    const cartDrawerOpenBtn = document.getElementById('cartDrawerOpenBtn');
    const cartDrawerCloseBtn = document.getElementById('cartDrawerCloseBtn');
    const cartCountBadge = document.getElementById('cartCountBadge');
    const cartDrawerCount = document.getElementById('cartDrawerCount');
    const cartItemList = document.getElementById('cartItemList');
    const emptyCartNotice = document.getElementById('emptyCartNotice');
    const cartSubtotalPrice = document.getElementById('cartSubtotalPrice');
    const freeShippingText = document.getElementById('freeShippingText');
    const freeShippingStatus = document.getElementById('freeShippingStatus');
    const freeShippingBar = document.getElementById('freeShippingBar');
    const checkoutBtn = document.getElementById('checkoutBtn');

    const quickViewBackdrop = document.getElementById('quickViewBackdrop');
    const quickViewDialog = document.getElementById('quickViewDialog');
    const quickViewContent = document.getElementById('quickViewContent');
    const closeQuickViewBtn = document.getElementById('closeQuickViewBtn');

    const searchToggleBtn = document.getElementById('searchToggleBtn');
    const searchDropdown = document.getElementById('searchDropdown');
    const closeSearchBtn = document.getElementById('closeSearchBtn');
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenuDropdown = document.getElementById('mobileMenuDropdown');

    // 3. Filter & Sort Logic
    function applyFilters() {
        let visibleCount = 0;
        const cardsArray = Array.from(productCards);

        // Sorting
        cardsArray.sort((a, b) => {
            const priceA = parseFloat(a.dataset.price);
            const priceB = parseFloat(b.dataset.price);
            const ratingA = parseFloat(a.dataset.rating);
            const ratingB = parseFloat(b.dataset.rating);

            if (activeSort === 'price-asc') return priceA - priceB;
            if (activeSort === 'price-desc') return priceB - priceA;
            if (activeSort === 'rating') return ratingB - ratingA;
            return parseInt(a.dataset.id) - parseInt(b.dataset.id);
        });

        cardsArray.forEach(card => {
            productGrid.appendChild(card);
            const cardCat = card.dataset.category;
            const cardName = card.dataset.name;

            const matchesCategory = (activeCategory === 'all' || cardCat === activeCategory);
            const matchesSearch = (searchQuery === '' || cardName.includes(searchQuery.toLowerCase()));

            if (matchesCategory && matchesSearch) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Update counter & empty state
        if (productCounterText) {
            productCounterText.textContent = `Showing ${visibleCount} pieces`;
        }
        if (noProductsFound) {
            noProductsFound.classList.toggle('hidden', visibleCount > 0);
        }
    }

    // Category button listeners (Clean Minimalist Active States)
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            categoryBtns.forEach(b => {
                b.className = 'category-filter-btn px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded transition-all whitespace-nowrap text-zinc-600 hover:text-black hover:bg-zinc-100';
            });
            btn.className = 'category-filter-btn px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded transition-all whitespace-nowrap bg-black text-white';
            activeCategory = btn.dataset.category;
            applyFilters();
        });
    });

    if (sortSelect) {
        sortSelect.addEventListener('change', (e) => {
            activeSort = e.target.value;
            applyFilters();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            searchQuery = e.target.value.trim();
            applyFilters();
        });
    }

    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', () => {
            activeCategory = 'all';
            searchQuery = '';
            if (searchInput) searchInput.value = '';
            categoryBtns[0].click();
            applyFilters();
        });
    }

    // 4. Cart Drawer Logic
    function saveCart() {
        try {
            localStorage.setItem('mortyx_cart', JSON.stringify(cart));
        } catch (e) {
            console.error('Could not save cart', e);
        }
        renderCart();
    }

    function renderCart() {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

        if (cartCountBadge) cartCountBadge.textContent = `(${totalItems})`;
        if (cartDrawerCount) cartDrawerCount.textContent = `(${totalItems})`;
        if (cartSubtotalPrice) cartSubtotalPrice.textContent = formatRupiah(subtotal);

        // Free shipping calculation (Min Rp 500.000)
        const threshold = 500000;
        if (freeShippingBar && freeShippingText && freeShippingStatus) {
            const percentage = Math.min(100, Math.round((subtotal / threshold) * 100));
            freeShippingBar.style.width = percentage + '%';
            freeShippingStatus.textContent = `${formatRupiah(subtotal)} / 500K`;

            if (subtotal >= threshold) {
                freeShippingText.innerHTML = '<span class="text-zinc-900 font-bold">✓ Selamat! Anda berhak Bebas Ongkir</span>';
                freeShippingBar.className = 'bg-black h-full transition-all duration-500';
            } else {
                const diff = threshold - subtotal;
                freeShippingText.textContent = `Belanja ${formatRupiah(diff)} lagi untuk Bebas Ongkir`;
                freeShippingBar.className = 'bg-black h-full transition-all duration-500';
            }
        }

        // Render Cart Item Rows
        if (!cartItemList) return;

        if (cart.length === 0) {
            cartItemList.innerHTML = `
                <div id="emptyCartNotice" class="h-64 flex flex-col items-center justify-center text-center space-y-3 text-zinc-400">
                    <svg class="w-10 h-10 stroke-[1.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <p class="text-xs font-medium text-zinc-500">Keranjang belanja Anda masih kosong.</p>
                    <button id="browseFromEmptyCart" class="text-xs uppercase font-bold tracking-wider text-black underline underline-offset-4 hover:text-zinc-600">Jelajahi Koleksi</button>
                </div>
            `;
            const browseBtn = document.getElementById('browseFromEmptyCart');
            if (browseBtn) browseBtn.addEventListener('click', closeCartDrawer);
            return;
        }

        let html = '';
        cart.forEach((item, index) => {
            html += `
                <div class="py-4 flex items-center space-x-4">
                    <img src="${item.image}" alt="${item.name}" class="w-16 h-20 object-cover bg-zinc-100 rounded border border-zinc-200">
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-black truncate uppercase tracking-tight">${item.name}</h4>
                        <div class="flex items-center space-x-2 mt-1 text-[11px] text-zinc-500 font-mono">
                            <span>Size: <strong class="text-black">${item.size}</strong></span>
                            <span>•</span>
                            <span class="text-black font-semibold">${formatRupiah(item.price)}</span>
                        </div>
                        <div class="flex items-center space-x-2 mt-2.5">
                            <button class="cart-qty-btn px-2 py-0.5 bg-zinc-100 hover:bg-zinc-200 text-xs rounded border border-zinc-200 text-black font-bold" data-action="decrease" data-index="${index}">-</button>
                            <span class="text-xs font-mono px-2 font-bold text-black">${item.quantity}</span>
                            <button class="cart-qty-btn px-2 py-0.5 bg-zinc-100 hover:bg-zinc-200 text-xs rounded border border-zinc-200 text-black font-bold" data-action="increase" data-index="${index}">+</button>
                            <button class="cart-remove-btn text-[11px] text-zinc-400 hover:text-red-600 ml-auto" data-index="${index}">Hapus</button>
                        </div>
                    </div>
                </div>
            `;
        });
        cartItemList.innerHTML = html;

        // Attach quantity and remove handlers
        cartItemList.querySelectorAll('.cart-qty-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const idx = parseInt(e.target.dataset.index);
                const action = e.target.dataset.action;
                if (action === 'increase') {
                    cart[idx].quantity += 1;
                } else if (action === 'decrease') {
                    if (cart[idx].quantity > 1) {
                        cart[idx].quantity -= 1;
                    } else {
                        cart.splice(idx, 1);
                    }
                }
                saveCart();
            });
        });

        cartItemList.querySelectorAll('.cart-remove-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const idx = parseInt(e.target.dataset.index);
                cart.splice(idx, 1);
                saveCart();
            });
        });
    }

    function openCartDrawer() {
        if (!cartDrawer || !cartDrawerBackdrop) return;
        cartDrawerBackdrop.classList.remove('opacity-0', 'pointer-events-none');
        cartDrawerBackdrop.classList.add('opacity-100');
        cartDrawer.classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
    }

    function closeCartDrawer() {
        if (!cartDrawer || !cartDrawerBackdrop) return;
        cartDrawerBackdrop.classList.add('opacity-0', 'pointer-events-none');
        cartDrawerBackdrop.classList.remove('opacity-100');
        cartDrawer.classList.add('translate-x-full');
        document.body.style.overflow = '';
    }

    window.closeCartDrawer = closeCartDrawer;

    if (cartDrawerOpenBtn) cartDrawerOpenBtn.addEventListener('click', openCartDrawer);
    if (cartDrawerCloseBtn) cartDrawerCloseBtn.addEventListener('click', closeCartDrawer);
    if (cartDrawerBackdrop) cartDrawerBackdrop.addEventListener('click', closeCartDrawer);

    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', () => {
            if (cart.length === 0) {
                alert('Keranjang belanja Anda masih kosong. Silakan pilih produk terlebih dahulu.');
                return;
            }
            alert('Fitur Checkout akan segera hadir pada Tahap 4 (branch: feature/checkout-flow)!');
        });
    }

    // 5. Quick View Modal Logic (Clean Minimalist White)
    function openQuickView(productId) {
        const prod = products.find(p => p.id === parseInt(productId));
        if (!prod || !quickViewContent) return;

        let selectedSize = prod.sizes[0];

        quickViewContent.innerHTML = `
            <div class="relative aspect-[3/4] bg-zinc-100 rounded overflow-hidden border border-zinc-200">
                <img id="modalMainImg" src="${prod.image}" alt="${prod.name}" class="w-full h-full object-cover">
                <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-2.5 py-1 text-[10px] font-mono uppercase text-black border border-zinc-200 font-bold">
                    ${prod.category_name}
                </div>
            </div>
            <div class="flex flex-col justify-between space-y-5">
                <div>
                    <span class="text-[11px] font-mono uppercase text-zinc-400 tracking-wider">SEASON 2026 // DROP 04</span>
                    <h3 class="font-heading font-black text-2xl text-black tracking-tight uppercase mt-1">${prod.name}</h3>
                    <div class="mt-2 flex items-baseline space-x-3">
                        <span class="font-mono text-xl font-bold text-black">${formatRupiah(prod.price)}</span>
                        ${prod.original_price > prod.price ? `<span class="font-mono text-sm text-zinc-400 line-through">${formatRupiah(prod.original_price)}</span>` : ''}
                    </div>
                    <p class="text-xs text-zinc-500 mt-3 leading-relaxed">${prod.description}</p>
                </div>

                <!-- Size Selector -->
                <div>
                    <div class="flex justify-between items-center text-xs font-bold uppercase tracking-wider text-black mb-2">
                        <span>Select Size</span>
                        <span class="text-zinc-400 font-mono text-[11px] font-medium">AVAILABLE</span>
                    </div>
                    <div class="flex flex-wrap gap-2" id="modalSizePills">
                        ${prod.sizes.map((s, idx) => `
                            <button type="button" class="size-pill px-3.5 py-2 border text-xs font-mono font-bold rounded transition-all ${idx === 0 ? 'bg-black text-white border-black' : 'bg-white border-zinc-200 text-zinc-800 hover:border-black'}" data-size="${s}">
                                ${s}
                            </button>
                        `).join('')}
                    </div>
                </div>

                <!-- Color Swatch Indicator -->
                <div class="flex items-center space-x-2 text-xs text-zinc-500">
                    <span class="font-semibold uppercase tracking-wider text-[11px]">Colorway:</span>
                    <span class="w-3.5 h-3.5 rounded-full inline-block border border-zinc-300 shadow-sm" style="background-color: ${prod.color_hex};"></span>
                    <span class="text-black font-semibold">${prod.color}</span>
                </div>

                <!-- Add To Bag Button -->
                <div class="pt-2">
                    <button id="modalAddToCartBtn" class="w-full py-4 bg-black hover:bg-zinc-800 text-white font-heading font-black text-xs uppercase tracking-widest transition-colors rounded flex items-center justify-center space-x-2 shadow">
                        <span>ADD TO CART</span>
                        <span>•</span>
                        <span class="font-mono font-bold">${formatRupiah(prod.price)}</span>
                    </button>
                </div>
            </div>
        `;

        // Handle Size Click
        const sizePills = quickViewContent.querySelectorAll('.size-pill');
        sizePills.forEach(pill => {
            pill.addEventListener('click', (e) => {
                sizePills.forEach(p => {
                    p.className = 'size-pill px-3.5 py-2 border text-xs font-mono font-bold rounded transition-all bg-white border-zinc-200 text-zinc-800 hover:border-black';
                });
                pill.className = 'size-pill px-3.5 py-2 border text-xs font-mono font-bold rounded transition-all bg-black text-white border-black';
                selectedSize = pill.dataset.size;
            });
        });

        // Handle Add To Cart from Modal
        const addBtn = quickViewContent.querySelector('#modalAddToCartBtn');
        if (addBtn) {
            addBtn.addEventListener('click', () => {
                const existingIndex = cart.findIndex(c => c.id === prod.id && c.size === selectedSize);
                if (existingIndex > -1) {
                    cart[existingIndex].quantity += 1;
                } else {
                    cart.push({
                        id: prod.id,
                        name: prod.name,
                        price: prod.price,
                        image: prod.image,
                        size: selectedSize,
                        quantity: 1
                    });
                }
                saveCart();
                closeQuickView();
                openCartDrawer();
            });
        }

        // Show Modal
        if (quickViewBackdrop && quickViewDialog) {
            quickViewBackdrop.classList.remove('opacity-0', 'pointer-events-none');
            quickViewBackdrop.classList.add('opacity-100');
            quickViewDialog.classList.remove('scale-95');
            quickViewDialog.classList.add('scale-100');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeQuickView() {
        if (!quickViewBackdrop || !quickViewDialog) return;
        quickViewBackdrop.classList.add('opacity-0', 'pointer-events-none');
        quickViewBackdrop.classList.remove('opacity-100');
        quickViewDialog.classList.add('scale-95');
        quickViewDialog.classList.remove('scale-100');
        document.body.style.overflow = '';
    }

    // Attach quickview triggers
    document.querySelectorAll('.quickview-btn, .quickview-trigger').forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const pid = trigger.dataset.productId;
            if (pid) openQuickView(pid);
        });
    });

    if (closeQuickViewBtn) closeQuickViewBtn.addEventListener('click', closeQuickView);
    if (quickViewBackdrop) {
        quickViewBackdrop.addEventListener('click', (e) => {
            if (e.target === quickViewBackdrop) closeQuickView();
        });
    }

    // Escape Key Listener
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeQuickView();
            closeCartDrawer();
        }
    });

    // 6. Header Nav Interactions
    if (searchToggleBtn && searchDropdown) {
        searchToggleBtn.addEventListener('click', () => {
            searchDropdown.classList.toggle('hidden');
            if (!searchDropdown.classList.contains('hidden') && searchInput) {
                searchInput.focus();
            }
        });
    }

    if (closeSearchBtn && searchDropdown) {
        closeSearchBtn.addEventListener('click', () => {
            searchDropdown.classList.add('hidden');
            searchQuery = '';
            if (searchInput) searchInput.value = '';
            applyFilters();
        });
    }

    if (mobileMenuBtn && mobileMenuDropdown) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenuDropdown.classList.toggle('hidden');
        });
    }

    // Initial render
    renderCart();
})();
