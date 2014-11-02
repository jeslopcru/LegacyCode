<?php
function echoln($string)
{
    echo $string . "\n";
}

class Game
{
    static $minimalNumberOfPlayer = 2;
    static $numberOfScoreToWin = 6;


    var $players;
    var $places;
    var $purses;
    var $inPenaltyBox;

    var $popQuestions;
    var $scienceQuestions;
    var $sportsQuestions;
    var $rockQuestions;

    var $currentPlayer = 0;
    var $isGettingOutOfPenaltyBox;

    function  __construct()
    {

        $this->players = array();
        $this->places = array(0);
        $this->purses = array(0);
        $this->inPenaltyBox = array(0);

        $this->popQuestions = array();
        $this->scienceQuestions = array();
        $this->sportsQuestions = array();
        $this->rockQuestions = array();

        $categorySize = 50;
        for ($i = 0; $i < $categorySize; $i++) {
            array_push($this->popQuestions, "Pop Question " . $i);
            array_push($this->scienceQuestions, "Science Question " . $i);
            array_push($this->sportsQuestions, "Sports Question " . $i);
            array_push($this->rockQuestions, "Rock Question " . $i);
        }
    }

    function isPlayable()
    {
        return ($this->howManyPlayers() >= Game::$minimalNumberOfPlayer);
    }

    function add($playerName)
    {
        array_push($this->players, $playerName);
        $this->setDefaultParameterForPlayer($this->howManyPlayers());

        echoln($playerName . " was added");
        echoln("They are player number " . count($this->players));

        return true;
    }

    function howManyPlayers()
    {
        return count($this->players);
    }

    function  roll($rolledNumber)
    {
        $this->displayCurrentPlayer();
        $this->displayRolledNumber($rolledNumber);

        $boardSize = 12;
        if ($this->inPenaltyBox[$this->currentPlayer]) {
            if ($this->isOdd($rolledNumber)) {
                $this->isGettingOutOfPenaltyBox = true;

                echoln($this->players[$this->currentPlayer] . " is getting out of the penalty box");
                $this->movePlayer($rolledNumber, $boardSize);
                $this->displayPlayerNewLocation();
                $this->displayCurrentCategory();
                $this->askQuestion();
            } else {
                echoln($this->players[$this->currentPlayer] . " is not getting out of the penalty box");
                $this->isGettingOutOfPenaltyBox = false;
            }

        } else {
            $this->movePlayer($rolledNumber, $boardSize);
            $this->displayPlayerNewLocation();
            $this->displayCurrentCategory();
            $this->askQuestion();
        }

    }

    function  askQuestion()
    {
        if ($this->currentCategory() == "Pop") {
            echoln(array_shift($this->popQuestions));
        }
        if ($this->currentCategory() == "Science") {
            echoln(array_shift($this->scienceQuestions));
        }
        if ($this->currentCategory() == "Sports") {
            echoln(array_shift($this->sportsQuestions));
        }
        if ($this->currentCategory() == "Rock") {
            echoln(array_shift($this->rockQuestions));
        }
    }


    function currentCategory()
    {
        $popCategory = "Pop";
        $scienceCategory = "Science";
        $sportsCategory = "Sports";
        $rockCategory = "Rock";

        if ($this->places[$this->currentPlayer] == 0) {
            return $popCategory;
        }
        if ($this->places[$this->currentPlayer] == 4) {
            return $popCategory;
        }
        if ($this->places[$this->currentPlayer] == 8) {
            return $popCategory;
        }
        if ($this->places[$this->currentPlayer] == 1) {
            return $scienceCategory;
        }
        if ($this->places[$this->currentPlayer] == 5) {
            return $scienceCategory;
        }
        if ($this->places[$this->currentPlayer] == 9) {
            return $scienceCategory;
        }
        if ($this->places[$this->currentPlayer] == 2) {
            return $sportsCategory;
        }
        if ($this->places[$this->currentPlayer] == 6) {
            return $sportsCategory;
        }
        if ($this->places[$this->currentPlayer] == 10) {
            return $sportsCategory;
        }

        return $rockCategory;
    }

    function wasCorrectlyAnswered()
    {
        if ($this->inPenaltyBox[$this->currentPlayer]) {
            if ($this->isGettingOutOfPenaltyBox) {
                echoln("Answer was correct!!!!");
                $this->purses[$this->currentPlayer]++;
                echoln(
                    $this->players[$this->currentPlayer]
                    . " now has "
                    . $this->purses[$this->currentPlayer]
                    . " Gold Coins."
                );

                $winner = $this->didNotPlayerWin();
                $this->currentPlayer++;
                if ($this->shoudResetCurrentPlayer()) {
                    $this->currentPlayer = 0;
                }

                return $winner;
            } else {
                $this->currentPlayer++;
                if ($this->shoudResetCurrentPlayer()) {
                    $this->currentPlayer = 0;
                }

                return true;
            }


        } else {

            echoln("Answer was corrent!!!!");
            $this->purses[$this->currentPlayer]++;
            echoln(
                $this->players[$this->currentPlayer]
                . " now has "
                . $this->purses[$this->currentPlayer]
                . " Gold Coins."
            );

            $winner = $this->didNotPlayerWin();
            $this->currentPlayer++;
            if ($this->shoudResetCurrentPlayer()) {
                $this->currentPlayer = 0;
            }

            return $winner;
        }
    }

    function wrongAnswer()
    {
        echoln("Question was incorrectly answered");
        echoln($this->players[$this->currentPlayer] . " was sent to the penalty box");
        $this->inPenaltyBox[$this->currentPlayer] = true;

        $this->currentPlayer++;
        if ($this->shoudResetCurrentPlayer()) {
            $this->currentPlayer = 0;
        }

        return true;
    }


    function didNotPlayerWin()
    {
        return !($this->purses[$this->currentPlayer] == Game::$numberOfScoreToWin);
    }

    protected function isOdd($roll)
    {
        return $roll % 2 != 0;
    }

    protected function playerShouldStartANewLap()
    {
        $lastPositionOnTheBoard = 11;

        return $this->places[$this->currentPlayer] > $lastPositionOnTheBoard;
    }

    protected function shoudResetCurrentPlayer()
    {
        return $this->currentPlayer == count($this->players);
    }

    protected function setDefaultParameterForPlayer($playerId)
    {
        $this->places[$playerId] = 0;
        $this->purses[$playerId] = 0;
        $this->inPenaltyBox[$playerId] = false;
    }

    protected function movePlayer($rolledNumber, $boardSize)
    {
        $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $rolledNumber;
        if ($this->playerShouldStartANewLap()) {
            $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - $boardSize;
        }
    }

    protected function displayPlayerNewLocation()
    {
        echoln(
            $this->players[$this->currentPlayer]
            . "'s new location is "
            . $this->places[$this->currentPlayer]
        );
    }

    protected function displayCurrentCategory()
    {
        echoln("The category is " . $this->currentCategory());
    }

    protected function displayCurrentPlayer()
    {
        echoln($this->players[$this->currentPlayer] . " is the current player");
    }

    protected function displayRolledNumber($rolledNumber)
    {
        echoln("They have rolled a " . $rolledNumber);
    }
}

