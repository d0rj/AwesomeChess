<?php

require_once __DIR__."/../IDataBaseCommand.php";
require_once __DIR__."/../../chess/ChessGame.php";


class UpdateGameCommand implements IDataBaseCommand 
{
	private int $id;
	private ChessGame $game;


	public function __construct(int $id, ChessGame $game) 
	{
		$this->id = $id;
		$this->game = $game;
	}


	public function GetStringQuery(): string 
	{
		return 'UPDATE `games` SET `state` = \''.$this->game->GetStringBoard().'\', `history` = \''
			.$this->game->GetStringHistory().'\', `ended` = '
			.intval($this->game->GetEnded()).', `whiteMove` = '
			.intval($this->game->GetIsWhiteMove()).' WHERE `games`.`id` = '.$this->id;
	}
}