<?php
namespace Icecave\Siphon\Score;

use Eloquent\Phony\Phpunit\Phony;
use PHPUnit_Framework_TestCase;

class PeriodScoreTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->scope1 = Phony::mock(PeriodInterface::class);
        $this->scope2 = Phony::mock(PeriodInterface::class);

        $this->scope1->homeTeamScore->returns(1);
        $this->scope1->awayTeamScore->returns(2);

        $this->scope2->homeTeamScore->returns(3);
        $this->scope2->awayTeamScore->returns(4);

        $this->score = new PeriodScore;
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
            4,
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
            6,
            $this->score->awayTeamScore()
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
        $scope = Phony::mock(InningInterface::class)->mock();

        $this->setExpectedException(
            'InvalidArgumentException',
            'Unsupported scope type "' . get_class($scope) . '", expected "Icecave\Siphon\Score\PeriodInterface".'
        );

        $this->score->add($scope);
    }
}
