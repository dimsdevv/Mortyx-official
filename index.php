<?php
$page_title = 'Official Store';

require_once __DIR__ . '/includes/header.php';
?>

<main class="flex-1 bg-white">

    <!-- Hero Section: CALLE-Style Editorial Full-Bleed Splash Screen -->
    <section class="relative h-[calc(100vh-4.5rem)] w-full overflow-hidden flex items-center justify-center bg-zinc-900">
        <!-- Background Editorial Image -->
        <img 
            src="https://images.unsplash.com/photo-1509967419530-da38b4704bc6?auto=format&fit=crop&w=2400&q=85" 
            alt="MORTYX Campaign" 
            class="w-full h-full object-cover object-center filter contrast-105">
        
        <!-- Subtle Overlay for Clean Depth -->
        <div class="absolute inset-0 bg-black/20"></div>

        <!-- Single Minimalist Hero CTA (CALLE Style) -->
        <div class="absolute inset-0 flex items-center justify-center z-10">
            <a href="catalog.php" class="px-8 py-3.5 bg-black/85 hover:bg-black text-white text-xs sm:text-sm font-bold tracking-widest uppercase rounded shadow-lg backdrop-blur-sm transition-all duration-200 transform hover:scale-105">
                SHOP HERE
            </a>
        </div>
    </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
