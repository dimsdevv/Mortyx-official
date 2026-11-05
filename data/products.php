<?php
/**
 * MORTYX Apparel — Product Catalog Dataset & Helper Functions
 * 
 * Data representatif produk katalog resmi brand MORTYX.
 */

if (!function_exists('format_rupiah')) {
    function format_rupiah($angka) {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('get_categories')) {
    function get_categories() {
        return [
            ['id' => 'all', 'name' => 'All Drops'],
            ['id' => 'tees', 'name' => 'Heavyweight Tees'],
            ['id' => 'hoodies', 'name' => 'Oversized Hoodies'],
            ['id' => 'outerwear', 'name' => 'Outerwear & Jackets'],
            ['id' => 'pants', 'name' => 'Utility Pants'],
            ['id' => 'accessories', 'name' => 'Accessories']
        ];
    }
}

if (!function_exists('get_products')) {
    function get_products() {
        return [
            [
                'id' => 1,
                'name' => 'MORTYX Obsidian Heavy Boxy Tee',
                'slug' => 'mortyx-obsidian-heavy-boxy-tee',
                'category' => 'tees',
                'category_name' => 'Heavyweight Tees',
                'price' => 285000,
                'original_price' => 350000,
                'tag' => 'LIMITED DROP',
                'tag_color' => 'bg-red-500/20 text-red-400 border-red-500/30',
                'rating' => 4.9,
                'review_count' => 84,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'color' => 'Washed Carbon Black',
                'color_hex' => '#18181b',
                'short_desc' => 'Kaos boxy cut 16s 280 GSM ultra-heavy cotton dengan wash vintage premium.',
                'description' => 'Dibuat khusus untuk streetwear enthusiast. Menggunakan kain katun 16s ultra-heavy 280 GSM berbobot solid dengan treatment vintage acid wash khusus, kerah rib tebal 3.5cm anti-melar, dan siluet drop-shoulder boxy modern.',
                'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80',
                'hover_image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=800&q=80',
                'stock' => 14
            ],
            [
                'id' => 2,
                'name' => 'Cyber-Distort Oversized Hoodie',
                'slug' => 'cyber-distort-oversized-hoodie',
                'category' => 'hoodies',
                'category_name' => 'Oversized Hoodies',
                'price' => 485000,
                'original_price' => 550000,
                'tag' => 'BEST SELLER',
                'tag_color' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                'rating' => 5.0,
                'review_count' => 129,
                'sizes' => ['M', 'L', 'XL', 'XXL'],
                'color' => 'Pitch Black',
                'color_hex' => '#09090b',
                'short_desc' => 'French Terry 400 GSM heavyweight dengan sablon plastisol high-density.',
                'description' => 'Hoodie berkarakter kuat dengan potongan relaxed oversized. Dilengkapi double-layered hood, saku kanguru tersembunyi, dan artwork tipografi khas MORTYX di bagian punggung dengan teknik high-density screen printing.',
                'image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=800&q=80',
                'hover_image' => 'https://images.unsplash.com/photo-1509967419530-da38b4704bc6?auto=format&fit=crop&w=800&q=80',
                'stock' => 8
            ],
            [
                'id' => 3,
                'name' => 'Tactical Modular Cargo Pants',
                'slug' => 'tactical-modular-cargo-pants',
                'category' => 'pants',
                'category_name' => 'Utility Pants',
                'price' => 425000,
                'original_price' => 495000,
                'tag' => 'NEW ARRIVAL',
                'tag_color' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                'rating' => 4.8,
                'review_count' => 42,
                'sizes' => ['28', '30', '32', '34', '36'],
                'color' => 'Matte Military Olive',
                'color_hex' => '#272d24',
                'short_desc' => 'Ripstop stretch water-repellent dengan 8 kantong ergonomis dan tali drawcord hem.',
                'description' => 'Celana cargo fungsional dengan durabilitas tinggi. Menggunakan material ripstop berteknologi water-repellent DWR, risleting YKK matte, serta strap pengatur siluet kaki di bagian pergelangan.',
                'image' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?auto=format&fit=crop&w=800&q=80',
                'hover_image' => 'https://images.unsplash.com/photo-1517445312882-bc9910d016b7?auto=format&fit=crop&w=800&q=80',
                'stock' => 19
            ],
            [
                'id' => 4,
                'name' => 'Neo-Vortex Puffer Bomber Jacket',
                'slug' => 'neo-vortex-puffer-bomber-jacket',
                'category' => 'outerwear',
                'category_name' => 'Outerwear & Jackets',
                'price' => 675000,
                'original_price' => 790000,
                'tag' => 'SIGNATURE',
                'tag_color' => 'bg-purple-500/20 text-purple-400 border-purple-500/30',
                'rating' => 4.9,
                'review_count' => 61,
                'sizes' => ['M', 'L', 'XL'],
                'color' => 'Reflective Charcoal',
                'color_hex' => '#262626',
                'short_desc' => 'Nylon crinkle weather-resistant dengan padding insulasi termal ringan.',
                'description' => 'Jacket ikonik musim ini dengan aksen garis reflektif 3M. Menawarkan kehangatan optimal tanpa terasa berat berkat padding microfiber synthetic down serta furing satin berlogo monogram MORTYX.',
                'image' => 'https://images.unsplash.com/photo-1548883354-7622d03aca27?auto=format&fit=crop&w=800&q=80',
                'hover_image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=800&q=80',
                'stock' => 5
            ],
            [
                'id' => 5,
                'name' => 'Raw Bleach Graphic Tee',
                'slug' => 'raw-bleach-graphic-tee',
                'category' => 'tees',
                'category_name' => 'Heavyweight Tees',
                'price' => 265000,
                'original_price' => 320000,
                'tag' => 'RESTOCK',
                'tag_color' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                'rating' => 4.7,
                'review_count' => 97,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'color' => 'Bleached Smoke Grey',
                'color_hex' => '#404040',
                'short_desc' => 'Heavy combed 20s dengan efek custom manual bleach discharge print.',
                'description' => 'Setiap potong kaos memiliki pola bleach yang unik dan tidak identik. Mengusung grafis brutalist typography MORTYX 2026 Season Release.',
                'image' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=800&q=80',
                'hover_image' => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?auto=format&fit=crop&w=800&q=80',
                'stock' => 22
            ],
            [
                'id' => 6,
                'name' => 'Industrial Crossbody Tactical Bag',
                'slug' => 'industrial-crossbody-tactical-bag',
                'category' => 'accessories',
                'category_name' => 'Accessories',
                'price' => 195000,
                'original_price' => 240000,
                'tag' => 'ESSENTIAL',
                'tag_color' => 'bg-zinc-700/50 text-zinc-300 border-zinc-600',
                'rating' => 4.9,
                'review_count' => 153,
                'sizes' => ['One Size'],
                'color' => 'Stealth Black',
                'color_hex' => '#141416',
                'short_desc' => 'Cordura 1000D water-resistant dengan buckle magnetic Fidlock style.',
                'description' => 'Tas selempang multifungsi dengan kompartemen empuk untuk gadget, saku rahasia di bagian belakang, dan tali anyaman nilon militer berkekuatan tinggi.',
                'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=80',
                'hover_image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=800&q=80',
                'stock' => 31
            ]
        ];
    }
}
