<?php

use GameLegacy\Display;

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
    protected $display;

    function  __construct()
    {

        $this->players = array();
        $this->places = array(0);
        $this->purses = array(0);
        $this->inPenaltyBox = array(0);

        $this->display = new Display();


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

    function howManyPlayers()
    {
        return count($this->players);
    }

    function add($playerName)
    {
        array_push($this->players, $playerName);
        $this->setDefaultParameterForPlayer($this->howManyPlayers());

        $this->display->echoln($playerName . " was added");
        $this->display->echoln("They are player number " . count($this->players));

        return true;
    }

    protected function setDefaultParameterForPlayer($playerId)
    {
        $this->places[$playerId] = 0;
        $this->purses[$playerId] = 0;
        $this->inPenaltyBox[$playerId] = false;
    }

    function roll($rolledNumber)
    {
        $this->display->statusAfterRoll($rolledNumber, $this->players[$this->currentPlayer]);

        if ($this->inPenaltyBox[$this->currentPlayer]) {
            $this->playNextMoveForPlayerInPenaltyBox($rolledNumber);
        } else {
            $this->playNextMove($rolledNumber);
        }
    }

    protected function playNextMoveForPlayerInPenaltyBox($rolledNumber)
    {
        if ($this->isOdd($rolledNumber)) {
            $this->getPlayerOutOfPenaltyBoxAndPlayNextMove($rolledNumber);
        } else {
            $this->keepPlayerInPenaltyBox();
        }
    }

    protected function isOdd($roll)
    {
        return $roll % 2 != 0;
    }

    protected function getPlayerOutOfPenaltyBoxAndPlayNextMove($rolledNumber)
    {
        $this->isGettingOutOfPenaltyBox = true;

        $this->display->PlayerGettingOutOfPenaltyBox($this->players[$this->currentPlayer]);
        $this->playNextMove($rolledNumber);
    }

    protected function playNextMove($rolledNumber)
    {
        $this->movePlayer($rolledNumber);
        $this->display->PlayerNewLocation($this->players[$this->currentPlayer], $this->places[$this->currentPlayer]);
        $this->display->CurrentCategory($this->currentCategory());
        $this->askQuestion();
    }

    protected function movePlayer($rolledNumber)
    {
        $boardSize = 12;

        $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $rolledNumber;
        if ($this->playerShouldStartANewLap()) {
            $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - $boardSize;
        }
    }

    protected function playerShouldStartANewLap()
    {
        $lastPositionOnTheBoard = 11;

        return $this->places[$this->currentPlayer] > $lastPositionOnTheBoard;
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

    function  askQuestion()
    {
        if ($this->currentCategory() == "Pop") {
            $this->display->echoln(array_shift($this->popQuestions));
        }
        if ($this->currentCategory() == "Science") {
            $this->display->echoln(array_shift($this->scienceQuestions));
        }
        if ($this->currentCategory() == "Sports") {
            $this->display->echoln(array_shift($this->sportsQuestions));
        }
        if ($this->currentCategory() == "Rock") {
            $this->display->echoln(array_shift($this->rockQuestions));
        }
    }

    protected function keepPlayerInPenaltyBox()
    {
        $this->display->playerStaysInPenaltyBox($this->players[$this->currentPlayer]);
        $this->isGettingOutOfPenaltyBox = false;
    }

    function wasCorrectlyAnswered()
    {
        if ($this->inPenaltyBox[$this->currentPlayer]) {
            if ($this->isGettingOutOfPenaltyBox) {
                $this->display->echoln("Answer was correct!!!!");
                $this->purses[$this->currentPlayer]++;
                $this->display->echoln(
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

            $this->display->echoln("Answer was corrent!!!!");
            $this->purses[$this->currentPlayer]++;
            $this->display->echoln(
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

    function didNotPlayerWin()
    {
        return !($this->purses[$this->currentPlayer] == Game::$numberOfScoreToWin);
    }

    protected function shoudResetCurrentPlayer()
    {
        return $this->currentPlayer == count($this->players);
    }

    function wrongAnswer()
    {
        $this->display->echoln("Question was incorrectly answered");
        $this->display->echoln($this->players[$this->currentPlayer] . " was sent to the penalty box");
        $this->inPenaltyBox[$this->currentPlayer] = true;

        $this->currentPlayer++;
        if ($this->shoudResetCurrentPlayer()) {
            $this->currentPlayer = 0;
        }

        return true;
    }
}

