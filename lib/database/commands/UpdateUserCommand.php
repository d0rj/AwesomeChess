<?php

require_once __DIR__."/../IDataBaseCommand.php";


class UpdateUserCommand implements IDataBaseCommand 
{
    private string $email = '';
    private string $newName = '';
    private string $newEmail = '';
    private string $newPassword = '';


    public function __construct(string $email, string $newName = '', string $newEmail = '', string $newPassword = '') 
    {
        $this->email = $email;
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

        
        if ($this->newEmail !== '') 
        {
            if ($needComma)
                $result = $result.', ';

            $result = $result.'`email` = \''.$this->newEmail.'\'';
            $needComma = true;
        }

        
        if ($this->newPassword !== '') 
        {
            if ($needComma)
                $result = $result.', ';

            $result = $result.'`password` = \''.$this->newPassword.'\' ';
        }

        $result = $result.'WHERE `users`.`email` = \''.$this->email.'\'';

        return $result;
    }
}