<?php

require_once __DIR__."/../IRouteHandler.php";
require_once __DIR__."/../../database/DataBase.php";
require_once __DIR__."/../../database/AuthCache.php";
require_once __DIR__."/../../chess/ChessGame.php";
require_once __DIR__."/../../chess/GameMessage.php";
require_once __DIR__."/../../database/commands/AvailableGamesCommand.php";
require_once __DIR__."/../../database/commands/GetGameCommand.php";
require_once __DIR__."/../../database/commands/GetUsersCommand.php";
require_once __DIR__."/../../database/commands/CreateGameCommand.php";


class GameHandler implements IRouteHandler 
{
    private DataBase $db;
    private AuthCache $cache;


    public function __construct(DataBase &$db = NULL) 
    {
        if ($db === NULL)
            $this->db = new DataBase('chess_db');
        else
            $this->db = $db;

        $this->cache = new AuthCache();
    }


    public function OnGET(array $args): void 
    {
        // All available games
        if (count($args) === 0) 
        {
            $loggedEmail = $this->cache->LoggedForEmail();
    
            if ($loggedEmail === '') 
            {
                header("HTTP/1.0 401 Unauthorized");
                
                echo json_encode([
                    'errors' => 1,
                    'message' => 'You\'re not logged in.'
                ]);
    
                return;
            }
    
            $games = $this->db->ExecuteGetList(new AvailableGamesCommand($loggedEmail));
    
            echo json_encode($games);
            return;
        }
        // Get game by id
        else if (count($args) === 1 && is_numeric($args[0])) 
        {
            $id = intval($args[0]);
            $game = $this->db->ExecuteGetList(new GetGameCommand($id))[0];

            echo json_encode($game);
            return;
        }
    }


    public function OnPOST(array $args): void 
    {
        $secondEmail = $_POST['secondEmail'];

        if (!isset($secondEmail)) 
        {
            header("HTTP/1.0 400 Bad Reqest");

            echo json_encode([
                'errors' => 1,
                'message' => 'To create new game you need to pass 1 arguments: \'secondEmail\' is email of second player.'
            ]);
            return;
        }

        $loggedEmail = $this->cache->LoggedForEmail();    
        if ($loggedEmail === '') 
        {
            header("HTTP/1.0 401 Unauthorized");
            
            echo json_encode([
                'errors' => 1,
                'message' => 'You\'re not logged in.'
            ]);
    
            return;
        }

        $secondUser = $this->db->ExecuteGetList(new GetUsersCommand('`email` = \''.$secondEmail.'\''))[0];
        if (!isset($secondUser)) 
        {
            header("HTTP/1.0 404 Not Found");

            echo json_encode([
                'errors' => 1,
                'message' => 'No user found with this email.'
            ]);
            return;
        }

        $firstId = $this->db->ExecuteGetList(new GetUsersCommand('`email` = \''.$loggedEmail.'\''))[0]['id'];
        $secondId = $secondUser['id'];
        $result = $this->db->Execute(new CreateGameCommand($firstId, $secondId));
        if ($result === true) 
        {
            header("HTTP/1.0 201 Created");

            echo json_encode([
                'errors' => 0,
                'message' => 'Game created.'
            ]);
        }
        else 
        {
            header("HTTP/1.0 409 Conflict");

            echo json_encode([
                'errors' => 1,
                'message' => 'Game not created. Error in db query.'
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