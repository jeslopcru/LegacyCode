<?php
namespace GameLegacy;

class CLIDisplay implements Display
{
    protected $popQuestions = [];
    protected $scienceQuestions = [];
    protected $sportsQuestions = [];
    protected $rockQuestions = [];

    public function __construct()
    {
        $this->initializeQuestions();
    }

    protected function initializeQuestions()
    {
        $categorySize = 50;
        for ($i = 0; $i < $categorySize; $i++) {
            array_push($this->popQuestions, "Pop Question " . $i);
            array_push($this->scienceQuestions, "Science Question " . $i);
            array_push($this->sportsQuestions, "Sports Question " . $i);
            array_push($this->rockQuestions, "Rock Question " . $i);
        }
    }

    public function statusAfterRoll($rolledNumber, $currentPlayer)
    {
        $this->currentPlayer($currentPlayer);
        $this->rolledNumber($rolledNumber);
    }

    protected function currentPlayer($currentPlayer)
    {
        $this->echoln($currentPlayer . " is the current player");
    }

    protected function echoln($string)
    {
        echo $string . "\n";
    }

    protected function rolledNumber($rolledNumber)
    {
        $this->echoln("They have rolled a " . $rolledNumber);
    }

    public function playerGettingOutOfPenaltyBox($currentPlayer)
    {
        $this->echoln($currentPlayer . " is getting out of the penalty box");
    }

    public function playerNewLocation($currentPlayer, $currentPlaces)
    {
        $this->echoln(
            $currentPlayer
            . "'s new location is " .
            $currentPlaces
        );
    }

    public function currentCategory($currentCategory)
    {
        $this->echoln("The category is " . $currentCategory);
    }

    public function playerStaysInPenaltyBox($currentPlayer)
    {
        $this->echoln($currentPlayer . " is not getting out of the penalty box");
    }

    public function  askQuestion($currentCategory)
    {
        if ($currentCategory == "Pop") {
            $this->echoln(array_shift($this->popQuestions));
        }
        if ($currentCategory == "Science") {
            $this->echoln(array_shift($this->scienceQuestions));
        }
        if ($currentCategory == "Sports") {
            $this->echoln(array_shift($this->sportsQuestions));
        }
        if ($currentCategory == "Rock") {
            $this->echoln(array_shift($this->rockQuestions));
        }
    }

    public function correctAnswer()
    {
        $this->echoln("Answer was correct!!!!");
    }

    public function correctAnswerWithTypo()
    {
        $this->echoln("Answer was corrent!!!!");
    }

    public function playerCoins($currentPlayer, $playerCoins)
    {
        $this->echoln($currentPlayer . " now has " . $playerCoins . " Gold Coins.");
    }

    public function incorrectAnswer()
    {
        $this->echoln("Question was incorrectly answered");
    }

    public function playerSentToPenaltyBox($currentPlayer)
    {
        $this->echoln($currentPlayer . " was sent to the penalty box");
    }

    public function playerAdded($playerName, $numberOfPlayers)
    {
        $this->echoln($playerName . " was added");
        $this->echoln("They are player number " . $numberOfPlayers);
    }

} 