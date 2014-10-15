<?php
include __DIR__ . '/../GameLegacy/GameRunner.php';

class GameRunnerTest extends PHPUnit_Framework_TestCase
{
    public function testCanFindCorrectAnswer()
    {
        $this->assertAnswersAreCorrectFor($this->getGoodAnswerId());
    }

    protected function getGoodAnswerId()
    {
        return array_diff(range(MIN_ANSWER_ID, MAX_ANSWER_ID), [WRONG_ANSWER_ID]);
    }

    function testCanFindWrongAnswer()
    {
        $this->assertFalse(isCorrectAnswer(WRONG_ANSWER_ID, WRONG_ANSWER_ID));
    }

    protected function assertAnswersAreCorrectFor($correctAnswerIDs)
    {
        foreach ($correctAnswerIDs as $id) {
            $this->assertTrue(isCorrectAnswer($id, $id));
        }
    }

    public function testWhenCorrectAnswerIsProviderItCanTellIfThereIsNoWinner()
    {
        $isCorrectAnswer = false;

        $mockGame = \Mockery::mock('Game');
        $mockGame->shouldReceive('wasCorrectlyAnswered')
            ->andReturn($isCorrectAnswer);

        $this->assertTrue(didSomeoneWin($mockGame, $isCorrectAnswer));
    }

    public function testWhenAWrongAnswerIsProvidedItCanTellIfThereIsNoWinner()
    {
        $isCorrectAnswer = true;

        $mockGame = \Mockery::mock('Game');
        $mockGame->shouldReceive('wrongAnswer')
            ->andReturn($isCorrectAnswer);

        $this->assertFalse(didSomeoneWin($mockGame, $isCorrectAnswer));
    }

    public function tearDown()
    {
        Mockery::close();
    }

    function testOutputMatchWithMaster()
    {
        $this->markTestSkipped();
        $masterOutput = __DIR__ . '/../MasterOutput.txt';
        $times = 20000;
        $actualPath = '/tmp/actual.txt';
        $this->generateManyOutputs($times, $actualPath);
        $fileContentMaster = sha1(file_get_contents($masterOutput));
        $fileContentActualOutput = sha1(file_get_contents($actualPath));
        $this->assertEquals($fileContentMaster, $fileContentActualOutput);
    }

    function testGenerateOutput()
    {
        $this->markTestSkipped();
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
        run();
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}
