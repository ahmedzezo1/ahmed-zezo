<?php
namespace App\Core;

declare(strict_types=1);

class View
{
    public static function render(string $template, array $params = [], string|false $layout = 'layouts/main'): string
    {
        $viewFile = VIEW_PATH . '/' . ltrim($template, '/') . '.php';
        if (!is_file($viewFile)) {
            return 'View not found: ' . $template;
        }

        extract($params, EXTR_SKIP);

        ob_start();
        include $viewFile;
        $content = (string)ob_get_clean();

        if ($layout === false) {
            return $content;
        }

        $layoutFile = VIEW_PATH . '/' . ltrim($layout, '/') . '.php';
        if (!is_file($layoutFile)) {
            return $content;
        }

        ob_start();
        include $layoutFile;
        return (string)ob_get_clean();
    }

    public static function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
