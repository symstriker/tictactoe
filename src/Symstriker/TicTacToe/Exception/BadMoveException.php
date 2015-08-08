<?php

namespace Symstriker\TicTacToe\Exception;

/**
 * Class BadMoveException
 * @package Symstriker\TicTacToe\Exception
 * @author Yevhen Straihorodskyi | indestructible86@gmail.com
 */
class BadMoveException extends \RuntimeException
{
    /**
     * @param string $message
     * @param int $code
     */
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }
} 