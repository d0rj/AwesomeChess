<?php

require_once __DIR__."/../IDataBaseCommand.php";
require_once __DIR__."/../../chess/ChessRules.php";


class CreateGameCommand implements IDataBaseCommand 
{
    private int $firstId;
    private int $secondId;


    public function __construct(int $firstId, int $secondId) 
    {
        $this->firstId = $firstId;
        $this->secondId = $secondId;
    }


    public function GetStringQuery(): string 
    {
        return 'INSERT INTO `games` (`id`, `state`, `history`, `whiteId`, `blackId`, `ended`, `whiteMove`) 
        VALUES (NULL, \''.ChessRules::GetNewBoard().'\', \'\', \''.$this->firstId.'\', \''.$this->secondId.'\', \'0\', \'1\')';
    }
}