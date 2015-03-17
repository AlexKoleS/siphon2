<?php
namespace Icecave\Siphon\Score\LiveScore;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Score\ScopeInterface;
use Icecave\Siphon\Score\ScopeStatus;
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
    }

    public function testCurrentScope()
    {
        $this
            ->scope2
            ->status
            ->returns(ScopeStatus::IN_PROGRESS());

        $this
            ->liveScore
            ->scopes
            ->returns(
                [
                    $this->scope1->mock(),
                    $this->scope2->mock(),
                ]
            );

        $this->assertSame(
            $this->scope2->mock(),
            $this->liveScore->mock()->currentScope()
        );
    }

    public function testCurrentScopeWhenLastScopeIsComplete()
    {
        $this
            ->liveScore
            ->scopes
            ->returns(
                [
                    $this->scope1->mock(),
                    $this->scope2->mock(),
                ]
            );

        $this->assertNull(
            $this->liveScore->mock()->currentScope()
        );
    }

    public function testCurrentScopeWhenEmpty()
    {
        $this
            ->liveScore
            ->scopes
            ->returns(
                []
            );

        $this->assertNull(
            $this->liveScore->mock()->currentScope()
        );
    }
}
