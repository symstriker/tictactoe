<?php

namespace Symstriker\TicTacToe\Entity;
use Symstriker\TicTacToe\Exception\BadMoveException;

/**
 * Class Player
 * @package Symstriker\TicTacToe\Model
 * @author Yevhen Straihorodskyi | indestructible86@gmail.com
 */
final class Player extends AbstractPlayer
{

    /**
     * @var string $name
     */
    private $name;

    /**
     * @param Board $board
     * @param string $name
     * @param int $sign
     * @throws \InvalidArgumentException
     */
    public function __construct(Board $board, $sign, $name)
    {
        if (!is_int($sign)) {
            throw new \InvalidArgumentException(sprintf('Parameter 2 for the method %s must be an integer %s given', __METHOD__, gettype($sign)));
        }

        if (!is_string($name) || !$name) {
            throw new \InvalidArgumentException(sprintf('Parameter 3 for the method %s must be an integer %s given', __METHOD__, gettype($name)));
        }
        parent::__construct($board, $sign);
        $this->name = $name;
    }


    /**
	 * Player type, e.g. real player | AI
	 * @return int
	 */
	public function getType()
	{
		return 1;
	}

	/**
	 * Returns player name
	 * @return string
	 */
	public function getName()
    {
		return $this->name;
	}

    /**
     * Make player move by given cell
     * @param int $row
     * @param int $col
     * @return array
     */
    public function makeMove($row = null, $col= null)
    {
        if (!is_int($row)) {
            throw new \InvalidArgumentException(sprintf('Parameter 1 for the method %s must be an integer %s given', __METHOD__, gettype($row)));
        }

        if (!is_int($col)) {
            throw new \InvalidArgumentException(sprintf('Parameter 2 for the method %s must be an integer %s given', __METHOD__, gettype($col)));
        }

        if (isset($this->board[$row][$col])) {
            throw new BadMoveException(sprintf('Move with coordinates %d : %d already have taken.', $row, $col));
        }

        $this->board[$row][$col] = $this->getGameSign();

        return [$row, $col];
    }

}