<?php

require_once "ChessBoard.php";
require_once "ChessRules.php";
require_once "GameMessage.php";


class ChessGame 
{
    private ChessBoard $board;
    private array $history;
    private bool $ended;
    private bool $whiteMove;


    public function __construct(string $board = '', string $history = '', bool $ended = false, bool $whiteMove = true) 
    {
        if ($board === '') $this->board = new ChessBoard();
        else $this->board = str_split($board);

        if ($history === '') $this->history = [];
        else $this->history = explode(';', $history);

        $this->ended = $ended;
        $this->whiteMove = $whiteMove;
    }


    public function Action(string $action): string 
    {
        if ($this->ended)
            return GameMessage::GAME_ENDED;

        if ($action === '0-0' || $action === '0-0-0')
            // TODO: handle castling
            return GameMessage::ERROR;
        
        $fromCoords = ChessRules::MoveNotationToCoords(substr($action, 0, 2));

        // If trying to move other color
        if (ChessRules::IsWhiteChessman($this->board->GetAt($fromCoords[0], $fromCoords[1])) !== $this->whiteMove)
            return GameMessage::INVALID_MOVE;

        if (!ChessRules::IsCorrectAction($this->board, $action))
            return GameMessage::INVALID_MOVE;

        $toCoords = ChessRules::MoveNotationToCoords(substr($action, 3, 2));
        $this->board->Move($fromCoords, $toCoords);
        
        if (ChessRules::IsGameEnded($this->board)) 
        {
            $this->ended = true;
            
            if ($this->whiteMove)
                return GameMessage::WHITE_WIN;
            else 
                return GameMessage::BLACK_WIN;
        }

        $this->history[] = $action;
        $this->whiteMove = !$this->whiteMove;

        return GameMessage::OK;
    }


    public function GetStringHistory(): string 
    {
        return implode(';', $this->history);
    }


    public function GetStringBoard(): string 
    {
        return $this->board->ToString();
    }


    public function GetEnded(): bool 
    {
        return $this->ended;
    }


    public function GetIsWhiteMove(): bool 
    {
        return $this->whiteMove;
    }
}