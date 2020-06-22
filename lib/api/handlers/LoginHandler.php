<?php 

require_once __DIR__."/../IRouteHandler.php";
require_once __DIR__."/../../database/DataBase.php";
require_once __DIR__."/../../database/commands/GetUsersCommand.php";


class LoginHandler implements IRouteHandler 
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

    }


    public function OnPOST(array $args): void
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (!isset($email) || !isset($password)) 
        {
            echo json_encode([
                'errors' => 1,
                'message' => 'Email and password required for login.'
            ]);

            return;
        }

        $users = $this->db->ExecuteGetList(new GetUsersCommand('`email` = \''.$email.'\' AND `password` = \''.$password.'\''));

        if (isset($users) && count($users) === 1) 
        {
            echo json_encode([
                'errors' => 0,
                'message' => 'Login successfull.'
            ]);
            return;
        }

        echo json_encode([
            'errors' => 1,
            'message' => 'No users with this email or password.'
        ]);
    }


    public function OnPUT(array $args): void
    {

    }


    public function OnDELETE(array $args): void
    {

    }
}