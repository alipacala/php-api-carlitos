<?php
define("ENV", "prod");

// Configuración de la base de datos
switch (ENV) {
    case 'dev':
        define("DB_HOST", "localhost");
        define("DB_USERNAME", "root");
        define("DB_PASSWORD", "");
        define("DB_DATABASE_NAME", "php-api-db");
        define("DB_PORT", 3306);
        break;

    case 'lan':
        define("DB_HOST", "192.168.1.11");
        define("DB_USERNAME", "abraham");
        define("DB_PASSWORD", "Admin123");
        define("DB_DATABASE_NAME", "hotelarenasspa");
        define("DB_PORT", 3307);
        break;

    case 'cloud':
        define("DB_HOST", "mysql-dev-control.alwaysdata.net");
        define("DB_USERNAME", "323003");
        define("DB_PASSWORD", "tomascito97A");
        define("DB_DATABASE_NAME", "dev-control_spa-arenas");
        define("DB_PORT", 3306);
        break;

    case 'server':
        define("DB_HOST", "localhost");
        define("DB_USERNAME", "abraham");
        define("DB_PASSWORD", "Admin123");
        define("DB_DATABASE_NAME", "hotelarenasspa");
        define("DB_PORT", 3307);
        break;

    case 'vm-prod':
        define("DB_HOST", "localhost");
        define("DB_USERNAME", "root");
        define("DB_PASSWORD", "@dm1n123");
        define("DB_DATABASE_NAME", "hotelarenasspa");
        define("DB_PORT", 3306);
        break;

    case 'prod':
        define("DB_HOST", "localhost");
        define("DB_USERNAME", "c1952071");
        define("DB_PASSWORD", "vureRIni44");
        define("DB_DATABASE_NAME", "c1952071_apitest");
        define("DB_PORT", 3306);
        break;

    default:
        break;
}

$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME, DB_PORT);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>