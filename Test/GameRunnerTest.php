<?php

class GameRunnerTest extends PHPUnit_Framework_TestCase
{
    function testGenerateOutput()
    {
        $this->generateManyOutputs(20, '/tmp/LegacyGameOutputA.txt');
        $this->generateManyOutputs(20, '/tmp/LegacyGameOutputB.txt');
        $outputA = file_get_contents('/tmp/LegacyGameOutputA.txt');
        $outputB = file_get_contents('/tmp/LegacyGameOutputB.txt');
        $this->assertEquals($outputA, $outputB);
    }

    private function generateManyOutputs($times, $fileName)
    {
        $itsFirst = true;
        while ($times) {
            if ($itsFirst) {
                file_put_contents($fileName, $this->generateOutput());
                $itsFirst = false;
            } else {
                file_put_contents($fileName, $this->generateOutput(), FILE_APPEND);
            }
            $times--;
        }
    }

    protected function generateOutput()
    {
        ob_start();
        srand(0);
        require __DIR__ . '/../GameLegacy/GameRunner.php';
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}
