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
require_once __DIR__."/../../database/commands/UpdateGameCommand.php";
require_once __DIR__."../../../Responce.php";


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
				Responce::Send(401, 'You\'re not logged in.');	
				return;
			}
	
			$games = $this->db->ExecuteGetList(new AvailableGamesCommand($loggedEmail));
	
			Responce::Send(200, $games);
			return;
		}
		// Get game by id
		else if (count($args) === 1 && is_numeric($args[0])) 
		{
			$id = intval($args[0]);
			$game = $this->db->ExecuteGetList(new GetGameCommand($id))[0];

			Responce::Send(200, $game);
			return;
		}
	}


	public function OnPOST(array $args): void 
	{
		$secondEmail = $_POST['secondEmail'];

		if (!isset($secondEmail)) 
		{
			Responce::Send(400, 'To create new game you need to pass 1 arguments: \'secondEmail\' is email of second player.');
			return;
		}

		$loggedEmail = $this->cache->LoggedForEmail();    
		if ($loggedEmail === '') 
		{
			Responce::Send(401, 'You\'re not logged in.');	
			return;
		}

		$secondUser = $this->db->ExecuteGetList(new GetUsersCommand('`email` = \''.$secondEmail.'\''))[0];
		if (!isset($secondUser)) 
		{
			Responce::Send(404, 'No user found with this email.');
			return;
		}

		$firstId = $this->db->ExecuteGetList(new GetUsersCommand('`email` = \''.$loggedEmail.'\''))[0]['id'];
		$secondId = $secondUser['id'];
		$result = $this->db->Execute(new CreateGameCommand($firstId, $secondId));
		if ($result === true) 
		{
			Responce::Send(201, 'Game created.');
		}
		else 
		{
			Responce::Send(409, 'Game not created. Error in db query.');
		}
	}


	public function OnPUT(array $args): void
	{
		if (count($args) === 1 && is_numeric($args[0])) 
		{
			$email = $this->cache->LoggedForEmail();
			if ($email === '') 
			{
				Responce::Send(401, 'You\'re not logged in.');
				return;
			}

			$data = file_get_contents('php://input');
			$data = json_decode($data, true);

			$action = $data['action'];
			if (!isset($action)) 
			{
				Responce::Send(406, 'You need to set property \'action\'.');
				return;
			}

			if (!ChessRules::IsValidAction($action)) 
			{
				Responce::Send(400, 'Invalid action.');
				return;
			}

			$gameInfo = $this->db->ExecuteGetList(new GetGameCommand(intval($args[0])))[0];
			if (!isset($gameInfo)) 
			{
				Responce::Send(404, 'Game with this ID not found.');
				return;
			}

			$history = strval($gameInfo['history']);
			$ended = boolval($gameInfo['ended']);
			$whiteMove = boolval($gameInfo['whiteMove']);
			$game = new ChessGame($gameInfo['state'], $history, $ended, $whiteMove);
			$result = $game->Action($action);

			$code = 200;
			$message = '';
			switch ($result) 
			{
				case GameMessage::OK:
					$dbResult = $this->db->Execute(new UpdateGameCommand($args[0], $game));

					$code = 202;
					$message = 'Ok.';
					break;
				case GameMessage::INVALID_MOVE:
					$code = 400;
					$message = 'Invalid move.';
					break;
				case GameMessage::GAME_ENDED:
					$code = 200;
					$message = 'Game ended.';
					break;
				case GameMessage::WHITE_WIN:
				case GameMessage::BLACK_WIN:
					break;
				default:
					$code = 400;
					$message = 'Unknown error.';
					break;
			}

			Responce::Send($code, $message);
		}
	}


	public function OnDELETE(array $args): void
	{

	}
}