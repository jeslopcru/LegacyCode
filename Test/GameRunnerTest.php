<?php

class GameRunnerTest extends PHPUnit_Framework_TestCase
{

    public function testOutput()
    {
        ob_start();
        require_once __DIR__ . '/../GameLegacy/GameRunner.php';
        $output = ob_get_contents();
        ob_end_clean();

        var_dump($output);
    }

}
