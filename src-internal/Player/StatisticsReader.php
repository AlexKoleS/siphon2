<?php
namespace Icecave\Siphon\Player;

use Icecave\Siphon\Util;
use Icecave\Siphon\XPath;
use Icecave\Siphon\XmlReaderInterface;
use SimpleXMLElement;

/**
 * Read data from player statistics feeds.
 */
class StatisticsReader implements PlayerReaderInterface
{
    public function __construct(XmlReaderInterface $xmlReader)
    {
        $this->xmlReader = $xmlReader;
    }

    /**
     * Read a player statistics feed.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     * @param string $season The season name.
     * @param string $teamId The ID of the team.
     *
     * @return array<tuple<PlayerInterface, StatisticsInterface>>
     */
    public function read($sport, $league, $season, $teamId)
    {
        $sport  = strtolower($sport);
        $league = strtoupper($league);

        $xml = $this
            ->xmlReader
            ->read(
                sprintf(
                    '/sport/v2/%s/%s/player-stats/%s/player_stats_%d_%s.xml',
                    $sport,
                    $league,
                    $season,
                    Util::extractNumericId($teamId),
                    $league
                )
            )
            ->xpath('//player-content');

        $result = [];

        foreach ($xml as $element) {
            $player = $element->{'player'};

            $result[] = [
                new Player(
                    strval($player->id),
                    XPath::string($player, "name[@type='first']"),
                    XPath::string($player, "name[@type='last']")
                ),
                new Statistics(
                    strval($player->id),
                    $season,
                    $this->aggregateStatistics($element)
                ),
            ];
        }

        return $result;
    }

    private function aggregateStatistics(SimpleXMLElement $element)
    {
        $result = [];

        foreach ($element->{'stat-group'} as $group) {
            $stats = [];

            foreach ($group->stat as $stat) {
                $key   = strval($stat['type']);
                $value = strval($stat['num']);

                if (ctype_digit($value)) {
                    $stats[$key] = intval($value);
                } else {
                    $stats[$key] = floatval($value);
                }
            }

            if ($stats) {
                $result[strval($group->key)] = $stats;
            }
        }

        return $result;
    }

    private $xmlReader;
}
