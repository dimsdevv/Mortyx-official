<!-- Slide-Over Shopping Cart Drawer (Clean Minimalist CALLE Style) -->
<div id="cartDrawerBackdrop" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 transition-opacity duration-300 opacity-0 pointer-events-none"></div>

<aside id="cartDrawer" class="fixed top-0 right-0 h-full w-full max-w-md bg-white border-l border-zinc-200 z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col shadow-2xl">
    
    <!-- Drawer Header -->
    <div class="p-6 border-b border-zinc-200 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <span class="font-heading font-black text-base uppercase tracking-tight text-black">Shopping Bag</span>
            <span id="cartDrawerCount" class="text-xs text-zinc-500 font-mono font-bold">(0)</span>
        </div>
        <button id="cartDrawerCloseBtn" aria-label="Close cart" class="p-1.5 text-zinc-400 hover:text-black transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Free Shipping Progress Tracker (Minimalist) -->
    <div class="bg-zinc-50 px-6 py-3.5 border-b border-zinc-200">
        <div class="flex items-center justify-between text-xs mb-1.5 font-medium">
            <span id="freeShippingText" class="text-zinc-600">Belanja Rp 500.000 lagi untuk Bebas Ongkir</span>
            <span id="freeShippingStatus" class="text-zinc-500 font-mono text-[11px]">Rp 0 / 500K</span>
        </div>
        <div class="w-full bg-zinc-200 h-1 rounded-full overflow-hidden">
            <div id="freeShippingBar" class="bg-black h-full transition-all duration-500 w-0"></div>
        </div>
    </div>

    <!-- Cart Items Container (Scrollable) -->
    <div id="cartItemList" class="flex-1 overflow-y-auto p-6 space-y-4 divide-y divide-zinc-100">
        <!-- Dynamic via JS -->
        <div id="emptyCartNotice" class="h-64 flex flex-col items-center justify-center text-center space-y-3 text-zinc-400">
            <svg class="w-10 h-10 stroke-[1.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <p class="text-xs font-medium text-zinc-500">Keranjang belanja Anda masih kosong.</p>
            <button onclick="closeCartDrawer()" class="text-xs uppercase font-bold tracking-wider text-black underline underline-offset-4 hover:text-zinc-600">Mulai Belanja</button>
        </div>
    </div>

    <!-- Drawer Footer / Checkout CTA -->
    <div class="p-6 border-t border-zinc-200 bg-white space-y-3">
        <div class="flex justify-between items-center text-sm">
            <span class="text-zinc-500 text-xs uppercase tracking-wider font-semibold">Subtotal</span>
            <span id="cartSubtotalPrice" class="font-bold text-black font-mono text-base">Rp 0</span>
        </div>
        <p class="text-[11px] text-zinc-400">Pajak dan ongkos kirim akan dikalkulasi saat checkout.</p>
        
        <button id="checkoutBtn" class="w-full py-3.5 px-4 bg-black text-white font-heading font-black text-xs tracking-widest uppercase hover:bg-zinc-800 transition-colors flex items-center justify-center space-x-2">
            <span>Proceed to Checkout</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </button>
    </div>
</aside>
