<?php
require __DIR__ . '/../vendor/autoload.php';

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

        $minAnswerId = 0;
        $maxAnswerId = 9;
        $wrongAnswerId = 7;
        if (isWrongAnswer($minAnswerId, $maxAnswerId, $wrongAnswerId)) {
            $notAWinner = $aGame->wrongAnswer();
        } else {
            $notAWinner = $aGame->wasCorrectlyAnswered();
        }

    } while ($notAWinner);
}

function isWrongAnswer($minAnswerId, $maxAnswerId, $wrongAnswerId)
{
    return rand($minAnswerId, $maxAnswerId) == $wrongAnswerId;
}

