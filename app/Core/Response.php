<?php
namespace App\Core;

declare(strict_types=1);

class Response
{
    private int $statusCode = 200;

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
        http_response_code($statusCode);
    }

    public function header(string $name, string $value): void
    {
        header($name . ': ' . $value, true);
    }

    public function json(mixed $data, int $status = 200): void
    {
        $this->setStatusCode($status);
        $this->header('Content-Type', 'application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function redirect(string $url, int $status = 302): void
    {
        $this->setStatusCode($status);
        header('Location: ' . $url);
        exit;
    }

    public function text(string $content, int $status = 200, string $contentType = 'text/plain; charset=utf-8'): void
    {
        $this->setStatusCode($status);
        $this->header('Content-Type', $contentType);
        echo $content;
    }
}
