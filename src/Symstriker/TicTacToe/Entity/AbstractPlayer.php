<?php

namespace Symstriker\TicTacToe\Entity;

/**
 * Class AbstractPlayer
 * @package Symstriker\TicTacToe\Entity
 * @author Yevhen Straihorodskyi | indestructible86@gmail.com
 */
abstract class AbstractPlayer implements PlayerInterface
{
    /**
     * 1 for Xs
     */
    const SIGN_X = 1;

    /**
     * 0 for Os
     */
    const SIGN_O = 0;

    /**
     * @var int
     */
    protected $score = 0;

    /**
     * @var int player game sign
     */
    protected $sign;

    /**
     * @var int player type
     */
    protected $type;

    /**
     * @var Board game board with current state
     */
    protected $board;

    /**
     * @param Board $board
     * @param int $sign
     */
    public function __construct(Board $board, $sign)
    {
        $this->board = $board;
        $this->sign = $sign;
    }


    /**
     * Returns the sign of a player, e.g. 1(X) | 0(O)
     * @return int
     */
    public function getGameSign()
    {
        return $this->sign;
    }

    /**
     * Player scores
     * @return int
     */
    public function getPlayerScore()
    {
        return $this->score;
    }

    /**
     * @return Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * @return bool
     */
    public function isFirstMovePlayer()
    {
        return $this->sign ==  self::SIGN_X;
    }

    /**
     * @param Board $board
     * @return void
     */
    public function setBoard(Board $board)
    {
        $this->board = $board;
    }

}