<?php
require __DIR__ . '/../vendor/autoload.php';

class GameTest extends PHPUnit_Framework_TestCase
{
    /** @var  Game */
    public $_game;

    public function setUp()
    {
        $mockDipslay = $this->getMock('\GameLegacy\Display');

        $this->_game = new Game($mockDipslay);
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

        $expectedResult = $currentPlace + $rolledNumber;
        $this->setAPlayerNotInPenaltyBox();
        $this->setCurrentPlayersPosition($currentPlace);

        $this->_game->roll($rolledNumber);

        $this->assertEquals(
            $expectedResult,
            $this->_game->places[$this->_game->currentPlayer],
            'The player was expected at position ' . $expectedResult
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

    public function testAPlayerWhoIsPenalizedAndRollsAnEvenNumberWillStayInThePenaltyBox()
    {
        $rolledNumber = 2;
        $this->setAPlayerInPenaltyBox();
        $this->_game->roll($rolledNumber);
        $this->assertFalse($this->_game->isGettingOutOfPenaltyBox);
    }

    protected function setAPlayerInPenaltyBox()
    {
        $this->_game->currentPlayer = 0;
        $this->_game->players[$this->_game->currentPlayer] = 'Jeff';
        $this->_game->inPenaltyBox[$this->_game->currentPlayer] = true;
    }

    public function testPlayerGettingOutOfPenaltyNextPositionWithNewLap()
    {
        $currentPlace = 11;
        $numberRequiredToGetOutOfPenaltyBox = 3;

        $this->setAPlayerInPenaltyBox();
        $this->setCurrentPlayersPosition($currentPlace);

        $this->_game->roll($numberRequiredToGetOutOfPenaltyBox);
        $this->assertEquals('2', $this->getCurrentPlayersPosition(), 'Player was expected at position 3');
    }

    protected function getCurrentPlayersPosition()
    {
        return $this->_game->places[$this->_game->currentPlayer];
    }

    public function testWasCorrectlyAnsweredAndGettingOutOfPenaltyBoxWhileBeingAWinner()
    {
        $this->setAPlayerThatIsInThePenaltyBox();
        $this->_game->add('Another Player');
        $this->currentPlayerWillLeavePenaltyBox();
        $this->setCurrentPlayerAWinner();

        $this->assertTrue($this->_game->wasCorrectlyAnswered());
    }

    protected function setAPlayerThatIsInThePenaltyBox()
    {
        $this->_game->currentPlayer = 0;
        $this->_game->players[$this->_game->currentPlayer] = 'John';
        $this->_game->inPenaltyBox[$this->_game->currentPlayer] = true;
    }

    protected function currentPlayerWillLeavePenaltyBox()
    {
        $this->_game->isGettingOutOfPenaltyBox = true;
    }

    protected function setCurrentPlayerAWinner()
    {
        $this->_game->purses[$this->_game->currentPlayer] = Game::$numberOfScoreToWin;
    }

    public function testWasCorrectlyAnsweredAndGettingOutOfPenaltyBoxWhileNOTBeingAWinner()
    {
        $this->setAPlayerInPenaltyBox();
        $this->currentPlayerWillLeavePenaltyBox();
        $this->setCurrentPlayerNotAWinner();

        $this->assertFalse($this->_game->wasCorrectlyAnswered());
    }

    protected function setCurrentPlayerNotAWinner()
    {
        $this->_game->purses[$this->_game->currentPlayer] = Game::$numberOfScoreToWin - 1;
    }

    function testWasCorrectlyAnsweredAndStayingInThePenaltyBox()
    {
        $this->setAPlayerThatIsInThePenaltyBox();
        $this->_game->add('Another Player');
        $this->currentPlayerWillStayInPenaltyBox();
        $this->assertTrue($this->_game->wasCorrectlyAnswered());
    }

    protected function currentPlayerWillStayInPenaltyBox()
    {
        $this->_game->isGettingOutOfPenaltyBox = false;
    }

    function testWasCorrectlyAnsweredAndNotInPenaltyBoxWhileBeingAWinner()
    {
        $this->setAPlayerThatIsNotInThePenaltyBox();
        $this->_game->add('Another Player');
        $this->setCurrentPlayerAWinner();
        $this->assertTrue($this->_game->wasCorrectlyAnswered());
    }

    protected function setAPlayerThatIsNotInThePenaltyBox()
    {
        $this->_game->currentPlayer = 0;
        $this->_game->players[$this->_game->currentPlayer] = 'John';
        $this->_game->inPenaltyBox[$this->_game->currentPlayer] = false;
    }

}
