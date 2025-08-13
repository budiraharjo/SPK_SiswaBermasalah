<?php
namespace Core;

class Route
{
    public static $routes = [];

    public static function get($uri, $action)
    {
        self::$routes['GET'][$uri] = $action;
    }

	public static function dispatch($method, $uri)
	{
		$uri = parse_url($uri, PHP_URL_PATH);

		foreach (self::$routes[$method] as $route => $action) {
			$pattern = preg_replace('/\{[a-zA-Z_]+\}/', '(\d+)', $route);
			if (preg_match('#^' . $pattern . '$#', $uri, $matches)) {
				array_shift($matches); // hapus full match
				$action = explode('@', $action);
				$controller = "App\\Controllers\\{$action[0]}";
				$methodName = $action[1];
				(new $controller)->$methodName(...$matches);
				return;
			}
		}

		http_response_code(404);
		require '../app/Views/404.php';
	}
	
	public static function post($uri, $action)
	{
		self::$routes['POST'][$uri] = $action;
	}

}

