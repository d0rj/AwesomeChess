<?php

require_once __DIR__."/../IRouteHandler.php";


class UsersHandler implements IRouteHandler 
{
    public function OnGET(array $args): void 
    {
        $connection = mysqli_connect("127.0.0.1", "root", "root", "chess_db");

        $users = mysqli_query($connection, "SELECT `id`, `name`, `email`, `rating` FROM `users`");
        $usersList = [];

        while ($user = mysqli_fetch_assoc($users)) 
        {
            $usersList[] = $user;
        }

        echo json_encode($usersList);
    }

    public function OnPOST(array $args): void 
    {

    }
    public function OnPUT(array $args): void 
    {

    }
    public function OnDELETE(array $args): void 
    {

    }
}