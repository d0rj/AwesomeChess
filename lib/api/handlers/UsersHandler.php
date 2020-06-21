<?php

require_once __DIR__."/../IRouteHandler.php";
require_once __DIR__."/../../database/DataBase.php";
require_once __DIR__."/../../database/commands/GetUsersCommand.php";


class UsersHandler implements IRouteHandler 
{
    private DataBase $db;


    public function __construct() 
    {
        $this->db = new DataBase();
    }


    public function OnGET(array $args): void 
    {
        $usersList = $this->db->ExecuteGetList(new GetUsersCommand());
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