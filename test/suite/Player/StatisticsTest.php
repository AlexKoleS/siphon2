<?php
namespace Icecave\Siphon\Player;

use PHPUnit_Framework_TestCase;

class StatisticsTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->statistics = new Statistics(
            '<player-id>',
            '<season>',
            [
                'one' => [
                    'a' => 5,
                    'b' => 10,
                ],
                'two' => [
                    'b' => 12.5,
                    'c' => 0.123,
                ],
            ]
        );
    }

    public function testPlayerId()
    {
        $this->assertSame(
            '<player-id>',
            $this->statistics->playerId()
        );
    }

    public function testSeason()
    {
        $this->assertSame(
            '<season>',
            $this->statistics->season()
        );
    }

    public function testGet()
    {
        $this->assertSame(
            5,
            $this->statistics->get('one', 'a')
        );
    }

    public function testGetFallback()
    {
        $this->assertSame(
            0,
            $this->statistics->get('one', 'c')
        );

        $this->assertSame(
            123,
            $this->statistics->get('one', 'c', 123)
        );
    }

    public function testGetTotal()
    {
        $this->assertSame(
            5,
            $this->statistics->getTotal('a')
        );

        $this->assertSame(
            22.5,
            $this->statistics->getTotal('b')
        );

        $this->assertSame(
            0.123,
            $this->statistics->getTotal('c')
        );
    }

    public function testHas()
    {
        $this->assertTrue(
            $this->statistics->has('one', 'a')
        );

        $this->assertFalse(
            $this->statistics->has('one', 'c')
        );
    }

    public function testGetIterator()
    {
        $this->assertSame(
            [
                'a' => 5,
                'b' => 22.5,
                'c' => 0.123,
            ],
            iterator_to_array($this->statistics)
        );
    }

    public function testIterate()
    {
        $this->assertSame(
            [
                ['one', 'a', 5],
                ['one', 'b', 10],
                ['two', 'b', 12.5],
                ['two', 'c', 0.123],
            ],
            iterator_to_array(
                $this->statistics->iterate()
            )
        );
    }

    public function testIterateByGroup()
    {
        $this->assertSame(
            [
                'one' => [
                    'a' => 5,
                    'b' => 10,
                    'c' => 0,
                ],
                'two' => [
                    'a' => 0,
                    'b' => 12.5,
                    'c' => 0.123,
                ],
            ],
            iterator_to_array(
                $this->statistics->iterateByGroup()
            )
        );
    }

    public function testIterateByKey()
    {
        $this->assertSame(
            [
                'a' => ['one' => 5,  'two' => 0],
                'b'           => ['one' => 10, 'two' => 12.5],
                'c'                     => ['one' => 0,  'two' => 0.123],
            ],
            iterator_to_array(
                $this->statistics->iterateByKey()
            )
        );
    }
}
