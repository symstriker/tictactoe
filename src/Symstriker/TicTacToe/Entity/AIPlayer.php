<?php

namespace Symstriker\TicTacToe\Entity;

/**
 * Class AI
 * @package Symstriker\TicTacToe\Model
 * @author Yevhen Straihorodskyi | indestructible86@gmail.com
 */
final class AIPlayer extends AbstractPlayer
{

    const PHP_INT_MAX = PHP_INT_MAX;
    const PHP_INT_MIN =  -2147483648;

    /**
     * @var array winner patterns for possible win lines
     */
    private static $winnerPatterns = [
        0b111000000, 0b000111000, 0b000000111,
        0b100100100, 0b010010010, 0b001001001,
        0b100010001, 0b001010100
    ];

    /**
     * @param Board $board
     * @param int $sign
     * @throws \InvalidArgumentException
     */
    public function __construct(Board $board, $sign)
    {
        if (!is_int($sign)) {
            throw new \InvalidArgumentException(sprintf('Parameter 2 for the method %s must be an integer %s given', __METHOD__, gettype($sign)));
        }

        parent::__construct($board, $sign);
    }

    /**
     * Player type, e.g. real player | AI
     * @return int
     */
    public function getType()
    {
        return 0;
    }

    /**
     * Returns player name
     * @return string
     */
    public function getName()
    {
        return 'AI';
    }

    /**
     * @param int $row
     * @param int $col
     * @return array|mixed
     */
    public function makeMove($row = null, $col= null)
    {
        $result = $this->minimax(2, $this->getGameSign(), self::PHP_INT_MIN, self::PHP_INT_MAX);
        return array_slice($result, 1);
    }

    /**
     * Using minimax algorithm with alpha-beta pruning to find the best move for AI
     * @See https://en.wikipedia.org/wiki/Minimax
     * @param int $depth
     * @param int $player player 0 (for Os) or 1 (for Xs)
     * @param int $alpha
     * @param int $beta
     * @return array of score, row and column are better suits for player
     */
    private function minimax($depth, $player, $alpha, $beta)
    {
        $possibleMoves = $this->generateMoves();
        $opponent = $this->getGameSign() == self::SIGN_X ? self::SIGN_O : self::SIGN_X;
        $score = 0;
        $bestRow = -1;
        $bestCol = -1;

        if (empty($possibleMoves) || $depth == 0) {
            $score = $this->evaluate();
            return [$score, $bestRow, $bestCol];
        } else {
            foreach ($possibleMoves as $move) {
                $this->board[$move[0]][$move[1]] = $this->getGameSign();
                if ($player == $this->getGameSign()) {
                    $score = $this->minimax($depth - 1, $opponent, $alpha, $beta)[0];
                    if ($score > $alpha) {
                        $alpha = $score;
                        $bestRow = $move[0];
                        $bestCol = $move[1];
                    }
                } else {
                    $score = $this->minimax($depth - 1, $this->getGameSign(), $alpha, $beta)[0];
                    if ($score < $beta) {
                        $beta = $score;
                        $bestRow = $move[0];
                        $bestCol = $move[1];
                    }
                }
                unset($this->board[$move[0]][$move[1]]);
                if ($alpha >= $beta) {
                    break;
                }
            }
            return [($player == $this->getGameSign()) ? $alpha : $beta, $bestRow, $bestCol];
        }
    }

    /**
     * @return Board for current iteration of minimax
     */
    private function generateMoves()
    {
        $board = new Board(array());


        if ($this->hasWon(self::SIGN_X) || $this->hasWon(self::SIGN_O)) {
            return $board;
        }

        for ($row = 0; $row < 3; ++$row) {
            for ($col = 0; $col < 3; ++$col) {
                if (!isset($this->board[$row][$col])) {
                    $board[] = [$row, $col];
                }
            }
        }
        return $board;
    }

    /**
     * @param int $sign self::SIGN_X | self::SIGN_Y
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function hasWon($sign)
    {

        if (!is_int($sign)) {
            throw new \InvalidArgumentException(sprintf('Parameter 2 for the method %s must be an integer %s given', __METHOD__, gettype($sign)));
        }
        $pattern = 0b000000000;
        for ($row = 0; $row < 3; ++$row) {
            for ($col = 0; $col < 3; ++$col) {
                if (isset($this->board[$row][$col]) && $this->board[$row][$col] === $sign) {
                    $pattern |= (1 << ($row * 3 + $col));
                }
            }
        }
        foreach (self::$winnerPatterns as $winningPattern) {
            if (($pattern & $winningPattern) == $winningPattern) return true;
        }
        return false;
    }


    /**
     * Heuristic method to check possible winning lines
     * @return int
     */
    private function evaluate()
    {
        $score = 0;
        $score += $this->evaluateLine(new Line(0, 0, 0, 1, 0, 2));
        $score += $this->evaluateLine(new Line(1, 0, 1, 1, 1, 2));
        $score += $this->evaluateLine(new Line(2, 0, 2, 1, 2, 2));
        $score += $this->evaluateLine(new Line(0, 0, 1, 0, 2, 0));
        $score += $this->evaluateLine(new Line(0, 1, 1, 1, 2, 1));
        $score += $this->evaluateLine(new Line(0, 2, 1, 2, 2, 2));
        $score += $this->evaluateLine(new Line(0, 0, 1, 1, 2, 2));
        $score += $this->evaluateLine(new Line(0, 2, 1, 1, 2, 0));
        return $score;
    }

    /**
     * Heuristic algorithm to check one line
     * @param Line $line
     * @return int
     * @throws \InvalidArgumentException
     */
    private function evaluateLine(Line $line)
    {

        $score = 0;
        $cells = $this->board;
        $opponent = $this->getGameSign() == self::SIGN_X ? self::SIGN_O : self::SIGN_X;

        if ($this->isValidCell($line->row1, $line->col1, $cells, $this->getGameSign())) {
            $score = 1;
        } elseif ($this->isValidCell($line->row1, $line->col1, $cells, $opponent)) {
            $score = -1;
        }

        if ($this->isValidCell($line->row2, $line->col2, $cells, $this->getGameSign())) {
            if ($score == 1) {
                $score = 10;
            } elseif ($score == -1) {
                return 0;
            } else {
                $score = 1;
            }
        } elseif ($this->isValidCell($line->row2, $line->col2, $cells, $opponent)) {
            if ($score == -1) {
                $score = -10;
            } elseif ($score == 1) {
                return 0;
            } else {
                $score = -1;
            }
        }

        if ($this->isValidCell($line->row3, $line->col3, $cells, $this->getGameSign())) {
            if ($score > 0) {
                $score *= 10;
            } else if ($score < 0) {
                return 0;
            } else {
                $score = 1;
            }
        } else if ($this->isValidCell($line->row3, $line->col3, $cells, $opponent)) {
            if ($score < 0) {
                $score *= 10;
            } else if ($score > 1) {
                return 0;
            } else {
                $score = -1;
            }
        }
        return $score;
    }

    /**
     * @param int $row
     * @param int $col
     * @param Board $board
     * @param int $sign
     * @return bool
     */
    private function isValidCell($row, $col, Board $board, $sign)
    {
        return (isset($board[$row][$col]) && $board[$row][$col] == $sign);
    }


}