<?php 

require_once __DIR__."/../IDataBaseCommand.php";


class AvailableGamesCommand implements IDataBaseCommand 
{
    private string $email;


    public function __construct(string $email) 
    {
        $this->email = $email;
    }


    public function GetStringQuery(): string 
    {
        $innerQuery = '(SELECT `users`.`id` AS user_id FROM `users` WHERE `users`.`email` = \''.$this->email.'\')';
        return 'SELECT * FROM `games` WHERE `whiteId` = '.$innerQuery.' OR `blackId` = '.$innerQuery;
    }
}