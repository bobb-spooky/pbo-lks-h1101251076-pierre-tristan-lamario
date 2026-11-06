<?php

namespace App\Database;

use App\Config\Env;
use PDO;
use PDOException;

class Connection
{
    private static ?PDO $instance = null;

    public static function get(): PDO
    {
        if (self::$instance === null) {
            $host = Env::get('DB_HOST', '127.0.0.1');
            $port = Env::get('DB_PORT', '3306');
            $database = Env::get('DB_NAME', 'perpustakaan');
            $username = Env::get('DB_USER', 'root');
            $password = Env::get('DB_PASS', '');

            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

            try {
                self::$instance = new PDO(
                    $dsn,
                    $username,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                throw new \RuntimeException(
                    "Koneksi database gagal: " . $e->getMessage()
                );
            }
        }

        return self::$instance;
    }
}
