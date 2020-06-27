<?php

require_once "ChessRules.php";


class ChessBoard 
{
    private array $board;


    public function __construct(array $board = []) 
    {
        if ($board === [])
            $this->board = str_split(ChessRules::GetNewBoard());
        else 
            $this->board = $board;
    }


    public function GetAt(int $row, int $col): string 
    {
        return $this->board[$row * 8 + $col];
    }


    public function SetAt(string $value, int $row, int $col): void 
    {
        $this->board[$row * 8 + $col] = $value;
    }
}