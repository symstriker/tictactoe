<?php

namespace Test\Symstriker\TicTacToe;

use Symstriker\TicTacToe\Entity\AIPlayer;
use Symstriker\TicTacToe\Entity\Board;
use PHPUnit_Framework_TestCase;

/**
 * Class AIPlayerTest
 * @package Test\Symstriker\TicTacToe
 */
class AIPlayerTest extends PHPUnit_Framework_TestCase
{
    public function testBlockerMoveWithPossiblePlayerWin_onAlternativeDiagonal()
    {
        $board = new Board(array(
            [0=>0, 2=>1],
            [1=>1],
            []
        ));
        $aiPlayer = new AIPlayer($board, 0);
        $move = $aiPlayer->makeMove();
        $this->assertTrue($move[0] == 2);
        $this->assertTrue($move[1] == 0);
    }

    public function testBlockerMoveWithPossiblePlayerWin_onDiagonal()
    {
        $board = new Board(array(
            [0=>1, 2=>0],
            [1=>1],
            []
        ));
        $aiPlayer = new AIPlayer($board, 0);
        $move = $aiPlayer->makeMove();
        $this->assertTrue($move[0] == 2);
        $this->assertTrue($move[1] == 2);
    }

    public function testBlockerMoveWithPossiblePlayerWin_onFirstRow()
    {
        $board = new Board(array(
            [0=>1, 2=>1],
            [1=>0],
            []
        ));
        $aiPlayer = new AIPlayer($board, 0);
        $move = $aiPlayer->makeMove();
        $this->assertTrue($move[0] == 0);
        $this->assertTrue($move[1] == 1);
    }

    public function testWinnerMove_onFirstCol_LastRow()
    {
        $board = new Board(array(
            0=>[0=>0],
            1=>[0=>0, 1=>1, 2=>1],
            2=>[1=>1]
        ));
        $aiPlayer = new AIPlayer($board, 0);
        $move = $aiPlayer->makeMove();
        $this->assertTrue($move[0] == 2);
        $this->assertTrue($move[1] == 0 );
    }

} 