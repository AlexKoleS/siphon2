<?php

namespace Icecave\Siphon\Team\Statistics;

use Eloquent\Phony\Phpunit\Phony;
use Icecave\Siphon\Reader\RequestVisitorInterface;
use Icecave\Siphon\Sport;
use Icecave\Siphon\Statistics\StatisticsType;
use PHPUnit_Framework_TestCase;

class TeamStatisticsRequestTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->subject = new TeamStatisticsRequest(
            Sport::NFL(),
            '<season>'
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

    public function testSeasonName()
    {
        $this->assertSame(
            '<season>',
            $this->subject->seasonName()
        );

        $this->subject->setSeasonName('<other>');

        $this->assertSame(
            '<other>',
            $this->subject->seasonName()
        );
    }

    public function testType()
    {
        $this->assertSame(
            StatisticsType::COMBINED(),
            $this->subject->type()
        );

        $this->subject->setType(StatisticsType::SPLIT());

        $this->assertSame(
            StatisticsType::SPLIT(),
            $this->subject->type()
        );
    }

    public function testAccept()
    {
        $visitor = Phony::mock(RequestVisitorInterface::class);

        $this->subject->accept($visitor->mock());

        $visitor->visitTeamStatisticsRequest->calledWith($this->subject);
    }

    public function testSerialize()
    {
        $buffer  = serialize($this->subject);
        $request = unserialize($buffer);

        $this->assertEquals(
            $this->subject,
            $request
        );

        // Enum instances must be identical ...
        $this->assertSame(
            Sport::NFL(),
            $request->sport()
        );
    }

    public function testToString()
    {
        $this->assertSame(
            'team-statistics(NFL <season> combined)',
            strval($this->subject)
        );

        $this->subject->setType(StatisticsType::SPLIT());

        $this->assertSame(
            'team-statistics(NFL <season> split)',
            strval($this->subject)
        );
    }
}
