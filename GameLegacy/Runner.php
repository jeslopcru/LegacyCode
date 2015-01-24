<?php
require __DIR__ . '/../vendor/autoload.php';

use GameLegacy\CLIDisplay;

class Runner {

    function run()
    {
        $display = new CLIDisplay();
        $aGame = new Game($display);

        $aGame->add("Chet");
        $aGame->add("Pat");
        $aGame->add("Sue");

        do {
            $dice = rand(0, 5) + 1;
            $aGame->roll($dice);

        } while (!$this->didSomeoneWin($aGame, $this->isCorrectAnswer()));
    }

    function didSomeoneWin(Game $aGame, $isCorrectAnswer)
    {
        if ($isCorrectAnswer) {
            return !$aGame->wrongAnswer();
        } else {
            return !$aGame->wasCorrectlyAnswered();
        }
    }

    function isCorrectAnswer($minAnswerId = Game::MIN_ANSWER_ID, $maxAnswerId = Game::MAX_ANSWER_ID)
    {
        return rand($minAnswerId, $maxAnswerId) != Game::WRONG_ANSWER_ID;
    }

}