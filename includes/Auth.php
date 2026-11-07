<?php
/**
 * MORTYX Apparel — Authentication & Access Control Manager (Deep Module)
 * 
 * Standards: SRS FR-AUT, NFR-SEC, codebase-design (deep module with narrow interface), anti-slop.
 */

require_once __DIR__ . '/../config/database.php';

class Auth {
    private static ?PDO $pdoInstance = null;

    /**
     * Set a custom PDO instance (useful for unit testing with SQLite in-memory).
     */
    public static function setPDO(?PDO $pdo): void {
        self::$pdoInstance = $pdo;
    }

    /**
     * Get active database connection.
     */
    private static function getDB(): PDO {
        if (self::$pdoInstance !== null) {
            return self::$pdoInstance;
        }
        return get_db_connection();
    }

    /**
     * Start secure session if not already active.
     */
    public static function init(): void {
        if (session_status() === PHP_SESSION_NONE) {
            if (!headers_sent()) {
                $lifetime = 60 * 60 * 24 * 7; // 7 days
                session_set_cookie_params([
                    'lifetime' => $lifetime,
                    'path'     => '/',
                    'domain'   => '',
                    'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
            }
            @session_start();
        }
        if (!isset($_SESSION) || !is_array($_SESSION)) {
            $_SESSION = [];
        }
    }

    /**
     * Register a new customer.
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $phone
     * @return array [success => bool, user_id => int|null, error => string|null, user => array|null]
     */
    public static function register(string $name, string $email, string $password, string $phone = ''): array {
        self::init();

        $name = trim($name);
        $email = strtolower(trim($email));
        $phone = trim($phone);

        if ($name === '' || mb_strlen($name) < 2) {
            return ['success' => false, 'error' => 'Full name must be at least 2 characters.', 'user_id' => null, 'user' => null];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Please enter a valid email address.', 'user_id' => null, 'user' => null];
        }

        if (strlen($password) < 8) {
            return ['success' => false, 'error' => 'Password must be at least 8 characters long.', 'user_id' => null, 'user' => null];
        }

        $db = self::getDB();

        // Check if email already registered
        $stmt = $db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'error' => 'This email is already registered. Please login.', 'user_id' => null, 'user' => null];
        }

        // Hash with standard Bcrypt cost 12
        $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $insertSql = "INSERT INTO users (name, email, password, phone, role, created_at)
                      VALUES (:name, :email, :password, :phone, 'customer', datetime('now'))";

        try {
            $insertStmt = $db->prepare($insertSql);
            $insertStmt->execute([
                ':name'     => $name,
                ':email'    => $email,
                ':password' => $passwordHash,
                ':phone'    => $phone !== '' ? $phone : null
            ]);
        } catch (PDOException $e) {
            // MySQL fallback for CURRENT_TIMESTAMP
            $insertStmt = $db->prepare(
                "INSERT INTO users (name, email, password, phone, role, created_at)
                 VALUES (:name, :email, :password, :phone, 'customer', NOW())"
            );
            $insertStmt->execute([
                ':name'     => $name,
                ':email'    => $email,
                ':password' => $passwordHash,
                ':phone'    => $phone !== '' ? $phone : null
            ]);
        }

        $userId = (int)$db->lastInsertId();

        $user = [
            'id'    => $userId,
            'name'  => $name,
            'email' => $email,
            'phone' => $phone,
            'role'  => 'customer'
        ];

        // Auto-login after registration
        if (!headers_sent()) {
            @session_regenerate_id(true);
        }
        $_SESSION['user'] = $user;

        return ['success' => true, 'user_id' => $userId, 'error' => null, 'user' => $user];
    }

    /**
     * Authenticate user by email & password.
     *
     * @param string $email
     * @param string $password
     * @return array [success => bool, user => array|null, error => string|null]
     */
    public static function login(string $email, string $password): array {
        self::init();

        $email = strtolower(trim($email));

        if ($email === '' || $password === '') {
            return ['success' => false, 'error' => 'Please enter both email and password.', 'user' => null];
        }

        $db = self::getDB();
        $stmt = $db->prepare("SELECT id, name, email, password, phone, role FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();

        if (!$row || !password_verify($password, $row['password'])) {
            return ['success' => false, 'error' => 'Invalid email or password.', 'user' => null];
        }

        $user = [
            'id'    => (int)$row['id'],
            'name'  => $row['name'],
            'email' => $row['email'],
            'phone' => $row['phone'] ?? '',
            'role'  => $row['role'] ?? 'customer'
        ];

        if (!headers_sent()) {
            @session_regenerate_id(true);
        }
        $_SESSION['user'] = $user;

        return ['success' => true, 'user' => $user, 'error' => null];
    }

    /**
     * Terminate current user session.
     */
    public static function logout(): void {
        self::init();
        unset($_SESSION['user']);
        $_SESSION['user'] = null;

        if (ini_get('session.use_cookies') && !headers_sent()) {
            $params = session_get_cookie_params();
            @setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        if (php_sapi_name() !== 'cli' && session_status() === PHP_SESSION_ACTIVE) {
            @session_destroy();
        }
    }

    /**
     * Check if a user is currently logged in.
     */
    public static function check(): bool {
        self::init();
        return !empty($_SESSION['user']) && isset($_SESSION['user']['id']);
    }

    /**
     * Get active logged-in user data.
     */
    public static function user(): ?array {
        self::init();
        return $_SESSION['user'] ?? null;
    }

    /**
     * Get active user ID.
     */
    public static function id(): ?int {
        $user = self::user();
        return $user ? (int)$user['id'] : null;
    }

    /**
     * Get active user role.
     */
    public static function role(): ?string {
        $user = self::user();
        return $user['role'] ?? null;
    }

    /**
     * Check if active user is an admin.
     */
    public static function isAdmin(): bool {
        return self::role() === 'admin';
    }

    /**
     * Route guard: require authenticated user or redirect.
     */
    public static function requireAuth(string $redirectTo = 'auth/login.php'): void {
        if (!self::check()) {
            $_SESSION['return_url'] = $_SERVER['REQUEST_URI'] ?? 'index.php';
            header("Location: " . $redirectTo);
            exit;
        }
    }

    /**
     * Route guard: require guest user (redirect if already logged in).
     */
    public static function requireGuest(string $redirectTo = 'customer/account.php'): void {
        if (self::check()) {
            header("Location: " . $redirectTo);
            exit;
        }
    }

    /**
     * Route guard: require specific role.
     */
    public static function requireRole(string $role, string $redirectTo = 'index.php'): void {
        self::requireAuth();
        if (self::role() !== $role) {
            header("Location: " . $redirectTo);
            exit;
        }
    }

    /**
     * Generate or retrieve active CSRF token.
     */
    public static function csrfToken(): string {
        self::init();
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    /**
     * Validate an incoming CSRF token with constant-time comparison.
     */
    public static function validateCsrf(?string $token): bool {
        self::init();
        if (empty($_SESSION['_csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['_csrf_token'], $token);
    }
}
