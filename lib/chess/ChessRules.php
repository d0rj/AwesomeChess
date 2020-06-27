<?php

require_once "ChessBoard.php";


class ChessRules 
{
    public static function GetNewBoard(): string
    {
        return 'RNBQKBNRPPPPPPPP................................pppppppprnbqkbnr';
    }


    public static function IsWhiteChessman(string $piece): bool 
    {
        return ctype_upper($piece);
    }


    public static function IsBlackChessman(string $piece): bool 
    {
        return !ChessRules::IsWhiteChessman($piece);
    }


    public static function LetterToCoord(string $letter): int 
    {
        switch (strtolower($letter)) 
        {
            case 'a': return 0;
            case 'b': return 1;
            case 'c': return 2;
            case 'd': return 3;
            case 'e': return 4;
            case 'f': return 5;
            case 'g': return 6;
            case 'h': return 7;
            default: return -1;
        }
    }


    public static function MoveNotationToCoords(string $move): array 
    {
        return [intval($move[1]) - 1, ChessRules::LetterToCoord($move[0])];
    }


    public static function IsKing(string $letter):   bool { return strtolower($letter) === 'k'; }
    public static function IsQueen(string $letter):  bool { return strtolower($letter) === 'q'; }
    public static function IsRook(string $letter):   bool { return strtolower($letter) === 'r'; }
    public static function IsKnight(string $letter): bool { return strtolower($letter) === 'n'; }
    public static function IsBishop(string $letter): bool { return strtolower($letter) === 'b'; }
    public static function IsPawn(string $letter):   bool { return strtolower($letter) === 'p'; }
    public static function IsEmpty(string $letter):  bool { return $letter === '.'; }


    public static function GetPawnMoves(ChessBoard $board, int $row, int $col) 
    {
        $result = [];

        if (ChessRules::IsWhiteChessman($board->GetAt($row, $col))) 
        {
            if (ChessRules::IsEmpty($board->GetAt($row + 1, $col))) 
                $result[] = [$row + 1, $col];
            if ($row === 1 && ChessRules::IsEmpty($board->GetAt($row + 2, $col)))
                $result[] = [$row + 2, $col];
        }
        else 
        {
            if (ChessRules::IsEmpty($board->GetAt($row - 1, $col)))
                $result[] = [$row - 1, $col];
            if ($row === 6 && ChessRules::IsEmpty($board->GetAt($row - 2, $col)))
                $result[] = [$row - 2, $col];
        }

        return $result;
    }


    // Move in 'e2-e4'
    public static function IsCorrectMove(ChessBoard $board, string $move): bool
    {
        $coords = explode('-', $move);
        
        if (ChessRules::IsPawn($board->GetAt(... ChessRules::MoveNotationToCoords($coords[0]))))
            return in_array(ChessRules::MoveNotationToCoords($coords[1]), ChessRules::GetPawnMoves($board, ... ChessRules::MoveNotationToCoords($coords[0])));
    }
}