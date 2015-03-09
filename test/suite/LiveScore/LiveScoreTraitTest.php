<?php
namespace Icecave\Siphon\Score;

use Eloquent\Phony\Phpunit\Phony;
use PHPUnit_Framework_TestCase;

class LiveScoreTraitTest extends PHPUnit_Framework_TestCase
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

        $this->liveScore = Phony::mock(LiveScoreTrait::class);

        $this
            ->liveScore
            ->scopeClass
            ->returns(ScopeInterface::class);
    }

    public function testHomeTeamScore()
    {
        $this->assertSame(
            0,
            $this->liveScore->mock()->homeTeamScore()
        );

        $this->liveScore->mock()->add(
            $this->scope1->mock()
        );

        $this->liveScore->mock()->add(
            $this->scope2->mock()
        );

        $this->assertSame(
            4,
            $this->liveScore->mock()->homeTeamScore()
        );
    }

    public function testAwayTeamScore()
    {
        $this->assertSame(
            0,
            $this->liveScore->mock()->awayTeamScore()
        );

        $this->liveScore->mock()->add(
            $this->scope1->mock()
        );

        $this->liveScore->mock()->add(
            $this->scope2->mock()
        );

        $this->assertSame(
            6,
            $this->liveScore->mock()->awayTeamScore()
        );
    }

    public function testCurrentScope()
    {
        $this
            ->scope2
            ->status
            ->returns(ScopeStatus::IN_PROGRESS());

        $this->liveScore->mock()->add(
            $this->scope1->mock()
        );

        $this->liveScore->mock()->add(
            $this->scope2->mock()
        );

        $this->assertSame(
            $this->scope2->mock(),
            $this->liveScore->mock()->currentScope()
        );
    }

    public function testCurrentScopeWhenLastScopeIsComplete()
    {
        $this->liveScore->mock()->add(
            $this->scope1->mock()
        );

        $this->liveScore->mock()->add(
            $this->scope2->mock()
        );

        $this->assertNull(
            $this->liveScore->mock()->currentScope()
        );
    }

    public function testCurrentScopeWhenEmpty()
    {
        $this->assertNull(
            $this->liveScore->mock()->currentScope()
        );
    }

    public function testScopes()
    {
        $this->assertSame(
            [],
            $this->liveScore->mock()->scopes()
        );

        $this->liveScore->mock()->add(
            $this->scope1->mock()
        );

        $this->liveScore->mock()->add(
            $this->scope2->mock()
        );

        $this->assertSame(
            [
                $this->scope1->mock(),
                $this->scope2->mock(),
            ],
            $this->liveScore->mock()->scopes()
        );
    }

    public function testAddWithInvalidScopeType()
    {
        $this
            ->liveScore
            ->scopeClass
            ->returns(\stdClass::class);

        $this->setExpectedException(
            'InvalidArgumentException',
            'Unexpected scope type "' . get_class($this->scope1->mock()) . '", expected "stdClass".'
        );

        $this->liveScore->mock()->add(
            $this->scope1->mock()
        );
    }
}
