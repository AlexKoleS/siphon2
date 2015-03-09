<?php
namespace Icecave\Siphon\LiveScore;

use Icecave\Siphon\Score\InningsType;
use PHPUnit_Framework_TestCase;

class InningsLiveScoreTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->liveScore = new InningsLiveScore;
    }

    public function testCurrentInningsType()
    {
        $this->assertNull(
            $this->liveScore->currentInningsType()
        );

        $this->liveScore->setCurrentInningsType(
            InningsType::TOP()
        );

        $this->assertSame(
            InningsType::TOP(),
            $this->liveScore->currentInningsType()
        );

        $this->liveScore->setCurrentInningsType(
            null
        );

        $this->assertNull(
            $this->liveScore->currentInningsType()
        );
    }
}
