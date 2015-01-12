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

        $this->display->playerAdded($playerName, count($this->players));

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
        $this->display->askQuestion($this->currentCategory());
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

    protected function keepPlayerInPenaltyBox()
    {
        $this->display->playerStaysInPenaltyBox($this->players[$this->currentPlayer]);
        $this->isGettingOutOfPenaltyBox = false;
    }

    function wasCorrectlyAnswered()
    {
        if ($this->inPenaltyBox[$this->currentPlayer]) {
            if ($this->isGettingOutOfPenaltyBox) {
                $this->display->correctAnswer();
                $this->purses[$this->currentPlayer]++;
                $this->display->playerCoins(
                    $this->players[$this->currentPlayer],
                    $this->purses[$this->currentPlayer]
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

            $this->display->correctAnswerWithTypo();
            $this->purses[$this->currentPlayer]++;
            $this->display->playerCoins(
                $this->players[$this->currentPlayer],
                $this->purses[$this->currentPlayer]
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
        $this->display->incorrectAnswer();
        $this->display->playerSentToPenaltyBox($this->players[$this->currentPlayer]);
        $this->inPenaltyBox[$this->currentPlayer] = true;

        $this->currentPlayer++;
        if ($this->shoudResetCurrentPlayer()) {
            $this->currentPlayer = 0;
        }

        return true;
    }
}

