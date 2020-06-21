<?php 

require_once __DIR__."/../IDataBaseCommand.php";


class CreateUserCommand implements IDataBaseCommand 
{
    public string $name = '';
    public string $email = '';
    public string $password = '';


    public function __construct(string $name, string $email, string $password) 
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }


    public function GetStringQuery(): string 
    {
        if ($this->name !== '' && $this->email !== '' && $this->password !== '') 
        {
            $result = 'INSERT INTO `users` (`id`, `name`, `email`, `password`, `rating`) VALUES (NULL, \''.$this->name.'\', \''.$this->email.'\', \''.$this->password.'\', 1000)';

            $this->name = '';
            $this->email = '';
            $this->password = '';

            return $result;
        }
        else 
        {
            die("Error in create user query construction.");
        }
    }
}