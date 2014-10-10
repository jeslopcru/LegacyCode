<?php
require __DIR__ . '/../vendor/autoload.php';


$notAWinner;

$aGame = new Game();

$aGame->add("Chet");
$aGame->add("Pat");
$aGame->add("Sue");

do {

    $dice = rand(0, 5) + 1;
    $aGame->roll($dice);

    if (rand(0, 9) == 7) {
        $notAWinner = $aGame->wrongAnswer();
    } else {
        $notAWinner = $aGame->wasCorrectlyAnswered();
    }

} while ($notAWinner);
  

