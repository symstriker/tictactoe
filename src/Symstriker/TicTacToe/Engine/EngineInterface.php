<?php

namespace Symstriker\TicTacToe\Engine;

use Symstriker\TicTacToe\Entity\Board;
use Symstriker\TicTacToe\Entity\PlayerInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Interface EngineInterface
 * @package Symstriker\TicTacToe\Engine
 * @author Yevhen Straihorodskyi | indestructible86@gmail.com
 */
interface EngineInterface
{
    /**
     * @param Session $session
     * @param PlayerInterface $player1
     * @param PlayerInterface $player2
     * @param Board $board
     * @return mixed
     */
    public static function start(Session $session, PlayerInterface $player1, PlayerInterface $player2, Board $board);

    /**
     * @return bool
     */
    public static function isStarted();

    /**
     * @param int $row
     * @param int $col
     * @return array
     */
    public function makeMove($row = null, $col = null);

    /**
     * Switches the player
     * @return PlayerInterface current player
     */
    public function switchPlayer();

    /**
     * @return int
     */
    public function getGameMode();

    /**
     * @return PlayerInterface
     */
    public function getCurrentPlayer();

    /**
     * @return PlayerInterface
     */
    public function getFirstPlayer();

    /**
     * @return PlayerInterface
     */
    public function getSecondPlayer();

    /**
     * @return Board
     */
    public function getBoard();


} 