<?php

namespace Icecave\Siphon\Statistics;

use PHPUnit_Framework_TestCase;

class StatisticsCollectionTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->group1 = new StatisticsGroup('<key 1>');
        $this->group2 = new StatisticsGroup('<key 2>');

        $this->collection = new StatisticsCollection(
            [
                $this->group1,
                $this->group2,
            ]
        );
    }

    public function testConstructorWithNonGroup()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Expected array of StatisticsGroup instances.'
        );

        new StatisticsCollection(['<not a group>']);
    }

    public function testIsEmpty()
    {
        $this->assertFalse(
            $this->collection->isEmpty()
        );

        $collection = new StatisticsCollection();

        $this->assertTrue(
            $collection->isEmpty()
        );
    }

    public function testCount()
    {
        $this->assertSame(
            2,
            count($this->collection)
        );
    }

    public function testGetIterator()
    {
        $this->assertEquals(
            [
                '<key 1>' => $this->group1,
                '<key 2>' => $this->group2,
            ],
            iterator_to_array($this->collection)
        );
    }
}
