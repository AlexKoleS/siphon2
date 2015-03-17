<?php
namespace Icecave\Siphon\Score\LiveScore;

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
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     *
     * @return boolean True if the factory supports the given competition; otherwise, false.
     */
    public function supports($sport, $league);

    /**
     * Create a live score for the given competition.
     *
     * @param string           $sport  The sport (eg, baseball, football, etc)
     * @param string           $league The league (eg, MLB, NFL, etc)
     * @param SimpleXMLElement $xml    The XML document being parsed.
     *
     * @return LiveScoreInterface
     */
    public function create($sport, $league, SimpleXMLElement $xml);
}
