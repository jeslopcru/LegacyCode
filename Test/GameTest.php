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

    public function testCreatedNewGameIsPlayable()
    {
        $this->addEnoughPlayers();
        $this->assertTrue($this->_game->isPlayable());
    }

    protected function addEnoughPlayers()
    {
        $this->addALotOfPlayers(Game::$minimalNumberOfPlayer);
    }

    public function testCanAddANewPlayer()
    {
        $this->assertEquals(0, count($this->_game->players));
        $this->_game->add('a Player');
        $this->assertEquals(1, count($this->_game->players));
        $this->assertSetDefaultParametersForPlayer(1);
    }

    protected function assertSetDefaultParametersForPlayer($playerId)
    {
        $this->assertEquals(0, $this->_game->places[$playerId]);
        $this->assertEquals(0, $this->_game->purses[$playerId]);
        $this->assertFalse($this->_game->inPenaltyBox[$playerId]);
    }

    public function testPlayerNotWinsWithTheCorrectNumberOfScore()
    {
        $this->_game->currentPlayer = 0;
        $this->_game->purses[0] = Game::$numberOfScoreToWin;
        $this->assertFalse($this->_game->didNotPlayerWin());
    }

}
