<?php
namespace App\Core;

declare(strict_types=1);

class Router
{
    private Request $request;
    private Response $response;

    /** @var array<string, array<int, array{pattern:string, regex:string, params:array<int, string>, handler:callable|array|string}>> */
    private array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
        'PATCH' => [],
    ];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get(string $pattern, callable|array|string $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, callable|array|string $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    public function put(string $pattern, callable|array|string $handler): void
    {
        $this->add('PUT', $pattern, $handler);
    }

    public function delete(string $pattern, callable|array|string $handler): void
    {
        $this->add('DELETE', $pattern, $handler);
    }

    public function patch(string $pattern, callable|array|string $handler): void
    {
        $this->add('PATCH', $pattern, $handler);
    }

    private function add(string $method, string $pattern, callable|array|string $handler): void
    {
        $normalized = $this->normalizePattern($pattern);
        [$regex, $paramNames] = $this->compilePattern($normalized);
        $this->routes[$method][] = [
            'pattern' => $normalized,
            'regex' => $regex,
            'params' => $paramNames,
            'handler' => $handler,
        ];
    }

    private function normalizePattern(string $pattern): string
    {
        if ($pattern === '') {
            return '/';
        }
        if ($pattern[0] !== '/') {
            $pattern = '/' . $pattern;
        }
        if ($pattern !== '/' && str_ends_with($pattern, '/')) {
            $pattern = rtrim($pattern, '/');
        }
        return $pattern;
    }

    /**
     * @return array{0:string,1:array<int,string>}
     */
    private function compilePattern(string $pattern): array
    {
        $paramNames = [];
        $regex = preg_replace_callback('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', function ($matches) use (&$paramNames) {
            $paramNames[] = $matches[1];
            return '(?P<' . $matches[1] . '>[^/]+)';
        }, $pattern);

        $regex = '#^' . $regex . '/?$#';
        return [$regex, $paramNames];
    }

    public function dispatch(): void
    {
        $method = $this->request->method();
        $path = $this->request->path();
        $routes = $this->routes[$method] ?? [];

        foreach ($routes as $route) {
            if (preg_match($route['regex'], $path, $matches)) {
                $params = [];
                foreach ($route['params'] as $name) {
                    if (isset($matches[$name])) {
                        $params[$name] = $matches[$name];
                    }
                }
                $this->invokeHandler($route['handler'], $params);
                return;
            }
        }

        $this->response->setStatusCode(404);
        $this->response->text('404 Not Found');
    }

    /**
     * @param callable|array|string $handler
     * @param array<string, string> $params
     */
    private function invokeHandler(callable|array|string $handler, array $params): void
    {
        if (is_callable($handler)) {
            $result = call_user_func_array($handler, [$this->request, $this->response, $params]);
            $this->emitResult($result);
            return;
        }

        if (is_array($handler) && count($handler) === 2) {
            [$class, $method] = $handler;
            if (is_string($class) && class_exists($class)) {
                $controller = new $class($this->request, $this->response);
                $result = $controller->$method(...array_values($params));
                $this->emitResult($result);
                return;
            }
        }

        if (is_string($handler) && str_contains($handler, '@')) {
            [$class, $method] = explode('@', $handler, 2);
            if (class_exists($class)) {
                $controller = new $class($this->request, $this->response);
                $result = $controller->$method(...array_values($params));
                $this->emitResult($result);
                return;
            }
        }

        $this->response->setStatusCode(500);
        $this->response->text('Invalid route handler');
    }

    private function emitResult(mixed $result): void
    {
        if ($result === null) {
            return;
        }
        if (is_string($result)) {
            echo $result;
            return;
        }
        if (is_array($result)) {
            $this->response->json($result);
        }
    }
}
