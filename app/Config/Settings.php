<?php

namespace App\Config;

use Dotenv\Dotenv;
use PDO;
use PDOException;

require_once __DIR__ . '/../../vendor/autoload.php';

class Settings
{

    protected static $dotenv;

    public function __construct()
    {
        self::$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        self::$dotenv->load();
    }

    public static function getSettings()
    {
        return [
            'host' => $_ENV['DB_HOST'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS']
        ];
    }

    public static function getConnection($game)
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $connection = null;

        $databaseName = $_ENV['DB_' . strtoupper($game)];

        try {
            $connection = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $databaseName, $_ENV['DB_USER'], $_ENV['DB_PASS']);
            $connection->exec("set names utf8");
        } catch (PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
        }

        return $connection;
    }

}