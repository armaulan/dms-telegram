<?php
namespace Armaulan\DmsTelegram\Class;
require_once '../vendor/autoload.php';

use Dotenv\Dotenv;
use PDO;

class Database {

    protected $connections = [];

    public function __construct() {
        $dotenv = Dotenv::createImmutable('../');
        $dotenv->load();
    }

    // Lazy load and return a connection
    public function getConnection($connectionName) {
        if (!isset($this->connections[$connectionName])) {
            $this->createConnection($connectionName);
        }

        return $this->connections[$connectionName];
    }

    // Establish a connection when needed
    protected function createConnection($connectionName) {
            $dbConfig = $this->getDbConfigFromEnv($connectionName);
            $this->connections[$connectionName] = new PDO(
                $dbConfig['dsn'],
                $dbConfig['username'],
                $dbConfig['password']
        );
    }

    // Retrieve database credentials from environment variables
    protected function getDbConfigFromEnv($connectionName) {
        $prefix = strtoupper($connectionName) . '_';
        return [
            'dsn' => "mysql:host=" . $_ENV[$prefix . 'HOST'] . ";port=" . $_ENV[$prefix . 'PORT'] . ";dbname=" . $_ENV[$prefix . 'NAME'], // Include port from environment variable
            'username' => $_ENV[$prefix . 'USERNAME'],
            'password' => $_ENV[$prefix . 'PASSWORD'],
        ];
    }

    // Example usage: (no changes needed here)
    public function getDistributorData($script) {
        $db = $this->getConnection('distributor');
        $statement = $db->query($script);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAgentData($script) {
        $db = $this->getConnection('agent');
        $statement = $db->query($script);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}