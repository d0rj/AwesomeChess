<?php


class AuthCache 
{
    private $memcache;
    private int $sessionTime;


    public function __construct(int $sessionTime = 3600) 
    {
        $this->memcache = new Memcache();
        $this->memcache->connect('127.0.0.1', 11211);

        $this->sessionTime = $sessionTime;
    }


    public function HasAuthKey(): bool 
    {
        return isset($_COOKIE['sessionId']);
    }


    public function LoggedForEmail(): string 
    {
        if (!$this->HasAuthKey())
            return '';

        $sessionId = $_COOKIE['sessionId'];
        $email = $this->memcache->get($sessionId);

        if ($email === false)
            return '';

        return $email;
    }


    public function SetLoggedIn(string $email): void 
    {
        $sessionId = uniqid('', true);

        $this->memcache->set($sessionId, $email, 0, $sessionTime);

        setcookie('sessionId', $sessionId, time() + 3600);
    }
}