<?php

require_once __DIR__."/../IDataBaseCommand.php";


class UpdateUserCommand implements IDataBaseCommand 
{
    private string $name = '';
    private string $newName = '';
    private string $newEmail = '';
    private string $newPassword = '';


    public function __construct(string $name, string $newName = '', string $newEmail = '', string $newPassword = '') 
    {
        $this->name = $name;
        $this->newName = $newName;
        $this->newEmail = $newEmail;
        $this->newPassword = $newPassword;
    }


    public function GetStringQuery(): string 
    {
        if ($this->newName === '' && $this->newEmail === '' && $this->newPassword === '') 
        {
            die('Error in updating user. Need to specify changes.');
        }

        $result = 'UPDATE `users` SET ';
        $needComma = false;
        
        if ($this->newName !== '') 
        {
            $result = $result.'`name` = \''.$this->newName.'\'';
            $needComma = true;
        }

        if ($needComma)
            $result = $result.', ';

        if ($this->newEmail !== '') 
        {
            $result = $result.'`email` = \''.$this->newEmail.'\'';
            $needComma = true;
        }

        if ($needComma)
            $result = $result.', ';

        if ($this->newPassword !== '')
            $result = $result.'`password` = \''.$this->newPassword.'\' ';

        $result = $result.'WHERE `users`.`name` = \''.$this->name.'\'';
        echo $result;

        return $result;
    }
}