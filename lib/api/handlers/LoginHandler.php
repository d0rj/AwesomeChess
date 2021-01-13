<?php 

require_once __DIR__."/../IRouteHandler.php";
require_once __DIR__."/../../database/DataBase.php";
require_once __DIR__."/../../database/commands/GetUsersCommand.php";
require_once __DIR__."/../../database/AuthCache.php";
require_once __DIR__."../../../Responce.php";


class LoginHandler implements IRouteHandler 
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
		$loggedEmail = $this->cache->LoggedForEmail();

		if ($loggedEmail === '') 
		{
			Responce::Send(401, 'You\'re not logged in.');
			return;
		}

		Responce::Send(200, 'Logged for email: \''.$loggedEmail.'\'.');
	}


	public function OnPOST(array $args): void
	{
		$email = $_POST['email'];
		$password = $_POST['password'];

		if (!isset($email) || !isset($password)) 
		{
			Responce::Send(400, 'Email and password required for login.');
			return;
		}

		if ($this->cache->LoggedForEmail() === $email) 
		{
			Responce::Send(200, 'You\'re already logged in.');
			return;
		}

		$users = $this->db->ExecuteGetList(new GetUsersCommand('`email` = \''.$email.'\' AND `password` = \''.$password.'\''));

		if (isset($users) && count($users) === 1) 
		{
			$this->cache->SetLoggedIn($email);
			Responce::Send(200, 'Login successfull.');
			return;
		}

		Responce::Send(400, 'No users with this email or password.');
	}


	public function OnPUT(array $args): void
	{

	}


	public function OnDELETE(array $args): void
	{

	}
}