<?php
require_once __DIR__ . '/data/products.php';

$page_title = 'Catalog & Collections';
$categories = get_categories();
$products = get_products();

require_once __DIR__ . '/includes/header.php';
?>

<main class="flex-1 bg-white">

    <!-- Main Catalog Section (Clean Minimalist White) -->
    <section id="catalog" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 scroll-mt-20">
        
        <!-- Catalog Header & Controls -->
        <div class="space-y-6 mb-12">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-zinc-200 pb-5">
                <div>
                    <h1 class="font-heading font-black text-2xl sm:text-3xl text-black tracking-tight uppercase">CATALOG</h1>
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

                        <!-- Minimalist Quick Spec Button on Hover -->
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
