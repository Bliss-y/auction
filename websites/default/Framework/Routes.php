<?php
namespace Framework;

class Routes
{

	public static $getroutes = [
	];
	public static $postroutes = [];
	public static function getController($route, $method)
	{
		$route = explode('/', $route);
		$routes = $method == "GET" ? Routes::$getroutes : Routes::$postroutes;
		$current = "";
		while (count($route) > 0) {
			$r = array_shift($route);
			$current .= '/' . strtolower($r);
			if (isset($routes[$current]) && count($routes[$current][1]) == count($route)) {
				$i = 0;
				foreach ($routes[$current][1] as $a) {
					$_GET[$a] = $route[$i];
					$i++;
				}
				return $routes[$current];
			}
		}
	}

	public static function get(
		$route,
		$params,
		$controller,
		$middleware
	) {
		Routes::$getroutes[strtolower($route)] = [$route, $params, $controller, $middleware];
	}

	public static function post(
		$route,
		$params,
		$controller,
		$middleware
	) {
		Routes::$postroutes[strtolower($route)] = [$route, $params, $controller, $middleware];
	}
	public static function getDefaultRoute()
	{

	}
}