<?php
namespace Framework;


class EntryPoint
{
	private $routes;
	public function __construct()
	{
	}

	public function run()
	{
		$route = ltrim(explode('?', $_SERVER['REQUEST_URI'])[0], '/');

		$controller = Routes::getController($route, $_SERVER['REQUEST_METHOD']);
		if (!$controller || !$controller[3]()) {
			require "../views/notFound.html.php";
			exit;
		}
		$method = $controller[2][1];

		$page = (new $controller[2][0])->$method();

		$output = $this->loadTemplate('../views/' . $page['template'], $page['variables']);

		$title = $page['title'];

		require '../views/layout.html.php';
	}

	public function loadTemplate($fileName, $templateVars)
	{
		extract($templateVars);
		ob_start();
		require $fileName;
		$contents = ob_get_clean();
		return $contents;
	}
}