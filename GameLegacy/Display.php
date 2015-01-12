<?php
namespace GameLegacy;

class Display
{
    public function statusAfterRoll($rolledNumber, $currentPlayer)
    {
        $this->currentPlayer($currentPlayer);
        $this->rolledNumber($rolledNumber);
    }

    protected function currentPlayer($currentPlayer)
    {
        $this->echoln($currentPlayer . " is the current player");
    }

    function echoln($string)
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
} 