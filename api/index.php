<?php 

declare(strict_types=1);

require_once "../lib/api/Router.php";
require_once "../lib/api/handlers/UsersHandler.php";
require_once "../lib/database/DataBase.php";


$db = new DataBase('chess_db');

$usersHandler = new UsersHandler($db);
$router = new Router();

$router->AddRoute('users', $usersHandler);

$router->Work();