<?php
require_once __DIR__ . '/data/products.php';

$page_title = 'Drop 04: Contemporary Streetwear';
$categories = get_categories();
$products = get_products();

require_once __DIR__ . '/includes/header.php';
?>

<main class="flex-1 bg-white">

    <!-- Hero Section: CALLE-Style Editorial Full-Bleed Campaign -->
    <section class="relative h-[85vh] sm:h-[88vh] w-full overflow-hidden flex items-center justify-center border-b border-zinc-200 bg-zinc-900">
        <!-- Background Editorial Image -->
        <img 
            src="https://images.unsplash.com/photo-1509967419530-da38b4704bc6?auto=format&fit=crop&w=2400&q=85" 
            alt="MORTYX Season 2026 Campaign" 
            class="w-full h-full object-cover object-center filter contrast-105">
        
        <!-- Subtle Vignette Overlay for Depth -->
        <div class="absolute inset-0 bg-black/25"></div>

        <!-- Single Minimalist Hero CTA (CALLE Style) -->
        <div class="absolute inset-0 flex items-center justify-center z-10">
            <a href="#catalog" class="px-8 py-3.5 bg-black/85 hover:bg-black text-white text-xs sm:text-sm font-bold tracking-widest uppercase rounded shadow-lg backdrop-blur-sm transition-all duration-200 transform hover:scale-105">
                SHOP HERE
            </a>
        </div>
    </section>

    <!-- Main Catalog Section (Clean Minimalist White) -->
    <section id="catalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 scroll-mt-20">
        
        <!-- Catalog Header & Controls -->
        <div class="space-y-6 mb-12">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-zinc-200 pb-5">
                <div>
                    <h2 class="font-heading font-black text-2xl sm:text-3xl text-black tracking-tight uppercase">CATALOG</h2>
                </div>
                <div class="flex items-center space-x-3 text-xs font-mono text-zinc-500">
                    <span id="productCounterText">Showing <?php echo count($products); ?> pieces</span>
                </div>
            </div>

            <!-- Category Pills & Sort Bar -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                
                <!-- Category Tabs (Minimalist) -->
                <div class="flex items-center space-x-1.5 overflow-x-auto pb-1 md:pb-0 no-scrollbar" id="categoryFilterContainer">
                    <?php foreach ($categories as $index => $cat): ?>
                        <button 
                            data-category="<?php echo htmlspecialchars($cat['id']); ?>" 
                            class="category-filter-btn px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded transition-all whitespace-nowrap <?php echo $index === 0 ? 'bg-black text-white' : 'text-zinc-600 hover:text-black hover:bg-zinc-100'; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- Sort Filter -->
                <div class="flex items-center space-x-2.5 self-end md:self-auto text-xs">
                    <span class="text-zinc-500 uppercase font-semibold">Sort:</span>
                    <select id="catalogSortSelect" class="bg-white border border-zinc-200 text-xs font-semibold text-zinc-800 px-3 py-1.5 rounded focus:outline-none focus:border-black">
                        <option value="featured">Featured Drops</option>
                        <option value="price-asc">Price: Low to High</option>
                        <option value="price-desc">Price: High to Low</option>
                        <option value="rating">Top Rated</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Product Grid (Clean Minimalist Cards) -->
        <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
            <?php foreach ($products as $prod): ?>
                <div 
                    class="product-card group flex flex-col bg-white overflow-hidden"
                    data-id="<?php echo $prod['id']; ?>"
                    data-category="<?php echo $prod['category']; ?>"
                    data-price="<?php echo $prod['price']; ?>"
                    data-rating="<?php echo $prod['rating']; ?>"
                    data-name="<?php echo htmlspecialchars(strtolower($prod['name'])); ?>">
                    
                    <!-- Media Container with Smooth Hover Swap -->
                    <div class="relative aspect-[3/4] w-full bg-zinc-100 overflow-hidden cursor-pointer quickview-trigger" data-product-id="<?php echo $prod['id']; ?>">
                        <!-- Primary Image -->
                        <img 
                            src="<?php echo htmlspecialchars($prod['image']); ?>" 
                            alt="<?php echo htmlspecialchars($prod['name']); ?>" 
                            class="w-full h-full object-cover object-center group-hover:opacity-0 transition-opacity duration-300 ease-out">
                        
                        <!-- Hover Alternate Image -->
                        <img 
                            src="<?php echo htmlspecialchars($prod['hover_image']); ?>" 
                            alt="<?php echo htmlspecialchars($prod['name']); ?> Model View" 
                            class="absolute inset-0 w-full h-full object-cover object-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-out">

                        <!-- Minimalist Quick Spec Pill Button on Hover -->
                        <div class="absolute inset-x-4 bottom-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex justify-center">
                            <button 
                                class="w-full py-2.5 bg-black/90 hover:bg-black text-white text-xs font-bold uppercase tracking-wider rounded shadow quickview-btn"
                                data-product-id="<?php echo $prod['id']; ?>">
                                Quick View
                            </button>
                        </div>
                    </div>

                    <!-- Product Meta Info -->
                    <div class="pt-3.5 flex flex-col space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-[11px] font-mono text-zinc-400 uppercase tracking-wider"><?php echo htmlspecialchars($prod['category_name']); ?></span>
                        </div>

                        <h3 class="font-bold text-sm text-zinc-900 group-hover:text-zinc-600 transition-colors uppercase tracking-tight">
                            <?php echo htmlspecialchars($prod['name']); ?>
                        </h3>

                        <div class="pt-0.5 flex items-center space-x-2">
                            <span class="font-mono font-bold text-black text-sm">
                                <?php echo format_rupiah($prod['price']); ?>
                            </span>
                            <?php if ($prod['original_price'] > $prod['price']): ?>
                                <span class="text-xs font-mono text-zinc-400 line-through">
                                    <?php echo format_rupiah($prod['original_price']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- No Results Fallback -->
        <div id="noProductsFound" class="hidden text-center py-20 border border-dashed border-zinc-200 rounded mt-8 space-y-3">
            <p class="text-sm text-zinc-500">Tidak ada produk yang cocok dengan pencarian.</p>
            <button id="resetFiltersBtn" class="text-xs uppercase font-bold tracking-wider text-black underline underline-offset-4">Reset Filter</button>
        </div>
    </section>

    <!-- Lookbook & Community Editorial Section -->
    <section id="lookbook" class="border-t border-zinc-200 bg-zinc-50/70 py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mb-12">
                <span class="text-xs font-mono text-zinc-500 uppercase tracking-widest">COMMUNITY // ARCHIVES</span>
                <h2 class="font-heading font-black text-3xl sm:text-4xl text-black tracking-tight uppercase mt-1">
                    SEASON 2026 VISUAL ARCHIVES
                </h2>
                <p class="text-xs text-zinc-500 mt-2 leading-relaxed">
                    Setiap rilisan dirancang dengan potongan arsitektural dan material berdensitas tinggi, meminimalisir distraksi untuk mengedepankan esensi struktur pakaian murni.
                </p>
            </div>

            <!-- Lookbook Photo Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="relative aspect-[4/5] bg-zinc-200 overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=1000&q=80" alt="MORTYX Study 01" class="w-full h-full object-cover filter contrast-105 group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end p-5">
                        <span class="font-mono text-xs text-white uppercase tracking-wider">ARCHIVE 01. BOXY RATIO</span>
                    </div>
                </div>

                <div class="relative aspect-[4/5] bg-zinc-200 overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=1000&q=80" alt="MORTYX Study 02" class="w-full h-full object-cover filter contrast-105 group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end p-5">
                        <span class="font-mono text-xs text-white uppercase tracking-wider">ARCHIVE 02. DOUBLE LAYER HOOD</span>
                    </div>
                </div>

                <div class="relative aspect-[4/5] bg-zinc-200 overflow-hidden group sm:col-span-2 lg:col-span-1">
                    <img src="https://images.unsplash.com/photo-1548883354-7622d03aca27?auto=format&fit=crop&w=1000&q=80" alt="MORTYX Study 03" class="w-full h-full object-cover filter contrast-105 group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent flex items-end p-5">
                        <span class="font-mono text-xs text-white uppercase tracking-wider">ARCHIVE 03. WEATHER TACTICAL</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick View / Add To Bag Modal (Clean Minimalist White) -->
    <div id="quickViewBackdrop" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-200">
        <div id="quickViewDialog" class="bg-white border border-zinc-200 max-w-3xl w-full rounded overflow-hidden shadow-2xl transform scale-95 transition-all duration-200">
            <div class="relative p-6 sm:p-8">
                <!-- Close Button -->
                <button id="closeQuickViewBtn" aria-label="Close dialog" class="absolute top-4 right-4 p-2 text-zinc-400 hover:text-black transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <!-- Modal Content Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center" id="quickViewContent">
                    <!-- Dynamic rendering in JS -->
                </div>
            </div>
        </div>
    </div>

</main>

<!-- Injeksi dataset produk ke JavaScript Client -->
<script>
    window.MORTYX_PRODUCTS = <?php echo json_encode($products); ?>;
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
