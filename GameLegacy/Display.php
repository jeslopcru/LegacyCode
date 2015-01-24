<?php

namespace GameLegacy;

interface Display
{
    public function statusAfterRoll($rolledNumber, $currentPlayer);

    public function playerGettingOutOfPenaltyBox($currentPlayer);

    public function playerNewLocation($currentPlayer, $currentPlaces);

    public function currentCategory($currentCategory);

    public function playerStaysInPenaltyBox($currentPlayer);

    public function askQuestion($currentCategory);

    public function correctAnswer();

    public function correctAnswerWithTypo();

    public function playerCoins($currentPlayer, $playerCoins);

    public function incorrectAnswer();

    public function playerSentToPenaltyBox($currentPlayer);

    public function playerAdded($playerName, $numberOfPlayers);

}
