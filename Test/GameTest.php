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

    public function testWhenAPlayerEntersAWrongAnswerItIsSentToThePenaltyBox()
    {
        $this->_game->add('A player');
        $this->_game->currentPlayer = 0;
        $this->_game->wrongAnswer();
        $this->assertTrue($this->_game->inPenaltyBox[0]);
        $this->assertEquals(0, $this->_game->currentPlayer);
    }

    public function testCurrentPlayerIsNotResetAfterWrongAnswerIfOtherPlayersDidNotYetPlay()
    {
        $this->addALotOfPlayers(2);
        $this->_game->currentPlayer = 0;
        $this->_game->wrongAnswer();
        $this->assertEquals(1, $this->_game->currentPlayer);
    }

    public function testAPlayersNextPositionIsCorrectlyDeterminedWhenNoNewLapIsInvolved()
    {
        $currentPlace = 2;
        $rolledNumber = 1;

        $this->setAPlayerNotInPenaltyBox();
        $this->setCurrentPlayersPosition($currentPlace);

        $this->_game->roll($rolledNumber);

        $this->assertEquals(
            $currentPlace + $rolledNumber,
            $this->_game->places[$this->_game->currentPlayer],
            'The player was expected at position ' . $currentPlace + $rolledNumber
        );
    }

    protected function setAPlayerNotInPenaltyBox()
    {
        $this->_game->currentPlayer = 0;
        $this->_game->players[$this->_game->currentPlayer] = 'Jeff';
        $this->_game->inPenaltyBox[$this->_game->currentPlayer] = false;
    }

    protected function setCurrentPlayersPosition($currentPlace)
    {
        $this->_game->places[$this->_game->currentPlayer] = $currentPlace;
    }

    public function testAPlayerWillStarANewLapWhenNeeded()
    {
        $currentPlace = 11;
        $rolledNumber = 2;

        $this->setAPlayerNotInPenaltyBox();
        $this->setCurrentPlayersPosition($currentPlace);

        $this->_game->roll($rolledNumber);

        $this->assertEquals(
            1,
            $this->_game->places[$this->_game->currentPlayer],
            'The player was expected at position 1'
        );
    }

    public function testScienceCategoryCanBeDetermined()
    {
        $currentPlaces = [1];
        $expectedCategory = 'Science';

        $this->assertCorrectCategoryForGivenPlaces($currentPlaces, $expectedCategory);
    }

    protected function assertCorrectCategoryForGivenPlaces($currentPlaces, $expectedCategory)
    {
        foreach ($currentPlaces as $currentPlace) {
            $this->setAPlayerNotInPenaltyBox();
            $this->setCurrentPlayersPosition($currentPlace);
            $foundCategory = $this->_game->currentCategory();
            $this->assertEquals(
                $expectedCategory,
                $foundCategory,
                'Expected' . $expectedCategory . 'category for position ' . $currentPlace .
                ' but got ' . $foundCategory
            );
        }
    }

    public function testSportsCategoryCanBeDetermined()
    {
        $currentPlaces = [2];
        $expectedCategory = 'Sports';

        $this->assertCorrectCategoryForGivenPlaces($currentPlaces, $expectedCategory);
    }

    public function testRockCategoryCanBeDetermined()
    {
        $currentPlaces = [3];
        $expectedCategory = 'Rock';

        $this->assertCorrectCategoryForGivenPlaces($currentPlaces, $expectedCategory);
    }

    public function testPopCategoryCanBeDetermined()
    {
        $currentPlaces = [4];
        $expectedCategory = 'Pop';

        $this->assertCorrectCategoryForGivenPlaces($currentPlaces, $expectedCategory);
    }
}
