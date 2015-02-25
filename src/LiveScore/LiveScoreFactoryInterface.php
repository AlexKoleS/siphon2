<?php
namespace Icecave\Siphon\LiveScore;

use Icecave\Siphon\Schedule\Competition;
use SimpleXMLElement;

/**
 * A factory for live scores.
 */
interface LiveScoreFactoryInterface
{
    /**
     * Check if this factory supports creation of live scores for the given
     * competition.
     *
     * @param Competition $competition The competition.
     *
     * @return boolean True if the factory supports the given competition; otherwise, false.
     */
    public function supports(Competition $competition);

    /**
     * Create a live score for the given competition.
     *
     * @param Competition      $competition The competition.
     * @param SimpleXMLElement $xml         The XML document being parsed.
     *
     * @return LiveScoreInterface
     */
    public function create(Competition $competition, SimpleXMLElement $xml);
}
