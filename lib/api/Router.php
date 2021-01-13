<?php 

require_once __DIR__."/IRouteHandler.php";
require_once __DIR__."../../Responce.php";


class Router 
{
	private array $handlers = [];


	public function AddRoute(string $route, IRouteHandler &$handler)
	{
	   $this->handlers[$route] = $handler;
	}

	
	public function Work(): void 
	{
		Responce::Init();
		
		$q = $_GET['q'];
		$params = explode('/', $q);

		$handler = $params[0];
		$params = array_slice($params, 1);

		$method = $_SERVER['REQUEST_METHOD'];

		if (!array_key_exists($handler, $this->handlers)) 
		{
			Responce::Send(404, 'Not found API for \''.$handler.'\'.');
			return;
		}
		
		if (!method_exists($this->handlers[$handler], 'On'.$method)) 
		{
			Responce::Send(404, 'Not found method \''.$method.'\' for this API part.');
			return;
		}

		$result = call_user_func(array($this->handlers[$handler], 'On'.$method), $params);
		if ($result === false)
		{
			Responce::Send(404, 'API not working...');
		}
	}
}
