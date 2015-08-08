<?php

namespace Symstriker\TicTacToe\Engine;

use Symstriker\TicTacToe\Entity\AbstractPlayer;
use Symstriker\TicTacToe\Entity\Board;
use Symstriker\TicTacToe\Entity\AIPlayer;
use Symstriker\TicTacToe\Entity\PlayerInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class GameEngine
 * @package Symstriker\TicTacToe\Engine
 * @author Yevhen Straihorodskyi | indestructible86@gmail.com
 */
class GameEngine implements EngineInterface
{

    const GAME_MODE_AI = 0;
    const GAME_MODE_PLAYER = 1;

    /**
     * @var PlayerInterface
     */
    private $currentPlayer;

    /**
     * @var PlayerInterface
     */
    private $firstPlayer;

    /**
     * @var PlayerInterface
     */
    private $secondPlayer;

    /**
     * @var int
     */
    private $gameMode;

    /**
     * @var EngineInterface
     */
    private static $gameEngine;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var Board
     */
    private $board;


    /**
     * @param Session $session
     * @param PlayerInterface $player1
     * @param PlayerInterface $player2
     * @param Board $board
     */
    private function __construct(Session $session, PlayerInterface $player1, PlayerInterface $player2, Board $board)
    {
        $this->session = $session;
        if ($player2 instanceof AIPlayer) {
            $this->gameMode = self::GAME_MODE_AI;
        }
        $this->firstPlayer = $player1;
        $this->secondPlayer = $player2;
        $this->board = $board;

        $this->currentPlayer = $this->firstPlayer;
        if (
            !$this->board->count()
            && $this->firstPlayer->getGameSign() === AbstractPlayer::SIGN_O
        ) {
            $this->currentPlayer = $this->secondPlayer;
            $move = $this->currentPlayer->makeMove();
            $this->updateBoard($move[0], $move[1]);
            $this->switchPlayer();
        }
    }

    /**
     * Start engine with given players or from session if players is not defined
     * @param Session $session
     * @param PlayerInterface $player1
     * @param PlayerInterface $player2
     * @param Board $board
     * @return EngineInterface|GameEngine|mixed
     * @throws \InvalidArgumentException if no players is defined in both arguments and session
     */
    public static function start(Session $session, PlayerInterface $player1 = null, PlayerInterface $player2 = null, Board $board = null)
    {
        if (
            (!$player1 || !$player2)
            && (!$session->has('player1') || !$session->has('player2'))
        ) {
            throw new \InvalidArgumentException('You must specify players to start the engine');
        }
        //restart the game
        if ($player1 && $player2) {
            $session->set('player1', $player1);
            $session->set('player2', $player2);
            $board = new Board(array());
            $session->set('board', $board);
            self::$gameEngine = new GameEngine($session, $player1, $player2, $board);
            return self::$gameEngine;
        }

        if (self::$gameEngine) {
            return self::$gameEngine;
        }

        if (
            !$player1 || !$player2
            && ($session->has('player1') && $session->has('player2'))
        ) {
            $player1 = $session->get('player1');
            $player2 = $session->get('player2');
            $board = $session->get('board');
            $player1->setBoard($board);
            $player2->setBoard($board);
        }

        if (!$board) {
            $board = new Board(array());
        }

        self::$gameEngine = new GameEngine($session, $player1, $player2, $board);
        return self::$gameEngine;
    }

    /**
     * Make a move for exact player
     * @param int $row
     * @param int $col
     * @return array
     */
    public function makeMove($row = null, $col = null)
    {
        $playerMove = $this->getCurrentPlayer()->makeMove((int) $row, (int)$col);
        $this->updateBoard($playerMove[0],$playerMove[1]);
        $aiMove = $this->switchPlayer()->makeMove();
        $this->updateBoard($aiMove[0],$aiMove[1]);
        $this->switchPlayer();
        return $aiMove;
    }

    /**
     * Switches the player
     * @return PlayerInterface current player
     */
    public function switchPlayer()
    {
        $this->currentPlayer = $this->currentPlayer->getName() == $this->firstPlayer->getName()
            ? $this->secondPlayer
            : $this->firstPlayer;
        return $this->currentPlayer;
    }

    /**
     * @return PlayerInterface
     */
    public function getCurrentPlayer()
    {
        return $this->currentPlayer;
    }

    /**
     * @return int
     */
    public function getGameMode()
    {
        return $this->gameMode;
    }

    /**
     * @return bool
     */
    public static function isStarted()
    {
        return self::$gameEngine ? true : false;
    }

    /**
     * @return PlayerInterface
     */
    public function getFirstPlayer()
    {
        return $this->firstPlayer;
    }

    /**
     * @return PlayerInterface
     */
    public function getSecondPlayer()
    {
        return $this->secondPlayer;
    }

    /**
     * @return PlayerInterface|null
     */
    public function whoWon()
    {
        if ($this->secondPlayer->hasWon($this->firstPlayer->getGameSign())) {
            return $this->firstPlayer;
        }
        if ($this->secondPlayer->hasWon($this->secondPlayer->getGameSign())) {
            return $this->secondPlayer;
        }
        return null;
    }

    /**
     * @return Board
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Updates board in session
     * @param int $row
     * @param int $col
     */
    private function updateBoard($row, $col)
    {
        if (!is_int($row)) {
           throw new \InvalidArgumentException(sprintf('First parameter passed for method %s must be a type of integer %s given', __METHOD__, gettype($row)));
        }

        if (!is_int($col)) {
            throw new \InvalidArgumentException(sprintf('Second parameter passed for method %s must be a type of integer %s given', __METHOD__, gettype($col)));
        }

        $this->board[$row][$col] = $this->currentPlayer->getGameSign();
        $this->session->set('board', $this->board);
    }

}