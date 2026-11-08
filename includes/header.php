<?php
if (!class_exists('Auth')) {
    require_once __DIR__ . '/Auth.php';
}
Auth::init();
$currentUser = Auth::user();
$isLoggedIn = Auth::check();
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' | MORTYX APPAREL' : 'MORTYX — Contemporary Streetwear & Modern Apparel'; ?></title>
    
    <!-- Google Fonts: Syne (Brand) & Plus Jakarta Sans (Clean Minimalist Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        canvas: '#ffffff',
                        surface: '#f9f9f9',
                        border: '#e5e7eb',
                        brand: {
                            black: '#000000',
                            charcoal: '#111827',
                            muted: '#6b7280'
                        }
                    },
                    fontFamily: {
                        heading: ['Syne', 'sans-serif'],
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            background-color: #ffffff;
            color: #111827;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }
        .font-heading {
            font-family: 'Syne', sans-serif;
            letter-spacing: -0.04em;
        }
        /* Minimalist scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #ffffff;
        }
        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 2px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
</head>
<body class="bg-white text-zinc-900 min-h-screen flex flex-col antialiased selection:bg-black selection:text-white">

    <!-- Main Navigation Bar (Clean White, CALLE Style) -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-zinc-200 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 py-4 flex items-center justify-between">
            
            <!-- Left: Brand Logo & Mobile Trigger -->
            <div class="flex items-center space-x-3">
                <button id="mobileMenuBtn" aria-label="Toggle Navigation" class="md:hidden p-1.5 text-zinc-800 hover:text-black focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <a href="index.php" class="flex items-center">
                    <span class="font-heading font-black text-2xl sm:text-3xl tracking-tighter text-black uppercase">
                        MORTYX
                    </span>
                </a>
            </div>

            <!-- Center: Navigation Links (Clean & Minimalist) -->
            <nav class="hidden md:flex items-center space-x-8 text-xs font-bold uppercase tracking-wider text-zinc-800">
                <a href="catalog.php" class="hover:text-zinc-500 transition-colors">Catalog</a>
                <a href="catalog.php" class="hover:text-zinc-500 transition-colors">Community</a>
                <a href="catalog.php" class="hover:text-zinc-500 transition-colors">Archives</a>
                <a href="#about" class="hover:text-zinc-500 transition-colors">About</a>
            </nav>

            <!-- Right: Currency, Search, Cart, Account -->
            <div class="flex items-center space-x-5 text-xs font-bold uppercase tracking-wider text-zinc-900">
                
                <!-- Currency Indicator (Indonesian Flag + IDR) -->
                <div class="hidden sm:flex items-center space-x-1.5 text-zinc-900 font-bold select-none">
                    <span class="inline-block w-4 h-2.5 rounded-[1px] overflow-hidden border border-zinc-300 relative">
                        <span class="block h-[5px] bg-[#e11d48]"></span>
                        <span class="block h-[5px] bg-white"></span>
                    </span>
                    <span class="text-xs">IDR</span>
                </div>

                <!-- Search Toggle -->
                <button id="searchToggleBtn" aria-label="Search" class="p-1 text-zinc-800 hover:text-black transition-colors" title="Search catalog">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                <!-- Cart Button with Count Indicator -->
                <button id="cartDrawerOpenBtn" aria-label="View Shopping Cart" class="hover:text-zinc-500 transition-colors flex items-center space-x-1">
                    <span>CART</span>
                    <span id="cartCountBadge" class="text-xs font-mono font-bold">(0)</span>
                </button>

                <!-- Account / Profile Icon -->
                <?php if ($isLoggedIn): ?>
                    <a href="customer/account.php" class="p-1 text-zinc-900 hover:text-zinc-500 transition-colors flex items-center space-x-1" title="Account (<?php echo htmlspecialchars($currentUser['name'] ?? 'MEMBER'); ?>)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </a>
                <?php else: ?>
                    <a href="auth/login.php" class="p-1 text-zinc-900 hover:text-zinc-500 transition-colors" title="Sign In">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Search Bar Dropdown (Clean White Slide Down) -->
        <div id="searchDropdown" class="hidden border-t border-zinc-200 bg-zinc-50 px-4 py-3 sm:px-6">
            <div class="max-w-3xl mx-auto flex items-center">
                <svg class="w-4 h-4 text-zinc-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="catalogSearchInput" placeholder="Cari t-shirt, hoodie, varsity jacket, cargo pants..." class="w-full bg-transparent text-sm text-zinc-900 placeholder-zinc-400 focus:outline-none">
                <button id="closeSearchBtn" class="text-xs uppercase font-bold tracking-wider text-zinc-500 hover:text-black ml-2">Close</button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobileMenuDropdown" class="hidden md:hidden border-t border-zinc-200 bg-white px-5 pt-3 pb-6 space-y-3 text-xs uppercase tracking-wider font-bold text-zinc-800">
            <a href="catalog.php" class="block py-2 border-b border-zinc-100 hover:text-black">Catalog</a>
            <a href="catalog.php" class="block py-2 border-b border-zinc-100 hover:text-black">Community</a>
            <a href="catalog.php" class="block py-2 border-b border-zinc-100 hover:text-black">Archives</a>
            <a href="#about" class="block py-2 border-b border-zinc-100 hover:text-black">About</a>
            <?php if ($isLoggedIn): ?>
                <a href="customer/account.php" class="block py-2 border-b border-zinc-100 text-black flex items-center justify-between">
                    <span>Account: <?php echo htmlspecialchars($currentUser['name'] ?? 'MEMBER'); ?></span>
                    <span class="text-zinc-400">→</span>
                </a>
                <a href="auth/logout.php" class="block py-2 border-b border-zinc-100 text-red-600">Sign Out</a>
            <?php else: ?>
                <a href="auth/login.php" class="block py-2 border-b border-zinc-100 hover:text-black">Sign In</a>
                <a href="auth/register.php" class="block py-2 border-b border-zinc-100 hover:text-black">Create Account</a>
            <?php endif; ?>
        </div>
    </header>
