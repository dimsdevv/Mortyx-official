<?php
/**
 * MORTYX Apparel — Database Configuration & PDO Connection Manager
 * 
 * Supports MySQL / MariaDB (XAMPP Environment) with graceful fallback / testing mode.
 */

if (!function_exists('get_db_config')) {
    function get_db_config() {
        return [
            'driver'   => getenv('DB_DRIVER') ?: 'mysql',
            'host'     => getenv('DB_HOST') ?: '127.0.0.1',
            'port'     => getenv('DB_PORT') ?: '3306',
            'dbname'   => getenv('DB_NAME') ?: 'mortyx_db',
            'username' => getenv('DB_USER') ?: 'root',
            'password' => getenv('DB_PASS') !== false ? getenv('DB_PASS') : '',
            'charset'  => 'utf8mb4'
        ];
    }
}

if (!function_exists('get_db_connection')) {
    /**
     * Mengembalikan instance PDO tunggal (Singleton pattern).
     *
     * @param array|null $customConfig
     * @return PDO
     * @throws PDOException
     */
    function get_db_connection($customConfig = null) {
        static $pdoInstance = null;

        if ($pdoInstance !== null && $customConfig === null) {
            return $pdoInstance;
        }

        $config = $customConfig ?: get_db_config();
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        if ($config['driver'] === 'sqlite') {
            $dsn = 'sqlite:' . $config['dbname'];
            $pdo = new PDO($dsn, null, null, $options);
            $pdo->exec('PRAGMA foreign_keys = ON;');
        } else {
            // MySQL Connection
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['dbname'],
                $config['charset']
            );
            $pdo = new PDO($dsn, $config['username'], $config['password'], $options);
        }

        if ($customConfig === null) {
            $pdoInstance = $pdo;
        }

        return $pdo;
    }
}

if (!function_exists('ensure_database_exists')) {
    /**
     * Memastikan database MySQL mortyx_db telah terbuat di server MySQL.
     *
     * @param array|null $config
     * @return bool
     */
    function ensure_database_exists($config = null) {
        $cfg = $config ?: get_db_config();
        if ($cfg['driver'] !== 'mysql') {
            return true;
        }

        try {
            $rootDsn = sprintf('mysql:host=%s;port=%s;charset=%s', $cfg['host'], $cfg['port'], $cfg['charset']);
            $rootPdo = new PDO($rootDsn, $cfg['username'], $cfg['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            $dbname = preg_replace('/[^a-zA-Z0-9_]/', '', $cfg['dbname']);
            $rootPdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

if (!function_exists('run_schema_migration')) {
    /**
     * Mengeksekusi file SQL schema ke dalam database yang aktif.
     *
     * @param PDO $pdo
     * @param string|null $schemaPath
     * @return bool
     */
    function run_schema_migration(PDO $pdo, $schemaPath = null) {
        $path = $schemaPath ?: __DIR__ . '/../database/schema.sql';
        if (!file_exists($path)) {
            throw new RuntimeException("Schema file not found at: {$path}");
        }

        $sql = file_get_contents($path);
        
        // Eksekusi multi-query
        $pdo->exec($sql);
        return true;
    }
}
