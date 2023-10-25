<?php
namespace App\Repository\Postgres;

require __DIR__ . '/../../../vendor/autoload.php';

if (!function_exists('config')) {
    function config(string $key) : array|string|null
    {
        $params = explode('.', $key);
        $file = __DIR__ . "/../../Config/{$params[0]}.php";
        if (!file_exists($file)) return null;
        $in = include $file;
        for ($i = 1; $i < count($params); $i++) { 
            if (!isset($in[$params[$i]])) {
                throw new \Exception("The \"{$key}\" config not found!");
            }
            $in = $in[$params[$i]];
        }
        return $in;
    }
}

/**
 * Represent the Connection
 */
class Connection {

    /**
     * Connection
     * @var type 
     */
    private static $conn;

    /**
     * Connect to the database and return an instance of \PDO object
     * @return \PDO
     * @throws \Exception
     */
    public function connect() {
        $config = config('database.pgsql');
        // connect to the postgresql database
        $conStr = sprintf("pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s", 
                $config['host'], 
                $config['port'], 
                $config['database'], 
                $config['username'], 
                $config['password']);

        $pdo = new \PDO($conStr);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    /**
     * return an instance of the Connection object
     * @return type
     */
    public static function get() {
        if (null === static::$conn) {
            static::$conn = new static();
        }

        return static::$conn;
    }

    protected function __construct() {
        
    }

    private function __clone() {
        
    }

    public function __wakeup() {
        
    }

}