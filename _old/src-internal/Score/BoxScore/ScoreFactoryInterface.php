<?php
namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Siphon\Score\ScoreInterface;
use SimpleXMLElement;

/**
 * Create score objects from boxscore XML data.
 */
interface ScoreFactoryInterface
{
    /**
     * Check if this factory supports creation of scores for the given
     * competition.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     *
     * @return boolean True if the factory supports the given competition; otherwise, false.
     */
    public function supports($sport, $league);

    /**
     * Create a score object from the given XML document.
     *
     * @param string           $sport  The sport (eg, baseball, football, etc)
     * @param string           $league The league (eg, MLB, NFL, etc)
     * @param SimpleXMLElement $xml    The XML document being parsed.
     *
     * @return ScoreInterface
     */
    public function create($sport, $league, SimpleXMLElement $xml);
}
