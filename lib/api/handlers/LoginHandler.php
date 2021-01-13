<?php 

require_once __DIR__."/../IRouteHandler.php";
require_once __DIR__."/../../database/DataBase.php";
require_once __DIR__."/../../database/commands/GetUsersCommand.php";
require_once __DIR__."/../../database/AuthCache.php";


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
			header("HTTP/1.0 401 Unauthorized");
			
			echo json_encode([
				'errors' => 0,
				'message' => 'You\'re not logged in.'
			]);

			return;
		}

		echo json_encode([
			'errors' => 0,
			'message' => 'Logged for email: \''.$loggedEmail.'\''
		]);
	}


	public function OnPOST(array $args): void
	{
		$email = $_POST['email'];
		$password = $_POST['password'];

		if (!isset($email) || !isset($password)) 
		{
			header("HTTP/1.0 400 Bad Reqest");

			echo json_encode([
				'errors' => 1,
				'message' => 'Email and password required for login.'
			]);

			return;
		}

		if ($this->cache->LoggedForEmail() === $email) 
		{
			echo json_encode([
				'errors' => 0,
				'message' => 'You\'re already logged in.'
			]);

			return;
		}

		$users = $this->db->ExecuteGetList(new GetUsersCommand('`email` = \''.$email.'\' AND `password` = \''.$password.'\''));

		if (isset($users) && count($users) === 1) 
		{
			$this->cache->SetLoggedIn($email);

			echo json_encode([
				'errors' => 0,
				'message' => 'Login successfull.'
			]);

			return;
		}

		header("HTTP/1.0 400 Bad Reqest");

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