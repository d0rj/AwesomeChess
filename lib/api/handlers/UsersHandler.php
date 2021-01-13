<?php

require_once __DIR__."/../IRouteHandler.php";
require_once __DIR__."/../../database/DataBase.php";
require_once __DIR__."/../../database/AuthCache.php";
require_once __DIR__."/../../database/commands/GetUsersCommand.php";
require_once __DIR__."/../../database/commands/CreateUserCommand.php";
require_once __DIR__."/../../database/commands/UpdateUserCommand.php";
require_once __DIR__."../../../Responce.php";


class UsersHandler implements IRouteHandler 
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
		if (count($args) === 0 || $args[0] === '') 
		{
			$usersList = $this->db->ExecuteGetList(new GetUsersCommand());

			Responce::Send(200, $usersList);
			return;
		}

		if ($this->paramsIsGetById($args)) 
		{
			$user = $this->db->ExecuteGetList(new GetUsersCommand('`id` = '.$args[0]))[0];

			if (!isset($user))
			{
				Responce::Send(404, 'No user with id '.$args[0].'.');
			}
			else 
				Responce::Send(200, $user);

			return;
		}

		if (count($args) === 1) 
		{
			$user = $this->db->ExecuteGetList(new GetUsersCommand('`name` = \''.$args[0].'\''))[0];

			if (!isset($user)) 
			{
				Responce::Send(404, 'No user with name \''.$args[0].'\'.')
			}
			else 
				Responce::Send(200, $user);

			return;
		}

		if ($this->paramsIsGetByRating($args)) 
		{
			$users = $this->db->ExecuteGetList(new GetUsersCommand('`rating` = '.$args[1]));

			Responce::Send(200, $users);
			return;
		}
		
		Responce::Send(400, 'Unknown command.');
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
				Responce::Send(201, 'User added.');
			}
			else 
			{
				Responce::Send(409, 'User not added. User already exists or Error in db query.');
			}
		}
		else 
		{
			Responce::Send(400, 'To create new user you need to pass 3 arguments: name, email and password.');
		}
	}


	public function OnPUT(array $args): void 
	{
		$data = file_get_contents('php://input');
		$data = json_decode($data, true);

		$email = $this->cache->LoggedForEmail();
		$newName = $data['newName'];
		$newEmail = $data['newEmail'];
		$newPassword = $data['newPassword'];

		if ($email === '') 
		{
			Responce::Send(401, 'You\'re not logged in.');			
			return;
		}

		if (!isset($newName) && !isset($newEmail) && !isset($newPassword)) 
		{
			Responce::Send(406, 'At least one property must be changed.');			
			return;
		}

		if (!isset($newName)) $newName = '';
		if (!isset($newEmail)) $newEmail = '';
		if (!isset($newPassword)) $newPassword = '';
		$queryResult = $this->db->Execute(new UpdateUserCommand($email, $newName, $newEmail, $newPassword));

		if ($queryResult === true) 
		{
			Responce::Send(202, 'User updated.');
		}
		else 
		{
			Responce::Send(400, 'User not updated. Error in db query.');
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