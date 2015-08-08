<?php

namespace Symstriker\TicTacToe\Entity;

/**
 * Class Line
 * @package Symstriker\TicTacToe\Entity
 * @author Yevhen Straihorodskyi | indestructible86@gmail.com
 */
class Line
{

    public $row1, $col1, $row2, $col2, $row3, $col3;


    /**
     * Constructor
     * @param int $row1
     * @param int $col1
     * @param int $row2
     * @param int $col2
     * @param int $row3
     * @param int $col3
     * @throws \InvalidArgumentException
     */
    public function __construct($row1, $col1, $row2, $col2, $row3, $col3)
    {
        if (!is_int($row1)) {
            throw new \InvalidArgumentException(sprintf('Parameter 1 for the method %s must be an integer %s given', __METHOD__, gettype($row1)));
        }

        if (!is_int($col1)) {
            throw new \InvalidArgumentException(sprintf('Parameter 2 for the method %s must be an integer %s given', __METHOD__, gettype($col1)));
        }

        if (!is_int($row2)) {
            throw new \InvalidArgumentException(sprintf('Parameter 3 for the method %s must be an integer %s given', __METHOD__, gettype($row2)));
        }

        if (!is_int($col2)) {
            throw new \InvalidArgumentException(sprintf('Parameter 4 for the method %s must be an integer %s given', __METHOD__, gettype($col2)));
        }

        if (!is_int($row3)) {
            throw new \InvalidArgumentException(sprintf('Parameter 5 for the method %s must be an integer %s given', __METHOD__, gettype($row3)));
        }

        if (!is_int($col3)) {
            throw new \InvalidArgumentException(sprintf('Parameter 6 for the method %s must be an integer %s given', __METHOD__, gettype($col3)));
        }

        $this->row1 = $row1;
        $this->row2 = $row2;
        $this->row3 = $row3;
        $this->col1 = $col1;
        $this->col2 = $col2;
        $this->col3 = $col3;

    }

    /**
     * @param $name
     * @return int
     */
    public function __get($name)
    {
        return $this->$name;
    }
}