<?php
require __DIR__ . '/../vendor/autoload.php';

const WRONG_ANSWER_ID = 7;
const MIN_ANSWER_ID = 0;
const MAX_ANSWER_ID = 9;

function run()
{
    $notAWinner;

    $aGame = new Game();

    $aGame->add("Chet");
    $aGame->add("Pat");
    $aGame->add("Sue");

    do {
        $dice = rand(0, 5) + 1;
        $aGame->roll($dice);

        $notAWinner = getNotAwinner($aGame,isCorrectAnswer());

    } while ($notAWinner);
}

function getNotAwinner($aGame, $isCorrectAnswer)
{
    if (!$isCorrectAnswer) {
        $notAWinner = $aGame->wrongAnswer();

        return $notAWinner;
    } else {
        $notAWinner = $aGame->wasCorrectlyAnswered();

        return $notAWinner;
    }
}

function isCorrectAnswer($minAnswerId = MIN_ANSWER_ID,$maxAnswerId = MAX_ANSWER_ID)
{
    return rand($minAnswerId, $maxAnswerId) != WRONG_ANSWER_ID;
}

