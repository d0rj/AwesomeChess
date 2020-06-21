<?php

require_once __DIR__."/../IRouteHandler.php";
require_once __DIR__."/../../database/DataBase.php";
require_once __DIR__."/../../database/commands/GetUsersCommand.php";
require_once __DIR__."/../../database/commands/CreateUserCommand.php";


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
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (isset($name) && isset($email) && isset($password)) 
        {
            $queryResult = $this->db->Execute(new CreateUserCommand($name, $email, $password));

            if ($queryResult === TRUE) 
            {
                echo json_encode([
                    'errors' => 0,
                    'message' => 'User added.'
                ]);
            }
            else 
            {
                echo json_encode([
                    'errors' => 1,
                    'message' => 'User not added. User already exists or Error in db query.'
                ]);
            }
        }
        else 
        {
            echo json_encode([
                'errors' => 1,
                'message' => 'To create new user you need to pass 3 arguments: name, email and password.'
            ]);
        }
    }


    public function OnPUT(array $args): void 
    {

    }

    
    public function OnDELETE(array $args): void 
    {

    }
}