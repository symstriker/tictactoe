<?php

/**
 * Tic Tac Toe game
 * Silex application is using composer
 * Here you can find how to install application vendors through composer:
 * @See http://silex.sensiolabs.org/download
 * @See https://getcomposer.org/download/
 * @Author Yevhen Straihorodskyi | indestructible86@gmail.com
 */

use Symstriker\TicTacToe\Entity\Board;
use Symstriker\TicTacToe\Entity\PlayerInterface;
use Symstriker\TicTacToe\Entity as Entity;

require_once __DIR__.'/../vendor/autoload.php';

$debug = false;
$app = new Silex\Application();
$app->register(new Silex\Provider\SessionServiceProvider());
if ($debug) {
    $app->register(new Silex\Provider\MonologServiceProvider(), array(
        'monolog.logfile' => __DIR__.'/../development.log',
    ));
}

/**
 * Using twig template engine
 */
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../src/Symstriker/TicTacToe/Resources/views',
));

/**
 * Sharing game engine for all
 */
$app['game_engine'] = $app->protect(function(PlayerInterface $player1 = null, PlayerInterface $player2 = null, Board $board = null) use ($app) {
    return \Symstriker\TicTacToe\Engine\GameEngine::start($app['request']->getSession(), $player1, $player2);
});

/******************
 * Controllers
 ******************/

/**
 * Welcome action
 */
$app->get('/', function () use ($app) {
    return $app['twig']->render('welcome.html.twig');
})->bind('welcome');


$app->get('/aboutme', function () use ($app) {
    return $app['twig']->render('aboutme.html.twig');
})->bind('aboutme');

/**
 * Welcome action
 */
$app->get('/start/{player}/{sign}', function ($player, $sign) use ($app) {
    $board = new Entity\Board(array());
    $player1 = new Entity\Player($board, (int) $sign, $player);
    $player2 = new Entity\AIPlayer($board, (int) $sign ? 0 : 1);
    $gameEngineCallback = $app['game_engine'];
    $gameEngineCallback($player1, $player2, $board);
    return $app->redirect('/game');
})->bind('start');

/**
 * Game action
 */
$app->get('/game', function () use ($app) {
    $gameEngineCallback = $app['game_engine'];
    $gameEngine = $gameEngineCallback();
    if (!\Symstriker\TicTacToe\Engine\GameEngine::isStarted()) {
        return $app->redirect('/');
    }

    return $app['twig']->render('game.html.twig',
        [
            'player1' => $gameEngine->getFirstPlayer(),
            'player2' => $gameEngine->getSecondPlayer(),
            'board' => $gameEngine->getBoard()
        ]
    );
})->bind('game');

/**
 * Make move action
 */
$app->get('/move/{row}/{col}', function ($row, $col) use ($app) {
    $gameEngineCallback = $app['game_engine'];
    $gameEngine = $gameEngineCallback();

    if (!\Symstriker\TicTacToe\Engine\GameEngine::isStarted()) {
        $app->abort(403, 'Game is forbidden, that\'s because game engine is not started!');
    }
    $opponentMove = $gameEngine->makeMove((int)$row, (int)$col);
    $winner = $gameEngine->whoWon();
    $winner = $winner ? $winner->getGameSign() : false;

    return new \Symfony\Component\HttpFoundation\JsonResponse(
        ['row' => $opponentMove[0],
        'col' => $opponentMove[1],
        'winner' => $winner]
    );

})->bind('make_move');

$app->run();