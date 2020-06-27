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


    public static function CoordToLetter(int $coord): string 
    {
        switch ($coord) 
        {
            case 0: return 'a';
            case 1: return 'b';
            case 2: return 'c';
            case 3: return 'd';
            case 4: return 'e';
            case 5: return 'f';
            case 6: return 'g';
            case 7: return 'h';
            default: return '';
        }
    }


    public static function MoveNotationToCoords(string $move): array 
    {
        return [intval($move[1]) - 1, ChessRules::LetterToCoord($move[0])];
    }


    public static function CoordsToMoveNotation(array $coords): string 
    {
        return ChessRules::CoordToLetter($coords[1]).($coords[0] + 1);
    }


    public static function InBoard(int $row, int $col): bool 
    {
        return ($row >= 0) && ($row <= 7) && ($col >= 0) && ($col <= 7);
    }


    public static function IsKing(string $letter):   bool { return strtolower($letter) === 'k'; }
    public static function IsQueen(string $letter):  bool { return strtolower($letter) === 'q'; }
    public static function IsRook(string $letter):   bool { return strtolower($letter) === 'r'; }
    public static function IsKnight(string $letter): bool { return strtolower($letter) === 'n'; }
    public static function IsBishop(string $letter): bool { return strtolower($letter) === 'b'; }
    public static function IsPawn(string $letter):   bool { return strtolower($letter) === 'p'; }
    public static function IsEmpty(string $letter):  bool { return $letter === '.'; }


    public static function GetPawnMoves(ChessBoard $board, int $row, int $col): array
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


    public static function GetBishopMoves(ChessBoard $board, int $row, int $col): array 
    {
        $result = [];

        // Left-top
        $checkRow = $row + 1;
        $checkCol = $col - 1;
        while (ChessRules::InBoard($checkRow, $checkCol) && ChessRules::IsEmpty($board->GetAt($checkRow, $checkCol))) 
        {
            $result[] = [$checkRow, $checkCol];
            ++$checkRow;
            --$checkCol;
        }

        // Right-top
        $checkRow = $row + 1;
        $checkCol = $col + 1;
        while (ChessRules::InBoard($checkRow, $checkCol) && ChessRules::IsEmpty($board->GetAt($checkRow, $checkCol)))
        {
            $result[] = [$checkRow, $checkCol];
            ++$checkRow;
            ++$checkCol;
        }

        // Right-bot
        $checkRow = $row - 1;
        $checkCol = $col + 1;
        while (ChessRules::InBoard($checkRow, $checkCol) && ChessRules::IsEmpty($board->GetAt($checkRow, $checkCol)))
        {
            $result[] = [$checkRow, $checkCol];
            --$checkRow;
            ++$checkCol;
        }

        // Left-bot
        $checkRow = $row - 1;
        $checkCol = $col - 1;
        while (ChessRules::InBoard($checkRow, $checkCol) && ChessRules::IsEmpty($board->GetAt($checkRow, $checkCol)))
        {
            $result[] = [$checkRow, $checkCol];
            --$checkRow;
            --$checkCol;
        }

        return $result;
    }


    public static function GetKnightMoves(ChessBoard $board, int $row, int $col): array 
    {
        $result = [];

        // Top
        if (ChessRules::InBoard($row + 2, $col - 1) && ChessRules::IsEmpty($board->GetAt($row + 2, $col - 1)))
            $result[] = [$row + 2, $col - 1];
        if (ChessRules::InBoard($row + 2, $col + 1) && ChessRules::IsEmpty($board->GetAt($row + 2, $col + 1)))
            $result[] = [$row + 2, $col + 1];

        // Right
        if (ChessRules::InBoard($row + 1, $col + 2) && ChessRules::IsEmpty($board->GetAt($row + 1, $col + 2)))
            $result[] = [$row + 1, $col + 2];
        if (ChessRules::InBoard($row - 1, $col + 2) && ChessRules::IsEmpty($board->GetAt($row - 1, $col + 2)))
            $result[] = [$row - 1, $col + 2];

        // Bot
        if (ChessRules::InBoard($row - 2, $col - 1) && ChessRules::IsEmpty($board->GetAt($row - 2, $col - 1)))
            $result[] = [$row - 2, $col - 1];
        if (ChessRules::InBoard($row - 2, $col + 1) && ChessRules::IsEmpty($board->GetAt($row - 2, $col + 1)))
            $result[] = [$row - 2, $col + 1];

        // Left
        if (ChessRules::InBoard($row + 1, $col - 2) && ChessRules::IsEmpty($board->GetAt($row + 1, $col - 2)))
            $result[] = [$row + 1, $col - 2];
        if (ChessRules::InBoard($row - 1, $col - 2) && ChessRules::IsEmpty($board->GetAt($row - 1, $col - 2)))
            $result[] = [$row - 1, $col - 2];

        return $result;
    }


    public static function GetRookMoves(ChessBoard $board, int $row, int $col): array 
    {
        $result = [];

        // Top
        $offset = 1;
        while (ChessRules::InBoard($row + $offset, $col) && ChessRules::IsEmpty($board->GetAt($row + $offset, $col))) 
        {
            $result[] = [$row + $offset, $col];
            ++$offset;
        }

        // Right
        $offset = 1;
        while (ChessRules::InBoard($row, $col + $offset) && ChessRules::IsEmpty($board->GetAt($row, $col + $offset))) 
        {
            $result[] = [$row, $col + $offset];
            ++$offset;
        }

        // Bot
        $offset = -1;
        while (ChessRules::InBoard($row + $offset, $col) && ChessRules::IsEmpty($board->GetAt($row + $offset, $col))) 
        {
            $result[] = [$row + $offset, $col];
            --$offset;
        }

        // Left
        $offset = -1;
        while (ChessRules::InBoard($row, $col + $offset) && ChessRules::IsEmpty($board->GetAt($row, $col + $offset))) 
        {
            $result[] = [$row, $col + $offset];
            --$offset;
        }

        return $result;
    }


    // Move in 'e2-e4'
    public static function IsCorrectMove(ChessBoard $board, string $move): bool
    {
        list($from, $to) = explode('-', $move);
        $toCoords = ChessRules::MoveNotationToCoords($to);

        $piece = $board->GetAt(... ChessRules::MoveNotationToCoords($from));
        if (ChessRules::IsPawn($piece))
            return in_array($toCoords, ChessRules::GetPawnMoves($board, ... ChessRules::MoveNotationToCoords($from)));
        if (ChessRules::IsBishop($piece))
            return in_array($toCoords, ChessRules::GetBishopMoves($board, ... ChessRules::MoveNotationToCoords($from)));
        if (ChessRules::IsKnight($piece))
            return in_array($toCoords, ChessRules::GetKnightMoves($board, ... ChessRules::MoveNotationToCoords($from)));
        if (ChessRules::IsRook($piece))
            return in_array($toCoords, ChessRules::GetRookMoves($board, ... ChessRules::MoveNotationToCoords($from)));

        return false;
    }
}