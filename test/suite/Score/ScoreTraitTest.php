<?php
namespace Icecave\Siphon\Score;

use Eloquent\Phony\Phpunit\Phony;
use PHPUnit_Framework_TestCase;

class ScoreTraitTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->scope1 = Phony::mock(ScopeInterface::class);
        $this->scope2 = Phony::mock(ScopeInterface::class);

        $this->scope1->status->returns(ScopeStatus::COMPLETE());
        $this->scope1->homeTeamPoints->returns(1);
        $this->scope1->awayTeamPoints->returns(2);

        $this->scope2->status->returns(ScopeStatus::COMPLETE());
        $this->scope2->homeTeamPoints->returns(3);
        $this->scope2->awayTeamPoints->returns(4);

        $this->score = Phony::mock(ScoreTrait::class);

        $this
            ->score
            ->scopeClass
            ->returns(ScopeInterface::class);
    }

    public function testHomeTeamScore()
    {
        $this->assertSame(
            0,
            $this->score->mock()->homeTeamScore()
        );

        $this->score->mock()->add(
            $this->scope1->mock()
        );

        $this->score->mock()->add(
            $this->scope2->mock()
        );

        $this->assertSame(
            4,
            $this->score->mock()->homeTeamScore()
        );
    }

    public function testAwayTeamScore()
    {
        $this->assertSame(
            0,
            $this->score->mock()->awayTeamScore()
        );

        $this->score->mock()->add(
            $this->scope1->mock()
        );

        $this->score->mock()->add(
            $this->scope2->mock()
        );

        $this->assertSame(
            6,
            $this->score->mock()->awayTeamScore()
        );
    }

    public function testScopes()
    {
        $this->assertSame(
            [],
            $this->score->mock()->scopes()
        );

        $this->score->mock()->add(
            $this->scope1->mock()
        );

        $this->score->mock()->add(
            $this->scope2->mock()
        );

        $this->assertSame(
            [
                $this->scope1->mock(),
                $this->scope2->mock(),
            ],
            $this->score->mock()->scopes()
        );
    }

    public function testAddWithInvalidScopeType()
    {
        $this
            ->score
            ->scopeClass
            ->returns(\stdClass::class);

        $this->setExpectedException(
            'InvalidArgumentException',
            'Unexpected scope type "' . get_class($this->scope1->mock()) . '", expected "stdClass".'
        );

        $this->score->mock()->add(
            $this->scope1->mock()
        );
    }
}
