<?php
/**
 * MORTYX Apparel — Relational Database Seeder
 * 
 * Populates categories, products, multi-size SKU variants, and default admin user.
 */

require_once __DIR__ . '/../config/database.php';

if (!function_exists('run_seeder')) {
    function run_seeder(PDO $pdo) {
        $pdo->beginTransaction();

        try {
            // 1. Seed Categories
            $categories = [
                ['name' => 'Heavyweight Tees', 'slug' => 'tees', 'description' => 'Kaos siluet boxy 16s 280 GSM ultra-heavy cotton.'],
                ['name' => 'Oversized Hoodies', 'slug' => 'hoodies', 'description' => 'Hoodie French Terry 400 GSM berbobot solid dengan double-layer hood.'],
                ['name' => 'Outerwear & Jackets', 'slug' => 'outerwear', 'description' => 'Bomber and tactical jackets engineered with weather-resistant shells.'],
                ['name' => 'Utility Pants', 'slug' => 'pants', 'description' => 'Celana cargo fungsional dengan material ripstop stretch ergonomis.'],
                ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Tactical bags, headwear, and apparel essentials.']
            ];

            $catStmt = $pdo->prepare("INSERT INTO `categories` (`name`, `slug`, `description`) VALUES (:name, :slug, :description) ON DUPLICATE KEY UPDATE `name`=VALUES(`name`)");
            $catIdMap = [];

            foreach ($categories as $cat) {
                $catStmt->execute($cat);
                $fetchStmt = $pdo->prepare("SELECT id FROM `categories` WHERE `slug` = :slug LIMIT 1");
                $fetchStmt->execute(['slug' => $cat['slug']]);
                $catIdMap[$cat['slug']] = (int) $fetchStmt->fetchColumn();
            }

            // 2. Seed Admin User
            $adminEmail = 'admin@mortyx.com';
            $adminHash = password_hash('mortyx2026', PASSWORD_BCRYPT);
            $userStmt = $pdo->prepare("
                INSERT INTO `users` (`name`, `email`, `password`, `phone`, `role`)
                VALUES (:name, :email, :password, :phone, 'admin')
                ON DUPLICATE KEY UPDATE `password` = VALUES(`password`)
            ");
            $userStmt->execute([
                'name'     => 'MORTYX Head Officer',
                'email'    => $adminEmail,
                'password' => $adminHash,
                'phone'    => '+6281299990001'
            ]);

            // 3. Seed Products & SKU Variants
            $productsData = [
                [
                    'name' => 'MORTYX Obsidian Heavy Boxy Tee',
                    'slug' => 'mortyx-obsidian-heavy-boxy-tee',
                    'category_slug' => 'tees',
                    'base_price' => 285000,
                    'compare_price' => 350000,
                    'tag' => 'LIMITED DROP',
                    'tag_color' => 'bg-red-500/20 text-red-400 border-red-500/30',
                    'rating' => 4.9,
                    'review_count' => 84,
                    'color' => 'Washed Carbon Black',
                    'color_hex' => '#18181b',
                    'short_desc' => 'Kaos boxy cut 16s 280 GSM ultra-heavy cotton dengan wash vintage premium.',
                    'full_desc' => 'Dibuat khusus untuk streetwear enthusiast. Menggunakan kain katun 16s ultra-heavy 280 GSM berbobot solid dengan treatment vintage acid wash khusus, kerah rib tebal 3.5cm anti-melar, dan siluet drop-shoulder boxy modern.',
                    'image' => 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80',
                    'hover_image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=800&q=80',
                    'variants' => [
                        ['size' => 'S', 'sku' => 'MTX-TEE-01-S', 'stock' => 15],
                        ['size' => 'M', 'sku' => 'MTX-TEE-01-M', 'stock' => 20],
                        ['size' => 'L', 'sku' => 'MTX-TEE-01-L', 'stock' => 25],
                        ['size' => 'XL', 'sku' => 'MTX-TEE-01-XL', 'stock' => 10]
                    ]
                ],
                [
                    'name' => 'Cyber-Distort Oversized Hoodie',
                    'slug' => 'cyber-distort-oversized-hoodie',
                    'category_slug' => 'hoodies',
                    'base_price' => 485000,
                    'compare_price' => 550000,
                    'tag' => 'BEST SELLER',
                    'tag_color' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                    'rating' => 5.0,
                    'review_count' => 129,
                    'color' => 'Pitch Black',
                    'color_hex' => '#09090b',
                    'short_desc' => 'French Terry 400 GSM heavyweight dengan sablon plastisol high-density.',
                    'full_desc' => 'Hoodie berkarakter kuat dengan potongan relaxed oversized. Dilengkapi double-layered hood, saku kanguru tersembunyi, dan artwork tipografi khas MORTYX di bagian punggung dengan teknik high-density screen printing.',
                    'image' => 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?auto=format&fit=crop&w=800&q=80',
                    'hover_image' => 'https://images.unsplash.com/photo-1509967419530-da38b4704bc6?auto=format&fit=crop&w=800&q=80',
                    'variants' => [
                        ['size' => 'M', 'sku' => 'MTX-HOD-02-M', 'stock' => 12],
                        ['size' => 'L', 'sku' => 'MTX-HOD-02-L', 'stock' => 18],
                        ['size' => 'XL', 'sku' => 'MTX-HOD-02-XL', 'stock' => 10],
                        ['size' => 'XXL', 'sku' => 'MTX-HOD-02-XXL', 'stock' => 5]
                    ]
                ],
                [
                    'name' => 'Tactical Modular Cargo Pants',
                    'slug' => 'tactical-modular-cargo-pants',
                    'category_slug' => 'pants',
                    'base_price' => 425000,
                    'compare_price' => 495000,
                    'tag' => 'NEW ARRIVAL',
                    'tag_color' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                    'rating' => 4.8,
                    'review_count' => 42,
                    'color' => 'Matte Military Olive',
                    'color_hex' => '#272d24',
                    'short_desc' => 'Ripstop stretch water-repellent dengan 8 kantong ergonomis dan tali drawcord hem.',
                    'full_desc' => 'Celana cargo fungsional dengan durabilitas tinggi. Menggunakan material ripstop berteknologi water-repellent DWR, risleting YKK matte, serta strap pengatur siluet kaki di bagian pergelangan.',
                    'image' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?auto=format&fit=crop&w=800&q=80',
                    'hover_image' => 'https://images.unsplash.com/photo-1517445312882-bc9910d016b7?auto=format&fit=crop&w=800&q=80',
                    'variants' => [
                        ['size' => '28', 'sku' => 'MTX-PNT-03-28', 'stock' => 8],
                        ['size' => '30', 'sku' => 'MTX-PNT-03-30', 'stock' => 14],
                        ['size' => '32', 'sku' => 'MTX-PNT-03-32', 'stock' => 16],
                        ['size' => '34', 'sku' => 'MTX-PNT-03-34', 'stock' => 10],
                        ['size' => '36', 'sku' => 'MTX-PNT-03-36', 'stock' => 6]
                    ]
                ],
                [
                    'name' => 'Neo-Vortex Puffer Bomber Jacket',
                    'slug' => 'neo-vortex-puffer-bomber-jacket',
                    'category_slug' => 'outerwear',
                    'base_price' => 675000,
                    'compare_price' => 790000,
                    'tag' => 'SIGNATURE',
                    'tag_color' => 'bg-purple-500/20 text-purple-400 border-purple-500/30',
                    'rating' => 4.9,
                    'review_count' => 61,
                    'color' => 'Reflective Charcoal',
                    'color_hex' => '#262626',
                    'short_desc' => 'Nylon crinkle weather-resistant dengan padding insulasi termal ringan.',
                    'full_desc' => 'Jacket ikonik musim ini dengan aksen garis reflektif 3M. Menawarkan kehangatan optimal tanpa terasa berat berkat padding microfiber synthetic down serta furing satin berlogo monogram MORTYX.',
                    'image' => 'https://images.unsplash.com/photo-1548883354-7622d03aca27?auto=format&fit=crop&w=800&q=80',
                    'hover_image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=800&q=80',
                    'variants' => [
                        ['size' => 'M', 'sku' => 'MTX-OUT-04-M', 'stock' => 7],
                        ['size' => 'L', 'sku' => 'MTX-OUT-04-L', 'stock' => 10],
                        ['size' => 'XL', 'sku' => 'MTX-OUT-04-XL', 'stock' => 5]
                    ]
                ],
                [
                    'name' => 'Raw Bleach Graphic Tee',
                    'slug' => 'raw-bleach-graphic-tee',
                    'category_slug' => 'tees',
                    'base_price' => 265000,
                    'compare_price' => 320000,
                    'tag' => 'RESTOCK',
                    'tag_color' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                    'rating' => 4.7,
                    'review_count' => 97,
                    'color' => 'Bleached Smoke Grey',
                    'color_hex' => '#404040',
                    'short_desc' => 'Heavy combed 20s dengan efek custom manual bleach discharge print.',
                    'full_desc' => 'Setiap potong kaos memiliki pola bleach yang unik dan tidak identik. Mengusung grafis brutalist typography MORTYX 2026 Season Release.',
                    'image' => 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=800&q=80',
                    'hover_image' => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?auto=format&fit=crop&w=800&q=80',
                    'variants' => [
                        ['size' => 'S', 'sku' => 'MTX-TEE-05-S', 'stock' => 10],
                        ['size' => 'M', 'sku' => 'MTX-TEE-05-M', 'stock' => 15],
                        ['size' => 'L', 'sku' => 'MTX-TEE-05-L', 'stock' => 20],
                        ['size' => 'XL', 'sku' => 'MTX-TEE-05-XL', 'stock' => 8]
                    ]
                ],
                [
                    'name' => 'Industrial Crossbody Tactical Bag',
                    'slug' => 'industrial-crossbody-tactical-bag',
                    'category_slug' => 'accessories',
                    'base_price' => 195000,
                    'compare_price' => 240000,
                    'tag' => 'ESSENTIAL',
                    'tag_color' => 'bg-zinc-700/50 text-zinc-300 border-zinc-600',
                    'rating' => 4.9,
                    'review_count' => 153,
                    'color' => 'Stealth Black',
                    'color_hex' => '#141416',
                    'short_desc' => 'Cordura 1000D water-resistant dengan buckle magnetic Fidlock style.',
                    'full_desc' => 'Tas selempang multifungsi dengan kompartemen empuk untuk gadget, saku rahasia di bagian belakang, dan tali anyaman nilon militer berkekuatan tinggi.',
                    'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=80',
                    'hover_image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=800&q=80',
                    'variants' => [
                        ['size' => 'One Size', 'sku' => 'MTX-ACC-06-OS', 'stock' => 35]
                    ]
                ]
            ];

            $prodStmt = $pdo->prepare("
                INSERT INTO `products` 
                (`category_id`, `name`, `slug`, `base_price`, `compare_price`, `tag`, `tag_color`, `short_description`, `full_description`, `primary_image`, `hover_image`, `rating`, `review_count`, `is_active`)
                VALUES 
                (:category_id, :name, :slug, :base_price, :compare_price, :tag, :tag_color, :short_description, :full_description, :primary_image, :hover_image, :rating, :review_count, 1)
                ON DUPLICATE KEY UPDATE `base_price`=VALUES(`base_price`), `tag`=VALUES(`tag`)
            ");

            $varStmt = $pdo->prepare("
                INSERT INTO `product_variants`
                (`product_id`, `size`, `color`, `color_hex`, `stock`, `sku`)
                VALUES
                (:product_id, :size, :color, :color_hex, :stock, :sku)
                ON DUPLICATE KEY UPDATE `stock`=VALUES(`stock`)
            ");

            foreach ($productsData as $item) {
                $catId = $catIdMap[$item['category_slug']];
                $prodStmt->execute([
                    'category_id'       => $catId,
                    'name'              => $item['name'],
                    'slug'              => $item['slug'],
                    'base_price'        => $item['base_price'],
                    'compare_price'     => $item['compare_price'],
                    'tag'               => $item['tag'],
                    'tag_color'         => $item['tag_color'],
                    'short_description' => $item['short_desc'],
                    'full_description'  => $item['full_desc'],
                    'primary_image'     => $item['image'],
                    'hover_image'       => $item['hover_image'],
                    'rating'            => $item['rating'],
                    'review_count'      => $item['review_count']
                ]);

                // Ambil ID produk
                $getIdStmt = $pdo->prepare("SELECT id FROM `products` WHERE `slug` = :slug LIMIT 1");
                $getIdStmt->execute(['slug' => $item['slug']]);
                $productId = (int) $getIdStmt->fetchColumn();

                // Masukkan varian ukuran
                foreach ($item['variants'] as $v) {
                    $varStmt->execute([
                        'product_id' => $productId,
                        'size'       => $v['size'],
                        'color'      => $item['color'],
                        'color_hex'  => $item['color_hex'],
                        'stock'      => $v['stock'],
                        'sku'        => $v['sku']
                    ]);
                }
            }

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}

// Eksekusi jika dipanggil via CLI
if (php_sapi_name() === 'cli' && basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    echo "=============================================\n";
    echo "       MORTYX DATABASE SEEDER RUNNER         \n";
    echo "=============================================\n";

    try {
        echo "1. Checking MySQL connection...\n";
        ensure_database_exists();
        $pdo = get_db_connection();
        echo "   [SUCCESS] Connected to database.\n\n";

        echo "2. Applying database schema...\n";
        run_schema_migration($pdo);
        echo "   [SUCCESS] Schema applied successfully.\n\n";

        echo "3. Seeding categories, products, variants, and admin...\n";
        run_seeder($pdo);
        echo "   [SUCCESS] All seed records inserted successfully.\n";
        echo "=============================================\n";
        echo "   DATABASE SEED COMPLETED SUCCESSFULLY!     \n";
        echo "=============================================\n";
    } catch (Exception $e) {
        echo "   [ERROR] " . $e->getMessage() . "\n";
        echo "=============================================\n";
        exit(1);
    }
}
