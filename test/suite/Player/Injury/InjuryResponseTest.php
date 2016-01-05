<?php

namespace Icecave\Siphon\Player\Injury;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Chrono\DateTime;
use Icecave\Siphon\Player\Player;
use Icecave\Siphon\Reader\ResponseVisitorInterface;
use Icecave\Siphon\Sport;
use PHPUnit_Framework_TestCase;

class InjuryResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->player1 = Phony::mock(Player::class);
        $this->player2 = Phony::mock(Player::class);

        $this->player1->id->returns('<player 1>');
        $this->player2->id->returns('<player 2>');

        $this->injury1 = Phony::mock(Injury::class)->mock();
        $this->injury2 = Phony::mock(Injury::class)->mock();

        $this->subject = new InjuryResponse(
            Sport::NFL()
        );
    }

    public function testSport()
    {
        $this->assertSame(
            Sport::NFL(),
            $this->subject->sport()
        );

        $this->subject->setSport(Sport::NBA());

        $this->assertSame(
            Sport::NBA(),
            $this->subject->sport()
        );
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->subject->isEmpty()
        );

        $this->subject->add($this->player1->mock(), $this->injury1);

        $this->assertFalse(
            $this->subject->isEmpty()
        );
    }

    public function testCount()
    {
        $this->assertSame(
            0,
            count($this->subject)
        );

        $this->subject->add($this->player1->mock(), $this->injury1);

        $this->assertSame(
            1,
            count($this->subject)
        );
    }

    public function testGetIterator()
    {
        $this->assertEquals(
            [],
            iterator_to_array($this->subject)
        );

        $this->subject->add($this->player1->mock(), $this->injury1);
        $this->subject->add($this->player2->mock(), $this->injury2);

        $this->assertEquals(
            [
                [$this->player1->mock(), $this->injury1],
                [$this->player2->mock(), $this->injury2],
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testAdd()
    {
        $this->subject->add($this->player1->mock(), $this->injury1);

        $this->assertEquals(
            [
                [$this->player1->mock(), $this->injury1],
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testAddDoesNotDuplicate()
    {
        $this->subject->add($this->player1->mock(), $this->injury1);
        $this->subject->add($this->player1->mock(), $this->injury1);

        $this->assertEquals(
            [
                [$this->player1->mock(), $this->injury1],
            ],
            iterator_to_array($this->subject)
        );
    }

    public function testRemove()
    {
        $this->subject->add($this->player1->mock(), $this->injury1);
        $this->subject->remove($this->player1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->subject)
        );
    }

    public function testRemoveUnknownPlayer()
    {
        $this->subject->add($this->player1->mock(), $this->injury1);
        $this->subject->remove($this->player1->mock());
        $this->subject->remove($this->player1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->subject)
        );
    }

    public function testClear()
    {
        $this->subject->add($this->player1->mock(), $this->injury1);
        $this->subject->add($this->player2->mock(), $this->injury2);

        $this->subject->clear();

        $this->assertTrue(
            $this->subject->isEmpty()
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->subject->accept($visitor->mock());

        $visitor->visitInjuryResponse->calledWith($this->subject);
    }

    public function testModifiedTime()
    {
        $this->assertNull(
            $this->subject->modifiedTime()
        );

        $modifiedTime = DateTime::fromUnixTime(123);
        $this->subject->setModifiedTime($modifiedTime);

        $this->assertSame(
            $modifiedTime,
            $this->subject->modifiedTime()
        );

        $this->subject->setModifiedTime(null);

        $this->assertNull(
            $this->subject->modifiedTime()
        );
    }
}
