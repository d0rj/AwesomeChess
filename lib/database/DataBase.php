<?php

require_once __DIR__."/IDataBaseCommand.php";


class DataBase 
{
    private mysqli $connection;


    public function __construct($connection = NULL)
    {
        if ($connection !== NULL)
            $this->connection = $connection;
        else
            $this->connection = mysqli_connect("127.0.0.1", "root", "root", "chess_db");
    }


    public function Execute(IDataBaseCommand $command): mysqli_result
    {
        return mysqli_query($this->connection, $command->GetStringQuery());
    }


    public function ExecuteGetList(IDataBaseCommand $command): array
    {
        $query = $this->Execute($command);
        $result = [];

        while ($element = mysqli_fetch_assoc($query)) 
        {
            $result[] = $element;
        }

        return $result;
    }
}