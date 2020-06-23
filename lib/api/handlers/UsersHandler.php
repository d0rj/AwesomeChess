<?php

require_once __DIR__."/../IRouteHandler.php";
require_once __DIR__."/../../database/DataBase.php";
require_once __DIR__."/../../database/commands/GetUsersCommand.php";
require_once __DIR__."/../../database/commands/CreateUserCommand.php";
require_once __DIR__."/../../database/commands/UpdateUserCommand.php";


class UsersHandler implements IRouteHandler 
{
    private DataBase $db;


    public function __construct(DataBase &$db = NULL) 
    {
        if ($db === NULL)
            $this->db = new DataBase('chess_db');
        else
            $this->db = $db;
    }


    public function OnGET(array $args): void 
    {
        if (count($args) === 0 || $args[0] === '') 
        {
            $usersList = $this->db->ExecuteGetList(new GetUsersCommand());
            echo json_encode($usersList);
            return;
        }

        if ($this->paramsIsGetById($args)) 
        {
            $user = $this->db->ExecuteGetList(new GetUsersCommand('`id` = '.$args[0]))[0];

            if (!isset($user))
            {
                header("HTTP/1.0 404 Not Found");

                echo json_encode([
                    'errors' => 1,
                    'message' => 'No user with id '.$args[0]
                ]);
            }
            else 
                echo json_encode($user);

            return;
        }

        if (count($args) === 1) 
        {
            $user = $this->db->ExecuteGetList(new GetUsersCommand('`name` = \''.$args[0].'\''))[0];

            if (!isset($user)) 
            {
                header("HTTP/1.0 404 Not Found");

                echo json_encode([
                    'errors' => 1,
                    'message' => 'No user with name \''.$args[0].'\''
                ]);
            }
            else 
                echo json_encode($user);

            return;
        }

        if ($this->paramsIsGetByRating($args)) 
        {
            $users = $this->db->ExecuteGetList(new GetUsersCommand('`rating` = '.$args[1]));

            echo json_encode($users);

            return;
        }

        header("HTTP/1.0 400 Bad Reqest");

        echo json_encode([
            'errors' => 1,
            'message' => 'Unknown command.'
        ]);
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
                header("HTTP/1.0 201 Created");

                echo json_encode([
                    'errors' => 0,
                    'message' => 'User added.'
                ]);
            }
            else 
            {
                header("HTTP/1.0 409 Conflict");

                echo json_encode([
                    'errors' => 1,
                    'message' => 'User not added. User already exists or Error in db query.'
                ]);
            }
        }
        else 
        {
            header("HTTP/1.0 400 Bad Reqest");

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
            header("HTTP/1.0 400 Bad Reqest");

            echo json_encode([
                'errors' => 1,
                'message' => 'Required argument \'name\' for updating '
            ]);
            
            return;
        }

        if (!isset($newName) && !isset($newEmail) && !isset($newPassword)) 
        {
            header("HTTP/1.0 406 Not Acceptable");

            echo json_encode([
                'errors' => 1,
                'message' => 'At least one property must be changed.'
            ]);
            
            return;
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
            header("HTTP/1.0 400 Bad Reqest");

            echo json_encode([
                'errors' => 1,
                'message' => 'User not updated. User not found or Error in db query.'
            ]);
        }
    }

    
    public function OnDELETE(array $args): void 
    {

    }


    private function paramsIsGetById(array $args): bool
    {
        return count($args) === 1 && is_numeric($args[0]);
    }


    private function paramsIsGetByRating(array $args): bool 
    {
        return count($args) === 2 && $args[0] === 'rating' && is_numeric($args[1]);
    }
}