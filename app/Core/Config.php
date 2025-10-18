<?php
namespace App\Core;

declare(strict_types=1);

class Config
{
    private static array $cache = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        [$file, $path] = self::splitKey($key);
        $config = self::loadFile($file);

        $value = $config;
        foreach ($path as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }
        return $value;
    }

    private static function splitKey(string $key): array
    {
        $parts = explode('.', $key);
        $file = array_shift($parts) ?? '';
        return [$file, $parts];
    }

    private static function loadFile(string $file): array
    {
        if (isset(self::$cache[$file])) {
            return self::$cache[$file];
        }
        $path = CONFIG_PATH . '/' . $file . '.php';
        $config = [];
        if (is_file($path)) {
            $config = require $path;
            if (!is_array($config)) {
                $config = [];
            }
        }
        return self::$cache[$file] = $config;
    }
}
