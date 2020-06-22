<?php

require_once __DIR__."/IDataBaseCommand.php";


class DataBase 
{
    private mysqli $connection;


    public function __construct(string $name, mysqli $connection = NULL)
    {
        if ($connection !== NULL)
            $this->connection = $connection;
        else
            $this->connection = mysqli_connect('127.0.0.1', 'root', 'root', $name);
    }


    public function Execute(IDataBaseCommand $command)
    {
        return mysqli_query($this->connection, $command->GetStringQuery());
    }


    public function ExecuteGetList(IDataBaseCommand $command): array
    {
        $query = $this->Execute($command);
        $result = [];

        if ($query !== false)
            while ($element = mysqli_fetch_assoc($query)) 
            {
                $result[] = $element;
            }

        return $result;
    }
}