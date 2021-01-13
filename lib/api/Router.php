<?php 

require_once __DIR__."/IRouteHandler.php";


class Router 
{
	private array $handlers = [];


	public function AddRoute(string $route, IRouteHandler &$handler)
	{
	   $this->handlers[$route] = $handler;
	}

	
	public function Work(): void 
	{
		header('Content-type: json/application');
		
		$q = $_GET['q'];
		$params = explode('/', $q);

		$handler = $params[0];
		$params = array_slice($params, 1);

		$method = $_SERVER['REQUEST_METHOD'];

		if (!array_key_exists($handler, $this->handlers)) 
		{
			header("HTTP/1.0 404 Not Found");

			echo json_encode([
				'error' => 1,
				'message' => 'Not found'
			]);

			return;
		}
		
		$result = call_user_func(array($this->handlers[$handler], 'On'.$method), $params);
		if ($result === false)
		{
			header("HTTP/1.0 404 Not Found");

			echo json_encode([
				'error' => 1,
				'message' => 'Not found'
			]);
		}
	}
}
