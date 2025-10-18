<?php
namespace App\Core;

declare(strict_types=1);

abstract class Controller
{
    protected Request $request;
    protected Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    protected function view(string $template, array $params = [], string $layout = 'layouts/main'): string
    {
        return View::render($template, $params, $layout);
    }

    protected function redirect(string $url): void
    {
        $this->response->redirect($url);
    }

    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }

    protected function flash(string $type, string $message): void
    {
        Session::flash($type, $message);
    }

    protected function requireAuth(): void
    {
        if (Session::userId() === null) {
            $this->flash('error', 'يرجى تسجيل الدخول للوصول.');
            $this->redirect('/login');
        }
    }

    protected function currentUser(): ?array
    {
        $userId = Session::userId();
        if ($userId === null) {
            return null;
        }
        return Database::fetchOne('SELECT id, name, email, role FROM users WHERE id = :id', ['id' => $userId]);
    }
}
