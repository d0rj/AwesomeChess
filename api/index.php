<?php 

declare(strict_types=1);

require_once "../lib/api/Router.php";
require_once "../lib/api/handlers/UsersHandler.php";


$usersHandler = new UsersHandler();
$router = new Router();

$router->AddRoute('users', $usersHandler);

$router->Work();