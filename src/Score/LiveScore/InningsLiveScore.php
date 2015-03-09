<?php
namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Siphon\Score\Innings;
use Icecave\Siphon\Score\InningsScore;
use Icecave\Siphon\Score\InningsType;

/**
 * Live score for innings-based sports.
 */
class InningsLiveScore extends InningsScore implements LiveScoreInterface
{
    use LiveScoreTrait;

    /**
     * Get the current innings type.
     *
     * @return InningsType|null The current innings type, or null if the game is complete.
     */
    public function currentInningsType()
    {
        return $this->currentInningsType;
    }

    /**
     * Set the current innings type.
     *
     * @param InningsType|null $type The current innings type, or null if the game is complete.
     */
    public function setCurrentInningsType(InningsType $type = null)
    {
        $this->currentInningsType = $type;
    }

    private $currentInningsType;
}
