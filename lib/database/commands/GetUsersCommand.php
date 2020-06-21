<?php

require_once __DIR__."/../IDataBaseCommand.php";


class GetUsersCommand implements IDataBaseCommand 
{
    private string $command = "SELECT `id`, `name`, `email`, `rating` FROM `users`";


    public function __construct(string $where = NULL) 
    {
        if ($where !== NULL)
            $this->command = $this->command." WHERE ".$where;
    }


    public function GetStringQuery(): string 
    {
        return $this->command;
    }
}