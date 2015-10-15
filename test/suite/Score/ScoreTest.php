<?php

namespace Icecave\Siphon\Score;

use PHPUnit_Framework_TestCase;

class ScoreTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->periods = [
            new Period(
                PeriodType::PERIOD(),
                1,
                10,
                20
            ),
            new Period(
                PeriodType::PERIOD(),
                2,
                30,
                40
            ),
        ];

        $this->score = new Score($this->periods);
    }

    public function testScore()
    {
        $this->assertSame(
            [40, 60],
            $this->score->score()
        );
    }

    public function testCount()
    {
        $this->assertSame(
            2,
            count($this->score)
        );
    }

    public function testGetIterator()
    {
        $this->assertSame(
            $this->periods,
            iterator_to_array($this->score)
        );
    }
}
