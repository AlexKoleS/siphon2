<?php
namespace Icecave\Siphon\Score;

use Eloquent\Phony\Phpunit\Phony;
use PHPUnit_Framework_TestCase;

class InningScoreTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->scope1 = Phony::mock(InningInterface::class);
        $this->scope2 = Phony::mock(InningInterface::class);

        $this->scope1->homeTeamScore->returns(1);
        $this->scope1->awayTeamScore->returns(2);

        $this->scope2->homeTeamScore->returns(7);
        $this->scope2->awayTeamScore->returns(8);

        $this->score = new InningScore(12, 14, 16, 18);
    }

    public function testHomeTeamScore()
    {
        $this->assertSame(
            0,
            $this->score->homeTeamScore()
        );

        $this->score->add(
            $this->scope1->mock()
        );

        $this->score->add(
            $this->scope2->mock()
        );

        $this->assertSame(
            8,
            $this->score->homeTeamScore()
        );
    }

    public function testAwayTeamScore()
    {
        $this->assertSame(
            0,
            $this->score->awayTeamScore()
        );

        $this->score->add(
            $this->scope1->mock()
        );

        $this->score->add(
            $this->scope2->mock()
        );

        $this->assertSame(
            10,
            $this->score->awayTeamScore()
        );
    }

    public function testHomeTeamHits()
    {
        $this->assertSame(
            12,
            $this->score->homeTeamHits()
        );
    }

    public function testAwayTeamHits()
    {
        $this->assertSame(
            14,
            $this->score->awayTeamHits()
        );
    }

    public function testHomeTeamErrors()
    {
        $this->assertSame(
            16,
            $this->score->homeTeamErrors()
        );
    }

    public function testAwayTeamErrors()
    {
        $this->assertSame(
            18,
            $this->score->awayTeamErrors()
        );
    }

    public function testAdd()
    {
        $this->score->add($this->scope1->mock());
        $this->score->add($this->scope2->mock());

        $this->assertSame(
            [
                $this->scope1->mock(),
                $this->scope2->mock(),
            ],
            $this->score->scopes()
        );
    }

    public function testRemove()
    {
        $this->score->add($this->scope1->mock());
        $this->score->add($this->scope2->mock());

        $this->score->remove($this->scope1->mock());

        $this->assertSame(
            [
                $this->scope2->mock(),
            ],
            $this->score->scopes()
        );
    }

    public function testRemoveWithUnknownScope()
    {
        $this->score->remove($this->scope1->mock());

        $this->assertSame(
            [
            ],
            $this->score->scopes()
        );
    }

    public function testAddWithInvalidScopeType()
    {
        $scope = Phony::mock(PeriodInterface::class)->mock();

        $this->setExpectedException(
            'InvalidArgumentException',
            'Unsupported scope type "' . get_class($scope) . '", expected "Icecave\Siphon\Score\InningInterface".'
        );

        $this->score->add($scope);
    }
}
