<?php

class GameRunnerTest extends PHPUnit_Framework_TestCase
{
    function testGenerateOutput()
    {
        $times = 20000;
        $this->generateManyOutputs($times, '/tmp/LegacyGameOutputA.txt');
        $this->generateManyOutputs($times, '/tmp/LegacyGameOutputB.txt');
        $outputA = file_get_contents('/tmp/LegacyGameOutputA.txt');
        $outputB = file_get_contents('/tmp/LegacyGameOutputB.txt');
        $this->assertTrue($outputA == $outputB);
    }

    private function generateManyOutputs($times, $fileName)
    {
        $itsFirst = true;
        while ($times) {
            if ($itsFirst) {
                file_put_contents($fileName, $this->generateOutput($times));
                $itsFirst = false;
            } else {
                file_put_contents($fileName, $this->generateOutput($times), FILE_APPEND);
            }
            $times--;
        }
    }

    protected function generateOutput($seed)
    {
        ob_start();
        srand($seed);
        require __DIR__ . '/../GameLegacy/GameRunner.php';
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}
