<?php
require_once __DIR__ . '/../GameLegacy/GameRunner.php';

class GameRunnerTest extends PHPUnit_Framework_TestCase
{

    public function testOutput()
    {
        file_put_contents('/tmp/LegacyGameOutput.txt', $this->generateOutput());
        $file_content = file_get_contents('/tmp/LegacyGameOutput.txt');
        $this->assertEquals($file_content, $this->generateOutput());
    }

    private function generateOutput()
    {
        ob_start();
        srand(0);
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

}
