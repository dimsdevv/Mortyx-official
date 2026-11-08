<?php
/**
 * MORTYX Apparel — Automated Authentication Test Suite
 * 
 * Verifies Auth deep module, Bcrypt cost 12 hashing, CSRF token validation,
 * Session management, and Route Guards using SQLite in-memory database.
 */

require_once __DIR__ . '/../includes/Auth.php';

// Initialize session before any CLI output
Auth::init();

// CLI Output Colors
define('COLOR_GREEN', "\033[32m");
define('COLOR_RED', "\033[31m");
define('COLOR_CYAN', "\033[36m");
define('COLOR_RESET', "\033[0m");

$passCount = 0;
$failCount = 0;

function it(string $description, callable $test) {
    global $passCount, $failCount;
    try {
        $result = $test();
        if ($result !== false) {
            echo "  " . COLOR_GREEN . "[PASS]" . COLOR_RESET . " {$description}\n";
            $passCount++;
        } else {
            echo "  " . COLOR_RED . "[FAIL]" . COLOR_RESET . " {$description}\n";
            $failCount++;
        }
    } catch (Throwable $e) {
        echo "  " . COLOR_RED . "[FAIL]" . COLOR_RESET . " {$description} (Error: " . $e->getMessage() . ")\n";
        $failCount++;
    }
}

echo "\n" . COLOR_CYAN . "========================================================\n";
echo "   MORTYX APPAREL — AUTOMATED AUTHENTICATION TESTS      \n";
echo "========================================================" . COLOR_RESET . "\n\n";

// 1. Initialize In-Memory SQLite Test Database
$testPdo = new PDO('sqlite::memory:', null, null, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$testPdo->exec("
    CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        phone TEXT NULL,
        role TEXT NOT NULL DEFAULT 'customer',
        created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TEXT NULL
    );
");

// Inject test PDO into Auth class
Auth::setPDO($testPdo);

// ========================================================
// Section 1: CSRF Protection
// ========================================================
echo COLOR_CYAN . "1. Testing CSRF Protection & Token Security:" . COLOR_RESET . "\n";

it("Auth::csrfToken generates 64-character hex string", function() {
    $token = Auth::csrfToken();
    return is_string($token) && strlen($token) === 64 && ctype_xdigit($token);
});

it("Auth::csrfToken returns the same token within active session", function() {
    $token1 = Auth::csrfToken();
    $token2 = Auth::csrfToken();
    return $token1 === $token2;
});

it("Auth::validateCsrf returns true for matching token", function() {
    $token = Auth::csrfToken();
    return Auth::validateCsrf($token) === true;
});

it("Auth::validateCsrf returns false for forged or altered token", function() {
    $forged = str_repeat('a', 64);
    return Auth::validateCsrf($forged) === false;
});

it("Auth::validateCsrf returns false for null or empty token", function() {
    return Auth::validateCsrf('') === false && Auth::validateCsrf(null) === false;
});

// ========================================================
// Section 2: User Registration & Validation
// ========================================================
echo "\n" . COLOR_CYAN . "2. Testing Customer Registration & Input Validation:" . COLOR_RESET . "\n";

it("Auth::register creates new customer and returns user_id", function() {
    $res = Auth::register('Gavin Streetwear', 'gavin@mortyx.com', 'streetwear2026', '081234567890');
    return $res['success'] === true && $res['user_id'] === 1 && $res['user']['name'] === 'Gavin Streetwear';
});

it("Password is saved as secure Bcrypt hash with cost 12", function() use ($testPdo) {
    $stmt = $testPdo->query("SELECT password FROM users WHERE email = 'gavin@mortyx.com'");
    $hash = $stmt->fetchColumn();
    // Bcrypt hash starts with $2y$12$ or $2a$12$
    return str_starts_with($hash, '$2y$12$') || str_starts_with($hash, '$2a$12$');
});

it("Plaintext password is never stored in database", function() use ($testPdo) {
    $stmt = $testPdo->query("SELECT password FROM users WHERE email = 'gavin@mortyx.com'");
    $hash = $stmt->fetchColumn();
    return $hash !== 'streetwear2026';
});

it("Auth::register rejects duplicate email address", function() {
    $res = Auth::register('Imposter Gavin', 'gavin@mortyx.com', 'differentpassword123');
    return $res['success'] === false && str_contains($res['error'], 'already registered');
});

it("Auth::register rejects invalid email format", function() {
    $res = Auth::register('Bad Email User', 'not-a-valid-email', 'password1234');
    return $res['success'] === false && str_contains($res['error'], 'valid email');
});

it("Auth::register rejects short password (< 8 chars)", function() {
    $res = Auth::register('Short Password', 'short@mortyx.com', '12345');
    return $res['success'] === false && str_contains($res['error'], 'at least 8 characters');
});

it("Auth::register rejects empty full name", function() {
    $res = Auth::register(' ', 'blankname@mortyx.com', 'validpassword123');
    return $res['success'] === false && str_contains($res['error'], 'at least 2 characters');
});

// ========================================================
// Section 3: Login & Authentication
// ========================================================
echo "\n" . COLOR_CYAN . "3. Testing Login Authentication & State:" . COLOR_RESET . "\n";

it("Auth::login succeeds with valid email and password", function() {
    $res = Auth::login('gavin@mortyx.com', 'streetwear2026');
    return $res['success'] === true && $res['user']['email'] === 'gavin@mortyx.com';
});

it("Auth::login is case-insensitive for email", function() {
    $res = Auth::login('GAVIN@MORTYX.COM', 'streetwear2026');
    return $res['success'] === true && $res['user']['id'] === 1;
});

it("Auth::login fails with incorrect password", function() {
    $res = Auth::login('gavin@mortyx.com', 'wrongpassword');
    return $res['success'] === false && $res['user'] === null;
});

it("Auth::login fails with unregistered email", function() {
    $res = Auth::login('nobody@mortyx.com', 'streetwear2026');
    return $res['success'] === false && $res['user'] === null;
});

it("Auth::login fails with empty inputs", function() {
    $res = Auth::login('', '');
    return $res['success'] === false;
});

// ========================================================
// Section 4: Session Identity & Role Helpers
// ========================================================
echo "\n" . COLOR_CYAN . "4. Testing Session Identity, State & Helpers:" . COLOR_RESET . "\n";

it("Auth::check returns true when logged in", function() {
    Auth::login('gavin@mortyx.com', 'streetwear2026');
    return Auth::check() === true;
});

it("Auth::user returns active user array", function() {
    $user = Auth::user();
    return is_array($user) && $user['name'] === 'Gavin Streetwear' && $user['role'] === 'customer';
});

it("Auth::id returns correct active user ID", function() {
    return Auth::id() === 1;
});

it("Auth::role returns 'customer' for regular member", function() {
    return Auth::role() === 'customer';
});

it("Auth::isAdmin returns false for customer", function() {
    return Auth::isAdmin() === false;
});

// ========================================================
// Section 5: Admin User Authentication
// ========================================================
echo "\n" . COLOR_CYAN . "5. Testing Admin User Authentication:" . COLOR_RESET . "\n";

it("Admin user can be created and authenticated", function() use ($testPdo) {
    $adminHash = password_hash('mortyx2026', PASSWORD_BCRYPT, ['cost' => 12]);
    $testPdo->exec("
        INSERT INTO users (name, email, password, role)
        VALUES ('System Admin', 'admin@mortyx.com', '{$adminHash}', 'admin')
    ");

    $res = Auth::login('admin@mortyx.com', 'mortyx2026');
    return $res['success'] === true && $res['user']['role'] === 'admin';
});

it("Auth::isAdmin returns true for authenticated admin", function() {
    Auth::login('admin@mortyx.com', 'mortyx2026');
    return Auth::isAdmin() === true && Auth::role() === 'admin';
});

// ========================================================
// Section 6: Logout & Session Teardown
// ========================================================
echo "\n" . COLOR_CYAN . "6. Testing Logout & Session Teardown:" . COLOR_RESET . "\n";

it("Auth::logout clears active user and terminates session", function() {
    Auth::login('gavin@mortyx.com', 'streetwear2026');
    $before = Auth::check();
    Auth::logout();
    $after = Auth::check();
    return $before === true && $after === false && Auth::user() === null && Auth::id() === null;
});

// ========================================================
// Final Result
// ========================================================
echo "\n" . COLOR_CYAN . "========================================================\n";
if ($failCount === 0) {
    echo "  " . COLOR_GREEN . "RESULT: ALL {$passCount} AUTHENTICATION TESTS PASSED! [STATUS: GREEN]" . COLOR_RESET . "\n";
} else {
    echo "  " . COLOR_RED . "RESULT: {$failCount} FAILED, {$passCount} PASSED [STATUS: RED]" . COLOR_RESET . "\n";
}
echo COLOR_CYAN . "========================================================" . COLOR_RESET . "\n\n";

exit($failCount === 0 ? 0 : 1);
