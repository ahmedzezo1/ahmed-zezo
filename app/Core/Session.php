<?php
namespace App\Core;

declare(strict_types=1);

class Session
{
    private const FLASH_KEY = '_flash';
    private static bool $started = false;

    public static function init(): void
    {
        if (self::$started) {
            return;
        }
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        self::$started = true;
        // Initialize flash array if not present
        if (!isset($_SESSION[self::FLASH_KEY])) {
            $_SESSION[self::FLASH_KEY] = ['new' => [], 'old' => []];
        }
        // Sweep old flash
        $_SESSION[self::FLASH_KEY]['old'] = $_SESSION[self::FLASH_KEY]['new'] ?? [];
        $_SESSION[self::FLASH_KEY]['new'] = [];
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function flash(string $key, string $message): void
    {
        $_SESSION[self::FLASH_KEY]['new'][$key] = $message;
    }

    public static function getFlash(string $key, ?string $default = null): ?string
    {
        if (isset($_SESSION[self::FLASH_KEY]['old'][$key])) {
            return (string)$_SESSION[self::FLASH_KEY]['old'][$key];
        }
        return $default;
    }

    public static function allFlashes(): array
    {
        return $_SESSION[self::FLASH_KEY]['old'] ?? [];
    }

    public static function regenerate(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public static function setUserId(?int $userId): void
    {
        if ($userId === null) {
            unset($_SESSION['user_id']);
            return;
        }
        $_SESSION['user_id'] = $userId;
    }

    public static function userId(): ?int
    {
        $id = $_SESSION['user_id'] ?? null;
        return $id !== null ? (int)$id : null;
    }
}
