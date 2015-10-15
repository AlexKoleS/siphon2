<?php

namespace Icecave\Siphon\Statistics;

use PHPUnit_Framework_TestCase;

class StatisticsGroupTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->group = new StatisticsGroup(
            '<key>',
            [
                '<scope 1>' => '<scope 1 value>',
                '<scope 2>' => '<scope 2 value>',
            ],
            [
                '<stat 1>' => 123,
                '<stat 2>' => 456.789,
            ]
        );
    }

    public function testConstructorWithInvalidKey()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Group key must be a string.'
        );

        new StatisticsGroup(123);
    }

    public function testConstructorWithInvalidScopeType()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Scope type must be a string.'
        );

        new StatisticsGroup(
            '<key>',
            [123 => '<scope value>']
        );
    }

    public function testConstructorWithInvalidScopeValue()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Scope value must be a string.'
        );

        new StatisticsGroup(
            '<key>',
            ['<scope>' => 456]
        );
    }

    public function testConstructorWithInvalidStatisticType()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Statistic type must be a string.'
        );

        new StatisticsGroup(
            '<key>',
            [],
            [123 => 456]
        );
    }

    public function testConstructorWithInvalidStatisticValue()
    {
        $this->setExpectedException(
            'InvalidArgumentException',
            'Statistic value must be an integer or float.'
        );

        new StatisticsGroup(
            '<key>',
            [],
            ['<stat>' => '<value>']
        );
    }

    public function testKey()
    {
        $this->assertSame(
            '<key>',
            $this->group->key()
        );
    }

    public function testScopes()
    {
        $this->assertEquals(
            [
                '<scope 1>' => '<scope 1 value>',
                '<scope 2>' => '<scope 2 value>',
            ],
            $this->group->scopes()
        );
    }

    public function testScopeByType()
    {
        $this->assertSame(
            '<scope 1 value>',
            $this->group->scopeByType('<scope 1>')
        );

        $this->assertNull(
            $this->group->scopeByType('<unknown scope>')
        );
    }

    public function testStatistics()
    {
        $this->assertEquals(
            [
                '<stat 1>' => 123,
                '<stat 2>' => 456.789,
            ],
            $this->group->statistics()
        );
    }

    public function testStatisticsProjection()
    {
        $this->assertEquals(
            [
                '<stat 1>' => 123,
            ],
            $this->group->statistics(['<stat 1>'])
        );
    }

    public function testStatisticsExpansion()
    {
        $this->assertEquals(
            [
                '<stat 1>' => 123,
                '<stat 3>' => 0,
            ],
            $this->group->statistics(['<stat 1>', '<stat 3>'])
        );
    }

    public function testStatisticsExpansionWithExplicitDefault()
    {
        $this->assertEquals(
            [
                '<stat 1>' => 123,
                '<stat 3>' => 456,
            ],
            $this->group->statistics(
                ['<stat 1>', '<stat 3>'],
                456
            )
        );
    }

    public function testIsEmpty()
    {
        $this->assertFalse(
            $this->group->isEmpty()
        );

        $group = new StatisticsGroup('<key>');

        $this->assertTrue(
            $group->isEmpty()
        );
    }

    public function testCount()
    {
        $this->assertSame(
            2,
            count($this->group)
        );
    }

    public function testGetIterator()
    {
        $this->assertEquals(
            [
                '<stat 1>' => 123,
                '<stat 2>' => 456.789,
            ],
            iterator_to_array($this->group)
        );
    }
}
