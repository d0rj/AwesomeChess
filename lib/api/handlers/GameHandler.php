<?php

require_once __DIR__."/../IRouteHandler.php";
require_once __DIR__."/../../database/DataBase.php";
require_once __DIR__."/../../database/AuthCache.php";
require_once __DIR__."/../../chess/ChessGame.php";
require_once __DIR__."/../../chess/GameMessage.php";
require_once __DIR__."/../../database/commands/AvailableGamesCommand.php";
require_once __DIR__."/../../database/commands/GetGameCommand.php";


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

    }


    public function OnPUT(array $args): void
    {

    }


    public function OnDELETE(array $args): void
    {

    }
}