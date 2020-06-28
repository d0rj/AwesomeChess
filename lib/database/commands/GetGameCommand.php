<?php

require_once __DIR__."/../IDataBaseCommand.php";


class GetGameCommand implements IDataBaseCommand 
{
    private int $id;


    public function __construct(int $id) 
    {
        $this->id = $id;
    }


    public function GetStringQuery(): string 
    {
        return 'SELECT `id`, `state`, `history`, `ended`, `whiteMove` FROM `games` WHERE `id` = '.$this->id;
    }
}