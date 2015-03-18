<?php
namespace Icecave\Siphon\Score\LiveScore;

class InningResult implements InningResultInterface
{
    use ResultTrait;

    /**
     * Get the current sub-type of the current inning.
     *
     * @return InningSubType|null The current inning sub-type (top or bottom), or null if no inning is in progress.
     */
    public function currentInningSubType()
    {
        return $this->currentInningSubType;
    }

    /**
     * Set the current status of the competition.
     *
     * @param InningSubType|null $subType The current inning sub-type (top or bottom), or null if no inning is in progress.
     */
    public function setCurrentInningSubType(InningSubType $subType = null)
    {
        return $this->currentInningSubType = $subType;
    }

    private $currentInningSubType;
}
