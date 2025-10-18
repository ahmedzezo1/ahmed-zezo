<?php
declare(strict_types=1);

// Base path constants
$basePath = dirname(__DIR__);
if (!defined('BASE_PATH')) {
    define('BASE_PATH', $basePath);
}
if (!defined('APP_PATH')) {
    define('APP_PATH', BASE_PATH . '/app');
}
if (!defined('VIEW_PATH')) {
    define('VIEW_PATH', APP_PATH . '/Views');
}
if (!defined('CONFIG_PATH')) {
    define('CONFIG_PATH', BASE_PATH . '/config');
}
if (!defined('STORAGE_PATH')) {
    define('STORAGE_PATH', BASE_PATH . '/storage');
}
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', BASE_PATH . '/public');
}

// Error reporting defaults; can be overridden by config
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Default timezone
if (!ini_get('date.timezone')) {
    date_default_timezone_set('UTC');
}

// Simple PSR-4 style autoloader for the App namespace
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $prefixLength = strlen($prefix);

    if (strncmp($class, $prefix, $prefixLength) !== 0) {
        return;
    }

    $relativeClass = substr($class, $prefixLength);
    $file = APP_PATH . '/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});
