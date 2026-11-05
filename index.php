<?php
require_once __DIR__ . '/data/products.php';

$page_title = 'Drop 04: Distorted Reality';
$categories = get_categories();
$products = get_products();

require_once __DIR__ . '/includes/header.php';
?>

<main class="flex-1">

    <!-- Hero Section: Modern Brutalist Streetwear -->
    <section class="relative min-h-[82vh] flex items-center justify-center overflow-hidden border-b border-zinc-800">
        <!-- Background Imagery & Grain Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1509967419530-da38b4704bc6?auto=format&fit=crop&w=2000&q=80" alt="MORTYX Drop 04 Campaign" class="w-full h-full object-cover object-center opacity-30 scale-105 transform hover:scale-100 transition-transform duration-1000 ease-out filter grayscale">
            <div class="absolute inset-0 bg-gradient-to-t from-[#080809] via-[#080809]/60 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[#080809] via-transparent to-[#080809]/80"></div>
        </div>

        <!-- Hero Content Box -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center lg:text-left flex flex-col lg:flex-row items-center justify-between gap-12">
            <div class="max-w-2xl space-y-6">
                <div class="inline-flex items-center space-x-2 border border-zinc-700/80 bg-zinc-900/80 backdrop-blur-md px-3.5 py-1.5 rounded-full text-xs font-mono tracking-widest text-zinc-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>SEASON 2026 // DROP 04 LIVE</span>
                </div>

                <h1 class="font-heading font-black text-4xl sm:text-6xl lg:text-7xl tracking-tighter text-white leading-none uppercase">
                    ENGINEERED <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-zinc-100 via-zinc-400 to-zinc-600">FOR THE DISSIDENT</span> <br>
                    SOUL.
                </h1>

                <p class="text-sm sm:text-base text-zinc-400 max-w-lg leading-relaxed font-normal">
                    Siluet streetwear berdensitas 280-400 GSM dengan potongan boxy arsitektural dan sentuhan grafis distorsi. Diciptakan tanpa kompromi.
                </p>

                <div class="pt-4 flex flex-col sm:flex-row items-center gap-4">
                    <a href="#catalog" class="w-full sm:w-auto px-8 py-4 bg-white text-black font-heading font-black text-xs uppercase tracking-widest hover:bg-zinc-200 transition-all duration-200 shadow-xl shadow-white/5 text-center">
                        EXPLORE THE DROP
                    </a>
                    <a href="#lookbook" class="w-full sm:w-auto px-8 py-4 border border-zinc-700 bg-zinc-900/60 backdrop-blur-md text-white font-heading font-black text-xs uppercase tracking-widest hover:bg-zinc-800 transition-colors text-center">
                        2026 LOOKBOOK
                    </a>
                </div>
            </div>

            <!-- Hero Floating Spec Card -->
            <div class="w-full lg:w-80 bg-zinc-950/80 border border-zinc-800 backdrop-blur-lg p-6 rounded-sm space-y-5 text-left hidden sm:block">
                <div class="flex items-center justify-between border-b border-zinc-800 pb-3">
                    <span class="text-[11px] font-mono text-zinc-400 uppercase tracking-widest">SPEC SHEET</span>
                    <span class="text-[10px] bg-zinc-800 px-2 py-0.5 rounded text-white font-mono">BATCH #04</span>
                </div>
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Fabric Density:</span>
                        <span class="text-zinc-200 font-medium">16s Heavyweight Cotton (280 GSM)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Collar Rib:</span>
                        <span class="text-zinc-200 font-medium">3.5cm Heavy Spandex Anti-Sag</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Print Tech:</span>
                        <span class="text-zinc-200 font-medium">High Density Plastisol Distort</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-zinc-500">Origin:</span>
                        <span class="text-zinc-200 font-medium">Crafted in Indonesia</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Catalog Section -->
    <section id="catalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 scroll-mt-24">
        
        <!-- Catalog Header & Filter Bar -->
        <div class="space-y-8 mb-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-zinc-800 pb-6">
                <div>
                    <span class="text-xs font-mono text-zinc-500 uppercase tracking-widest">PRODUCT ARCHIVE</span>
                    <h2 class="font-heading font-black text-3xl sm:text-4xl text-white tracking-tight mt-1 uppercase">CURRENT RELEASES</h2>
                </div>
                <div class="flex items-center space-x-3 text-xs font-mono text-zinc-400">
                    <span id="productCounterText">Showing <?php echo count($products); ?> pieces</span>
                </div>
            </div>

            <!-- Category Pills & Sorting Bar -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                
                <!-- Category Filter Pills -->
                <div class="flex items-center space-x-2 overflow-x-auto pb-2 lg:pb-0 no-scrollbar" id="categoryFilterContainer">
                    <?php foreach ($categories as $index => $cat): ?>
                        <button 
                            data-category="<?php echo htmlspecialchars($cat['id']); ?>" 
                            class="category-filter-btn px-4 py-2 text-xs font-semibold uppercase tracking-wider rounded-sm transition-all whitespace-nowrap <?php echo $index === 0 ? 'bg-white text-black font-bold' : 'bg-zinc-900 border border-zinc-800 text-zinc-400 hover:text-white hover:border-zinc-700'; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <!-- Sort Dropdown & Search Filter -->
                <div class="flex items-center space-x-3 self-end lg:self-auto">
                    <label for="catalogSortSelect" class="text-xs text-zinc-400 uppercase font-semibold hidden sm:inline">Sort By:</label>
                    <select id="catalogSortSelect" class="bg-zinc-900 border border-zinc-800 text-xs text-zinc-200 px-3 py-2 rounded-sm focus:outline-none focus:border-white">
                        <option value="featured">Featured Drops</option>
                        <option value="price-asc">Price: Low to High</option>
                        <option value="price-desc">Price: High to Low</option>
                        <option value="rating">Top Rated</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($products as $prod): ?>
                <div 
                    class="product-card group flex flex-col bg-zinc-950 border border-zinc-900 rounded-sm overflow-hidden hover:border-zinc-700 transition-all duration-300"
                    data-id="<?php echo $prod['id']; ?>"
                    data-category="<?php echo $prod['category']; ?>"
                    data-price="<?php echo $prod['price']; ?>"
                    data-rating="<?php echo $prod['rating']; ?>"
                    data-name="<?php echo htmlspecialchars(strtolower($prod['name'])); ?>">
                    
                    <!-- Product Media Container with Hover Swap -->
                    <div class="relative aspect-[3/4] w-full bg-zinc-900 overflow-hidden cursor-pointer quickview-trigger" data-product-id="<?php echo $prod['id']; ?>">
                        <!-- Primary Image -->
                        <img 
                            src="<?php echo htmlspecialchars($prod['image']); ?>" 
                            alt="<?php echo htmlspecialchars($prod['name']); ?>" 
                            class="w-full h-full object-cover object-center group-hover:scale-105 group-hover:opacity-0 transition-all duration-500 ease-out">
                        
                        <!-- Hover Alternate Image -->
                        <img 
                            src="<?php echo htmlspecialchars($prod['hover_image']); ?>" 
                            alt="<?php echo htmlspecialchars($prod['name']); ?> Model View" 
                            class="absolute inset-0 w-full h-full object-cover object-center opacity-0 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500 ease-out">

                        <!-- Badge Tag -->
                        <?php if (!empty($prod['tag'])): ?>
                            <div class="absolute top-3 left-3 border text-[10px] font-mono font-bold tracking-widest px-2.5 py-1 rounded-sm backdrop-blur-md uppercase <?php echo $prod['tag_color']; ?>">
                                <?php echo htmlspecialchars($prod['tag']); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Quick View Overlay Button -->
                        <div class="absolute inset-x-3 bottom-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <button 
                                class="w-full py-2.5 bg-black/80 backdrop-blur-md border border-zinc-700 text-white font-heading font-black text-xs tracking-wider uppercase hover:bg-white hover:text-black transition-colors rounded-sm flex items-center justify-center space-x-1.5 quickview-btn"
                                data-product-id="<?php echo $prod['id']; ?>">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span>Quick Spec</span>
                            </button>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <div class="flex items-center justify-between text-xs text-zinc-500 mb-1">
                                <span class="font-mono uppercase text-[11px]"><?php echo htmlspecialchars($prod['category_name']); ?></span>
                                <div class="flex items-center space-x-1 text-amber-400">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-zinc-400 font-mono text-[11px]"><?php echo $prod['rating']; ?> (<?php echo $prod['review_count']; ?>)</span>
                                </div>
                            </div>

                            <h3 class="font-bold text-sm text-zinc-100 group-hover:text-white transition-colors">
                                <?php echo htmlspecialchars($prod['name']); ?>
                            </h3>

                            <p class="text-xs text-zinc-400 mt-1 line-clamp-2">
                                <?php echo htmlspecialchars($prod['short_desc']); ?>
                            </p>
                        </div>

                        <!-- Price & Action Section -->
                        <div class="pt-2 border-t border-zinc-900 flex items-center justify-between">
                            <div>
                                <span class="font-mono font-bold text-white text-base">
                                    <?php echo format_rupiah($prod['price']); ?>
                                </span>
                                <?php if ($prod['original_price'] > $prod['price']): ?>
                                    <span class="text-xs font-mono text-zinc-500 line-through ml-1.5">
                                        <?php echo format_rupiah($prod['original_price']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Fast Add to Bag Trigger -->
                            <button 
                                class="p-2.5 bg-zinc-900 border border-zinc-800 text-zinc-300 hover:text-black hover:bg-white hover:border-white transition-colors rounded-sm quickview-btn" 
                                data-product-id="<?php echo $prod['id']; ?>"
                                title="Pilih Ukuran & Masukkan Keranjang">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- No Results Fallback -->
        <div id="noProductsFound" class="hidden text-center py-20 border border-dashed border-zinc-800 rounded-sm mt-8 space-y-3">
            <p class="text-sm text-zinc-400">Tidak ada produk yang cocok dengan filter atau kata kunci pencarian.</p>
            <button id="resetFiltersBtn" class="text-xs uppercase font-bold tracking-wider text-white underline underline-offset-4">Reset Semua Filter</button>
        </div>
    </section>

    <!-- Lookbook Editorial Feature Section -->
    <section id="lookbook" class="border-t border-zinc-800 bg-[#060608] py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mb-12">
                <span class="text-xs font-mono text-zinc-500 uppercase tracking-widest">EDITORIAL // VOL 04</span>
                <h2 class="font-heading font-black text-3xl sm:text-5xl text-white tracking-tight uppercase mt-2">
                    THE MONOCHROME MANIFESTO
                </h2>
                <p class="text-sm text-zinc-400 mt-4 leading-relaxed">
                    Setiap potong jahitan adalah penolakan terhadap fast-fashion biasa. Kami mereduksi warna menjadi esensi murni: tekstur kain katun berat, detail washed carbon, dan proporsi boxy yang berani.
                </p>
            </div>

            <!-- Lookbook Photo Collage -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="relative aspect-[4/5] bg-zinc-900 overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=1000&q=80" alt="MORTYX Silhouette Study" class="w-full h-full object-cover grayscale contrast-125 group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-6">
                        <span class="font-mono text-xs text-zinc-300 uppercase tracking-wider">FIG 01. BOXY RATIO 1.15</span>
                    </div>
                </div>

                <div class="relative aspect-[4/5] bg-zinc-900 overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=1000&q=80" alt="MORTYX French Terry" class="w-full h-full object-cover grayscale contrast-125 group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-6">
                        <span class="font-mono text-xs text-zinc-300 uppercase tracking-wider">FIG 02. DOUBLE LAYER HOOD</span>
                    </div>
                </div>

                <div class="relative aspect-[4/5] bg-zinc-900 overflow-hidden group sm:col-span-2 lg:col-span-1">
                    <img src="https://images.unsplash.com/photo-1548883354-7622d03aca27?auto=format&fit=crop&w=1000&q=80" alt="MORTYX Weather Tactical" class="w-full h-full object-cover grayscale contrast-125 group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex items-end p-6">
                        <span class="font-mono text-xs text-zinc-300 uppercase tracking-wider">FIG 03. NYLON CRINKLE SHELL</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick View / Add To Bag Modal -->
    <div id="quickViewBackdrop" class="fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
        <div id="quickViewDialog" class="bg-[#0e0e11] border border-zinc-800 max-w-3xl w-full rounded-sm overflow-hidden shadow-2xl transform scale-95 transition-all duration-300">
            <div class="relative p-6 sm:p-8">
                <!-- Close Button -->
                <button id="closeQuickViewBtn" aria-label="Tutup dialog" class="absolute top-4 right-4 p-2 text-zinc-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
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
