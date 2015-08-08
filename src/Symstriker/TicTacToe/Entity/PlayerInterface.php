<?php

namespace Symstriker\TicTacToe\Entity;

/**
 * Interface PlayerInterface
 * @package Symstriker\TicTacToe\Model
 * @author Yevhen Straihorodskyi | indestructible86@gmail.com
 */
interface PlayerInterface
{

	/**
	 * Player type, e.g. real player | AI
	 * @return int
	 */
	public function getType();

	/**
	 * Returns the sign of a player, e.g. Cross | Zero
	 * @return int
	 */
	public function getGameSign();

	/**
	 * Returns player name
	 * @return string
	 */
	public function getName();

    /**
     * @return int
     */
    public function getPlayerScore();

    /**
     * @param int $row
     * @param int $col
     * @return mixed
     */
    public function makeMove($row = null, $col = null);

    /**
     * @return bool
     */
    public function isFirstMovePlayer();

    /**
     * @param Board $board
     * @return void
     */
    public function setBoard(Board $board);
} 