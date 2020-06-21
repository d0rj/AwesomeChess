<?php

require_once __DIR__."/../IRouteHandler.php";
require_once __DIR__."/../../database/DataBase.php";
require_once __DIR__."/../../database/commands/GetUsersCommand.php";
require_once __DIR__."/../../database/commands/CreateUserCommand.php";
require_once __DIR__."/../../database/commands/UpdateUserCommand.php";


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

            if ($queryResult === true) 
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
        /* TODO: Check access rules */

        $data = file_get_contents('php://input');
        $data = json_decode($data, true);

        $name = $data['name'];
        $newName = $data['newName'];
        $newEmail = $data['newEmail'];
        $newPassword = $data['newPassword'];

        if (!isset($name)) 
        {
            echo json_encode([
                'errors' => 1,
                'message' => 'Required argument \'name\' for updating '
            ]);
            die();
        }

        if (!isset($newName) && !isset($newEmail) && !isset($newPassword)) 
        {
            echo json_encode([
                'errors' => 1,
                'message' => 'At least one property must be changed.'
            ]);
            die();
        }

        /* TODO: Check for correct changes */

        $queryResult = $this->db->Execute(new UpdateUserCommand($name, $newName, $newEmail, $newPassword));

        if ($queryResult === true) 
        {
            echo json_encode([
                'errors' => 0,
                'message' => 'User updated.'
            ]);
        }
        else 
        {
            echo json_encode([
                'errors' => 1,
                'message' => 'User not updated. User not found or Error in db query.'
            ]);
        }
    }

    
    public function OnDELETE(array $args): void 
    {

    }
}