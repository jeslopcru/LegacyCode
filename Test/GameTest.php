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
        $this->addInsufficientPlayers();
        $this->assertFalse($this->_game->isPlayable());
    }

    public function testCreatedNewGameIsPlayable()
    {
        $this->addEnoughPlayers();
        $this->assertTrue($this->_game->isPlayable());
    }

    protected function addEnoughPlayers()
    {
        $this->addALotOfPlayers(Game::$minimalNumberOfPlayer);
    }

    protected function addInsufficientPlayers()
    {
        $this->addALotOfPlayers(Game::$minimalNumberOfPlayer - 1);
    }

    protected function addALotOfPlayers($numberOfPlayers)
    {
        for ($i = 0; $i < $numberOfPlayers; $i++) {
            $this->_game->add('a Player');
        }
    }
}
