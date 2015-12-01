<?php

namespace Icecave\Siphon\Player\Injury;

use Eloquent\Phony\Phpunit\Phony;
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

        $this->response = new InjuryResponse(
            Sport::NFL()
        );
    }

    public function testSport()
    {
        $this->assertSame(
            Sport::NFL(),
            $this->response->sport()
        );

        $this->response->setSport(Sport::NBA());

        $this->assertSame(
            Sport::NBA(),
            $this->response->sport()
        );
    }

    public function testIsEmpty()
    {
        $this->assertTrue(
            $this->response->isEmpty()
        );

        $this->response->add($this->player1->mock(), $this->injury1);

        $this->assertFalse(
            $this->response->isEmpty()
        );
    }

    public function testCount()
    {
        $this->assertSame(
            0,
            count($this->response)
        );

        $this->response->add($this->player1->mock(), $this->injury1);

        $this->assertSame(
            1,
            count($this->response)
        );
    }

    public function testGetIterator()
    {
        $this->assertEquals(
            [],
            iterator_to_array($this->response)
        );

        $this->response->add($this->player1->mock(), $this->injury1);
        $this->response->add($this->player2->mock(), $this->injury2);

        $this->assertEquals(
            [
                [$this->player1->mock(), $this->injury1],
                [$this->player2->mock(), $this->injury2],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAdd()
    {
        $this->response->add($this->player1->mock(), $this->injury1);

        $this->assertEquals(
            [
                [$this->player1->mock(), $this->injury1],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testAddDoesNotDuplicate()
    {
        $this->response->add($this->player1->mock(), $this->injury1);
        $this->response->add($this->player1->mock(), $this->injury1);

        $this->assertEquals(
            [
                [$this->player1->mock(), $this->injury1],
            ],
            iterator_to_array($this->response)
        );
    }

    public function testRemove()
    {
        $this->response->add($this->player1->mock(), $this->injury1);
        $this->response->remove($this->player1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->response)
        );
    }

    public function testRemoveUnknownPlayer()
    {
        $this->response->add($this->player1->mock(), $this->injury1);
        $this->response->remove($this->player1->mock());
        $this->response->remove($this->player1->mock());

        $this->assertEquals(
            [],
            iterator_to_array($this->response)
        );
    }

    public function testClear()
    {
        $this->response->add($this->player1->mock(), $this->injury1);
        $this->response->add($this->player2->mock(), $this->injury2);

        $this->response->clear();

        $this->assertTrue(
            $this->response->isEmpty()
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(ResponseVisitorInterface::class);

        $this->response->accept($visitor->mock());

        $visitor->visitInjuryResponse->calledWith($this->response);
    }
}
