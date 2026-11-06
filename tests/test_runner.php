<?php
/**
 * MORTYX Apparel — Automated Test Suite
 * 
 * Unit & Integration test suite to verify code integrity,
 * dataset consistency, and helper logic before PR merge.
 */

$failures = 0;
$testsRun = 0;

function assert_test($description, $condition) {
    global $failures, $testsRun;
    $testsRun++;
    if ($condition) {
        echo "  [PASS] {$description}\n";
    } else {
        echo "  [FAIL] {$description}\n";
        $failures++;
    }
}

echo "=============================================\n";
echo "   MORTYX APPAREL — AUTOMATED TEST RUNNER    \n";
echo "=============================================\n\n";

// 1. Test Dataset & Helpers
require_once __DIR__ . '/../data/products.php';

echo "1. Testing Helper Functions:\n";
assert_test("format_rupiah formats 0 correctly", format_rupiah(0) === 'Rp 0');
assert_test("format_rupiah formats 285000 correctly", format_rupiah(285000) === 'Rp 285.000');
assert_test("format_rupiah formats 1500000 correctly", format_rupiah(1500000) === 'Rp 1.500.000');

echo "\n2. Testing Categories:\n";
$categories = get_categories();
assert_test("get_categories returns array", is_array($categories));
assert_test("get_categories has at least 5 categories", count($categories) >= 5);
assert_test("First category is 'all'", isset($categories[0]['id']) && $categories[0]['id'] === 'all');

echo "\n3. Testing Product Catalog Dataset Integrity:\n";
$products = get_products();
assert_test("get_products returns array", is_array($products));
assert_test("get_products has products populated", count($products) >= 6);

$requiredFields = ['id', 'name', 'slug', 'category', 'category_name', 'price', 'sizes', 'color', 'image', 'hover_image', 'stock'];
$allValid = true;
$uniqueIds = [];

foreach ($products as $index => $prod) {
    foreach ($requiredFields as $field) {
        if (!isset($prod[$field])) {
            $allValid = false;
            echo "     Missing field '{$field}' in product at index {$index}\n";
        }
    }
    if (isset($prod['id'])) {
        if (in_array($prod['id'], $uniqueIds)) {
            $allValid = false;
            echo "     Duplicate product ID: {$prod['id']}\n";
        }
        $uniqueIds[] = $prod['id'];
    }
    if (isset($prod['price']) && $prod['price'] <= 0) {
        $allValid = false;
        echo "     Invalid price for product ID {$prod['id']}\n";
    }
    if (isset($prod['sizes']) && (!is_array($prod['sizes']) || empty($prod['sizes']))) {
        $allValid = false;
        echo "     Empty sizes array for product ID {$prod['id']}\n";
    }
}
assert_test("All products have complete required schema & positive price", $allValid);
assert_test("All product IDs are unique", count($uniqueIds) === count($products));

echo "\n4. Testing Template Includes & Assets Existence:\n";
assert_test("includes/header.php exists", file_exists(__DIR__ . '/../includes/header.php'));
assert_test("includes/footer.php exists", file_exists(__DIR__ . '/../includes/footer.php'));
assert_test("includes/cart_drawer.php exists", file_exists(__DIR__ . '/../includes/cart_drawer.php'));
assert_test("assets/js/catalog.js exists", file_exists(__DIR__ . '/../assets/js/catalog.js'));
assert_test("index.php entrypoint exists", file_exists(__DIR__ . '/../index.php'));

echo "\n=============================================\n";
if ($failures === 0) {
    echo "  RESULT: ALL {$testsRun} TESTS PASSED! [STATUS: GREEN]\n";
    echo "=============================================\n";
    exit(0);
} else {
    echo "  RESULT: {$failures} TEST(S) FAILED OUT OF {$testsRun}! [STATUS: RED]\n";
    echo "=============================================\n";
    exit(1);
}

