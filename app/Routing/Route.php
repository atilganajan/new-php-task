<?php

namespace App\Routing;

class Route
{
    private static $routes = [];

    public static function get($uri, $action)
    {
        self::addRoute('GET', $uri, $action);
    }

    public static function post($uri, $action)
    {
        self::addRoute('POST', $uri, $action);
    }

    public static function put($uri, $action)
    {
        self::addRoute('PUT', $uri, $action);
    }

    public static function delete($uri, $action)
    {
        self::addRoute('DELETE', $uri, $action);
    }

    public static function middleware($middlewares, $callback)
    {
        $currentMiddlewares = $middlewares;

        $callback();

        foreach (self::$routes as &$route) {
            $route['middlewares'] = $currentMiddlewares;
        }
    }

    private static function addRoute($method, $uri, $action)
    {
        $uri = trim($uri, '/');
        $uriPattern = self::convertUriToRegex($uri);

        self::$routes[] = [
            'method' => $method,
            'uri' => $uri,
            'uri_pattern' => $uriPattern,
            'action' => $action,
            'middlewares' => [],
        ];
    }

    private static function convertUriToRegex($uri)
    {
        return preg_replace_callback('/\{(\w+)(:([^}]+))?\??\}/', function ($matches) {
            $regex = isset($matches[3]) ? $matches[3] : '[^\/]+';
            $optional = isset($matches[0]) && strpos($matches[0], '?') !== false ? "?" : "";
            return "($regex)$optional";
        }, trim($uri, '/') );
    }

    public static function resolve()
    {
        $requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $requestMethod = $_SERVER['REQUEST_METHOD'];





        foreach (self::$routes as $route) {

            $uri_pattert_arr = explode($requestUri,$route['uri_pattern']);

            if(isset($uri_pattert_arr[1]) && $uri_pattert_arr[1] == "/([^\/]+)?"){

                $route['uri_pattern'] = $requestUri;
            }

            $pattern = "#^" . $route['uri_pattern'] . "$#";
            if (preg_match($pattern, $requestUri, $matches)) {

                array_shift($matches);

                // Middleware kontrolü
                $lastMiddleware = function() use ($route, $matches) {
                    list($controller, $method) = $route['action'];
                    $controllerInstance = new $controller;
                    return call_user_func_array([$controllerInstance, $method], $matches);
                };

                // Middleware'leri çalıştır
                foreach ($route['middlewares'] as $middleware) {
                    $middlewareInstance = new $middleware;
                    $lastMiddleware = function() use ($middlewareInstance, $lastMiddleware) {
                        return $middlewareInstance->handle($lastMiddleware);
                    };
                }

                return $lastMiddleware();
            }

        }

        http_response_code(404);
        require_once 'resources/views/errors/404.php';
        exit();
    }
}
