<?php
namespace App\Core;

declare(strict_types=1);

class Request
{
    public function method(): string
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        return strtoupper($method);
    }

    public function path(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }
        return $path === '' ? '/' : $path;
    }

    public function query(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    public function input(?string $key = null, mixed $default = null): mixed
    {
        $data = $_POST;

        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'application/json') !== false) {
            $raw = file_get_contents('php://input');
            $json = json_decode($raw, true);
            if (is_array($json)) {
                $data = array_merge($data, $json);
            }
        }

        if ($key === null) {
            return $data;
        }
        return $data[$key] ?? $default;
    }

    public function all(): array
    {
        return [
            'query' => $this->query(),
            'body' => $this->input(),
            'files' => $_FILES,
        ];
    }
}
