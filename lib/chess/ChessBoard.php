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


	public function Move(array $from, array $to): void 
	{
		$piece = $this->GetAt($from[0], $from[1]);
		$this->SetAt('.', $from[0], $from[1]);
		$this->SetAt($piece, $to[0], $to[1]);
	}


	public function ToString(): string 
	{
		return implode($this->board);
	}


	public function FromString(string $board): void 
	{
		$this->board = str_split($board);
	}
}