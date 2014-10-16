<?php
require __DIR__ . '/../vendor/autoload.php';

class GameTest extends PHPUnit_Framework_TestCase
{
    /** @var  Game */
    public $_game;

    public function setUp()
    {
        $this->_game = new Game();
    }

    public function testCreateAGameOk()
    {
        $this->assertInstanceOf('Game', $this->_game);
    }

    public function testAJustCreatedNewGameIsNotPlayable()
    {
        $this->assertFalse($this->_game->isPlayable());
    }

    public function testCreatedNewGameIsPlayable()
    {
        $this->_game->add('player 1');
        $this->_game->add('player 2');
        $this->assertTrue($this->_game->isPlayable());
    }
}
