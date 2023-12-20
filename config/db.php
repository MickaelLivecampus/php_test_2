<?php
require_once(dirname(__DIR__) . "/vendor/" . 'autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1), '.env');
$dotenv->load();

function getPDO() {
    $dbHost = $_ENV['MYSQL_HOST'];
    $dbPort = $_ENV['MYSQL_PORT'];
    $dbName = $_ENV['MYSQL_DATABASE'];
    $dbUsername = $_ENV['MYSQL_USER'];
    $dbPassword = $_ENV['MYSQL_PASSWORD'];

    $pdo = NULL;
    try {
        $pdo = new PDO("mysql:host=" . $dbHost . ":" .  $dbPort . ";dbname=" . $dbName , $dbUsername, $dbPassword);
    } catch(PDOException $e) {
        http_response_code(500);
        echo "Unable to connect to database" . $e;
        exit();
    }
    return $pdo ? $pdo : $e;
}

function connectDB()
{

    $host = $_ENV['MYSQL_HOST'];
    $user = $_ENV['MYSQL_USER'];
    $password = $_ENV['MYSQL_PASSWORD'];
    $database = $_ENV['MYSQL_DATABASE'];
    $port = $_ENV['MYSQL_PORT'];
    
    $conn = new mysqli($host, $user, $password, $database, $port);

    if ($conn->connect_error) {
        die("Unable to connect to database: " . $conn->connect_error);
    }

    return $conn;
}