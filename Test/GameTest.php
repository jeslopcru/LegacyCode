<?php
require __DIR__ . '/../vendor/autoload.php';

class GameTest extends PHPUnit_Framework_TestCase
{
    public function testCreateAGameOk()
    {
        $game = new Game();
        $this->assertInstanceOf('Game', $game);
    }
}
