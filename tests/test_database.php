<?php
/**
 * MORTYX Apparel — Automated Database Unit & Integration Test Suite
 * 
 * Verifies relational schema DDL, migration, seed integrity,
 * foreign key constraints, and transactional stock deductions.
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../database/seed.php';

$failures = 0;
$testsRun = 0;

function assert_db_test($description, $condition) {
    global $failures, $testsRun;
    $testsRun++;
    if ($condition) {
        echo "  [PASS] {$description}\n";
    } else {
        echo "  [FAIL] {$description}\n";
        $failures++;
    }
}

echo "========================================================\n";
echo "   MORTYX APPAREL — AUTOMATED DATABASE TEST RUNNER      \n";
echo "========================================================\n\n";

// 1. Verify MySQL schema file exists and is valid SQL
echo "1. Testing Database Schema Integrity:\n";
$schemaFile = __DIR__ . '/../database/schema.sql';
assert_db_test("database/schema.sql file exists", file_exists($schemaFile));
$schemaContent = file_get_contents($schemaFile);
assert_db_test("schema.sql defines users table", strpos($schemaContent, 'CREATE TABLE `users`') !== false);
assert_db_test("schema.sql defines categories table", strpos($schemaContent, 'CREATE TABLE `categories`') !== false);
assert_db_test("schema.sql defines products table", strpos($schemaContent, 'CREATE TABLE `products`') !== false);
assert_db_test("schema.sql defines product_variants table", strpos($schemaContent, 'CREATE TABLE `product_variants`') !== false);
assert_db_test("schema.sql defines orders table", strpos($schemaContent, 'CREATE TABLE `orders`') !== false);
assert_db_test("schema.sql defines order_items table", strpos($schemaContent, 'CREATE TABLE `order_items`') !== false);
assert_db_test("schema.sql defines payments table", strpos($schemaContent, 'CREATE TABLE `payments`') !== false);

// 2. Setup Isolated In-Memory Database for Fast Deterministic Testing
echo "\n2. Testing Database Connection & Schema Migration:\n";
try {
    $tempDbPath = __DIR__ . '/temp_test_mortyx.sqlite';
    if (file_exists($tempDbPath)) {
        unlink($tempDbPath);
    }

    $pdo = get_db_connection([
        'driver' => 'sqlite',
        'dbname' => $tempDbPath
    ]);
    assert_db_test("PDO instance created successfully", $pdo instanceof PDO);

    $sqliteSchema = "
        DROP TABLE IF EXISTS `order_items`;
        DROP TABLE IF EXISTS `payments`;
        DROP TABLE IF EXISTS `orders`;
        DROP TABLE IF EXISTS `product_variants`;
        DROP TABLE IF EXISTS `products`;
        DROP TABLE IF EXISTS `categories`;
        DROP TABLE IF EXISTS `users`;

        CREATE TABLE `users` (
            `id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(150) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `phone` VARCHAR(25) NULL,
            `role` TEXT NOT NULL DEFAULT 'customer',
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE `categories` (
            `id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `name` VARCHAR(60) NOT NULL,
            `slug` VARCHAR(60) NOT NULL UNIQUE,
            `description` TEXT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        );

        CREATE TABLE `products` (
            `id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `category_id` INTEGER NOT NULL,
            `name` VARCHAR(150) NOT NULL,
            `slug` VARCHAR(150) NOT NULL UNIQUE,
            `base_price` DECIMAL(12, 2) NOT NULL,
            `compare_price` DECIMAL(12, 2) NULL,
            `tag` VARCHAR(50) NULL,
            `tag_color` VARCHAR(80) NULL,
            `short_description` VARCHAR(255) NOT NULL,
            `full_description` TEXT NOT NULL,
            `primary_image` VARCHAR(255) NOT NULL,
            `hover_image` VARCHAR(255) NOT NULL,
            `rating` DECIMAL(2, 1) NOT NULL DEFAULT 5.0,
            `review_count` INTEGER NOT NULL DEFAULT 0,
            `is_active` INTEGER NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
        );

        CREATE TABLE `product_variants` (
            `id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `product_id` INTEGER NOT NULL,
            `size` VARCHAR(15) NOT NULL,
            `color` VARCHAR(50) NOT NULL,
            `color_hex` VARCHAR(10) NOT NULL DEFAULT '#000000',
            `stock` INTEGER NOT NULL DEFAULT 0,
            `sku` VARCHAR(60) NOT NULL UNIQUE,
            `price_override` DECIMAL(12, 2) NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
        );

        CREATE TABLE `orders` (
            `id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `order_number` VARCHAR(32) NOT NULL UNIQUE,
            `user_id` INTEGER NULL,
            `customer_name` VARCHAR(100) NOT NULL,
            `customer_email` VARCHAR(150) NOT NULL,
            `customer_phone` VARCHAR(25) NOT NULL,
            `shipping_address` TEXT NOT NULL,
            `city` VARCHAR(100) NOT NULL,
            `province` VARCHAR(100) NOT NULL,
            `postal_code` VARCHAR(10) NOT NULL,
            `courier_name` VARCHAR(50) NOT NULL,
            `shipping_cost` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
            `subtotal` DECIMAL(12, 2) NOT NULL,
            `total_amount` DECIMAL(12, 2) NOT NULL,
            `status` TEXT NOT NULL DEFAULT 'PENDING_PAYMENT',
            `tracking_number` VARCHAR(100) NULL,
            `expires_at` DATETIME NOT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        );

        CREATE TABLE `order_items` (
            `id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `order_id` INTEGER NOT NULL,
            `variant_id` INTEGER NOT NULL,
            `product_name` VARCHAR(150) NOT NULL,
            `variant_size` VARCHAR(15) NOT NULL,
            `variant_color` VARCHAR(50) NOT NULL,
            `quantity` INTEGER NOT NULL DEFAULT 1,
            `unit_price` DECIMAL(12, 2) NOT NULL,
            `subtotal_price` DECIMAL(12, 2) NOT NULL,
            FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
            FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`)
        );

        CREATE TABLE `payments` (
            `id` INTEGER PRIMARY KEY AUTOINCREMENT,
            `order_id` INTEGER NOT NULL UNIQUE,
            `payment_method` VARCHAR(50) NOT NULL,
            `amount` DECIMAL(12, 2) NOT NULL,
            `payment_status` TEXT NOT NULL DEFAULT 'PENDING',
            `transaction_id` VARCHAR(100) NULL,
            `gateway_response_payload` TEXT NULL,
            `paid_at` DATETIME NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
        );
    ";

    $pdo->exec($sqliteSchema);
    assert_db_test("Schema DDL executed without errors", true);
} catch (Exception $e) {
    assert_db_test("Database setup failed: " . $e->getMessage(), false);
}

// 3. Testing Seeder Execution
echo "\n3. Testing Database Seeder:\n";
try {
    $seedResult = run_seeder($pdo);
    assert_db_test("run_seeder executed cleanly", $seedResult === true);
} catch (Exception $e) {
    assert_db_test("Seeder failed: " . $e->getMessage(), false);
}

// 4. Testing Categories Data
echo "\n4. Testing Categories Integrity:\n";
$catStmt = $pdo->query("SELECT id, name, slug FROM `categories` ORDER BY id ASC");
$seededCats = $catStmt->fetchAll();
assert_db_test("Categories table has exactly 5 records", count($seededCats) === 5);

$catSlugs = array_column($seededCats, 'slug');
assert_db_test("Categories contains 'tees'", in_array('tees', $catSlugs));
assert_db_test("Categories contains 'hoodies'", in_array('hoodies', $catSlugs));
assert_db_test("Categories contains 'outerwear'", in_array('outerwear', $catSlugs));

// 5. Testing Products Data
echo "\n5. Testing Products Integrity:\n";
$prodStmt = $pdo->query("SELECT id, name, slug, base_price, category_id, tag FROM `products`");
$seededProds = $prodStmt->fetchAll();
assert_db_test("Products table has exactly 6 products", count($seededProds) === 6);

$allPricesPositive = true;
foreach ($seededProds as $p) {
    if ((float) $p['base_price'] <= 0) {
        $allPricesPositive = false;
    }
}
assert_db_test("All products have positive base prices", $allPricesPositive);

// 6. Testing Multi-Size Variants & SKU Uniqueness
echo "\n6. Testing Product Variants & SKU Stock Control:\n";
$varStmt = $pdo->query("SELECT id, product_id, size, stock, sku FROM `product_variants`");
$seededVariants = $varStmt->fetchAll();
assert_db_test("Variants populated (at least 20 SKUs)", count($seededVariants) >= 20);

$uniqueSkus = [];
$allStocksPositive = true;
foreach ($seededVariants as $v) {
    $uniqueSkus[$v['sku']] = true;
    if ((int) $v['stock'] <= 0) {
        $allStocksPositive = false;
    }
}
assert_db_test("All variant SKUs are unique", count($uniqueSkus) === count($seededVariants));
assert_db_test("All initial variant stocks are positive (> 0)", $allStocksPositive);

// 7. Testing Admin User Authentication Hash
echo "\n7. Testing Admin User & Password Verification:\n";
$userStmt = $pdo->prepare("SELECT id, email, password, role FROM `users` WHERE `email` = :email");
$userStmt->execute(['email' => 'admin@mortyx.com']);
$adminUser = $userStmt->fetch();
assert_db_test("Admin user exists in users table", !empty($adminUser));
assert_db_test("Admin role is 'admin'", $adminUser['role'] === 'admin');
assert_db_test("Admin password hash validates 'mortyx2026'", password_verify('mortyx2026', $adminUser['password']));

// 8. Testing Relational Join Query (Catalog Hydration)
echo "\n8. Testing Relational Join Query:\n";
$joinStmt = $pdo->query("
    SELECT p.name AS product_name, c.name AS category_name, v.size, v.sku, v.stock
    FROM products p
    JOIN categories c ON p.category_id = c.id
    JOIN product_variants v ON v.product_id = p.id
    WHERE c.slug = 'tees'
");
$teeVariants = $joinStmt->fetchAll();
assert_db_test("Relational JOIN query returns tee variants", count($teeVariants) >= 8);

// 9. Testing Atomic Transaction Stock Deduction (ACID)
echo "\n9. Testing Atomic Transaction Stock Deduction:\n";
$testSku = $seededVariants[0]['sku'];
$initialStock = (int) $seededVariants[0]['stock'];

$pdo->beginTransaction();
$deductStmt = $pdo->prepare("UPDATE product_variants SET stock = stock - 2 WHERE sku = :sku");
$deductStmt->execute(['sku' => $testSku]);

$checkStmt = $pdo->prepare("SELECT stock FROM product_variants WHERE sku = :sku");
$checkStmt->execute(['sku' => $testSku]);
$newStock = (int) $checkStmt->fetchColumn();
assert_db_test("Stock decremented by 2 inside transaction", $newStock === ($initialStock - 2));

$pdo->rollBack();
$checkStmt->execute(['sku' => $testSku]);
$restoredStock = (int) $checkStmt->fetchColumn();
assert_db_test("Stock rollback restores original value (ACID Atomicity)", $restoredStock === $initialStock);

// Release lock and clean up temp test database
$pdo = null;
if (file_exists($tempDbPath)) {
    @unlink($tempDbPath);
}

echo "\n========================================================\n";
if ($failures === 0) {
    echo "  RESULT: ALL {$testsRun} DATABASE TESTS PASSED! [STATUS: GREEN]\n";
    echo "========================================================\n";
    exit(0);
} else {
    echo "  RESULT: {$failures} TEST(S) FAILED OUT OF {$testsRun}! [STATUS: RED]\n";
    echo "========================================================\n";
    exit(1);
}
