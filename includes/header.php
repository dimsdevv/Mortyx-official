<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' | MORTYX APPAREL' : 'MORTYX — Official Streetwear & Contemporary Apparel'; ?></title>
    
    <!-- Google Fonts: Syne & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Syne:wght@700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            950: '#080809',
                            900: '#0e0e11',
                            800: '#16161b',
                            700: '#22222a',
                            600: '#32323c'
                        },
                        brand: {
                            accent: '#ffffff',
                            muted: '#94a3b8',
                            neon: '#e2fd52'
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
            background-color: #080809;
            color: #f1f5f9;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }
        .font-heading {
            font-family: 'Syne', sans-serif;
            letter-spacing: -0.04em;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #0e0e11;
        }
        ::-webkit-scrollbar-thumb {
            background: #27272a;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #3f3f46;
        }
    </style>
</head>
<body class="bg-[#080809] text-zinc-100 min-h-screen flex flex-col antialiased selection:bg-zinc-100 selection:text-black">

    <!-- Top Announcement Bar -->
    <div class="bg-zinc-950 border-b border-zinc-800/60 text-xs py-2 px-4 text-center tracking-widest uppercase font-semibold text-zinc-400">
        <span class="inline-block animate-pulse text-emerald-400 mr-2">●</span>
        <span>FREE SHIPPING JABODETABEK MIN. IDR 500K</span>
        <span class="mx-3 text-zinc-700">|</span>
        <span class="text-zinc-200">DROP 04: DISTORTED REALITY IS LIVE</span>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-40 bg-[#080809]/90 backdrop-blur-md border-b border-zinc-800/80 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Mobile Menu Trigger -->
            <button id="mobileMenuBtn" aria-label="Toggle Navigation" class="md:hidden p-2 text-zinc-400 hover:text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                </svg>
            </button>

            <!-- Brand Logo -->
            <div class="flex items-center space-x-3">
                <a href="index.php" class="flex items-center space-x-2 group">
                    <span class="font-heading font-black text-2xl sm:text-3xl tracking-tighter text-white group-hover:tracking-normal transition-all duration-300">
                        MORTYX<span class="text-zinc-500 text-lg">®</span>
                    </span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-8 text-xs font-semibold uppercase tracking-wider text-zinc-400">
                <a href="#catalog" class="hover:text-white transition-colors duration-200">Catalog</a>
                <a href="#featured" class="hover:text-white transition-colors duration-200">Drop 04</a>
                <a href="#lookbook" class="hover:text-white transition-colors duration-200">Lookbook</a>
                <a href="#about" class="hover:text-white transition-colors duration-200">Manifesto</a>
            </nav>

            <!-- Action Controls (Search, Wishlist, Cart Drawer) -->
            <div class="flex items-center space-x-4">
                <!-- Search Toggle -->
                <button id="searchToggleBtn" aria-label="Search" class="p-2 text-zinc-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>

                <!-- Cart Button with Count Badge -->
                <button id="cartDrawerOpenBtn" aria-label="View Shopping Cart" class="relative p-2 text-zinc-300 hover:text-white flex items-center bg-zinc-900 border border-zinc-800 rounded-lg px-3 py-2 space-x-2 transition-colors hover:border-zinc-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span class="text-xs font-bold font-mono tracking-wider">CART</span>
                    <span id="cartCountBadge" class="bg-white text-black text-[10px] font-black rounded-full px-1.5 py-0.5 ml-1">0</span>
                </button>
            </div>
        </div>

        <!-- Search Bar Drawer (Slide Down) -->
        <div id="searchDropdown" class="hidden border-t border-zinc-800 bg-[#0e0e11] px-4 py-3 sm:px-6">
            <div class="max-w-3xl mx-auto flex items-center">
                <svg class="w-5 h-5 text-zinc-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="catalogSearchInput" placeholder="Cari hoodie, kaos heavyweight, cargo pants..." class="w-full bg-transparent text-sm text-white placeholder-zinc-500 focus:outline-none">
                <button id="closeSearchBtn" class="text-xs uppercase tracking-wider text-zinc-400 hover:text-white ml-2">Close</button>
            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobileMenuDropdown" class="hidden md:hidden border-t border-zinc-800 bg-zinc-950 px-4 pt-3 pb-6 space-y-3 text-sm uppercase tracking-wider font-semibold text-zinc-300">
            <a href="#catalog" class="block py-2 border-b border-zinc-900 hover:text-white">Catalog</a>
            <a href="#featured" class="block py-2 border-b border-zinc-900 hover:text-white">Drop 04</a>
            <a href="#lookbook" class="block py-2 border-b border-zinc-900 hover:text-white">Lookbook</a>
            <a href="#about" class="block py-2 border-b border-zinc-900 hover:text-white">Manifesto</a>
        </div>
    </header>

