<!-- Slide-Over Shopping Cart Drawer -->
<div id="cartDrawerBackdrop" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 transition-opacity duration-300 opacity-0 pointer-events-none"></div>

<aside id="cartDrawer" class="fixed top-0 right-0 h-full w-full max-w-md bg-[#0e0e11] border-l border-zinc-800 z-50 transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col shadow-2xl">
    
    <!-- Drawer Header -->
    <div class="p-5 border-b border-zinc-800/80 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <span class="font-heading font-black text-lg tracking-tight uppercase">Your Bag</span>
            <span id="cartDrawerCount" class="text-xs bg-zinc-800 text-zinc-400 px-2 py-0.5 rounded-full font-mono">(0)</span>
        </div>
        <button id="cartDrawerCloseBtn" aria-label="Close cart" class="p-2 text-zinc-400 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Free Shipping Progress Tracker -->
    <div class="bg-zinc-950 px-5 py-3 border-b border-zinc-800/80">
        <div class="flex items-center justify-between text-xs mb-1.5 font-medium">
            <span id="freeShippingText" class="text-zinc-300">Belanja Rp 500.000 lagi untuk Bebas Ongkir</span>
            <span id="freeShippingStatus" class="text-zinc-500 font-mono text-[11px]">Rp 0 / 500K</span>
        </div>
        <div class="w-full bg-zinc-800 h-1.5 rounded-full overflow-hidden">
            <div id="freeShippingBar" class="bg-white h-full transition-all duration-500 w-0"></div>
        </div>
    </div>

    <!-- Cart Items Container (Scrollable) -->
    <div id="cartItemList" class="flex-1 overflow-y-auto p-5 space-y-4 divide-y divide-zinc-800/60">
        <!-- Will be populated dynamically by JavaScript -->
        <div id="emptyCartNotice" class="h-64 flex flex-col items-center justify-center text-center space-y-3 text-zinc-500">
            <svg class="w-12 h-12 stroke-[1.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <p class="text-sm font-medium">Keranjang belanja Anda masih kosong.</p>
            <button onclick="closeCartDrawer()" class="text-xs uppercase font-bold tracking-wider text-white underline underline-offset-4 hover:text-zinc-300">Jelajahi Koleksi</button>
        </div>
    </div>

    <!-- Drawer Footer / Checkout CTA -->
    <div class="p-5 border-t border-zinc-800 bg-[#080809] space-y-3">
        <div class="flex justify-between items-center text-sm">
            <span class="text-zinc-400">Subtotal</span>
            <span id="cartSubtotalPrice" class="font-bold text-white font-mono text-base">Rp 0</span>
        </div>
        <p class="text-[11px] text-zinc-500">Pajak dan ongkos kirim dihitung pada saat checkout.</p>
        
        <button id="checkoutBtn" class="w-full py-3.5 px-4 bg-white text-black font-heading font-black text-sm tracking-wider uppercase hover:bg-zinc-200 transition-colors rounded-sm flex items-center justify-center space-x-2">
            <span>Proceed to Checkout</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </button>
    </div>
</aside>

