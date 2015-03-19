<?php
namespace Icecave\Siphon\Player;

use Icecave\Siphon\Util;
use Icecave\Siphon\XmlReaderInterface;

/**
 * Read data from player feeds.
 */
class PlayerReader implements PlayerReaderInterface
{
    public function __construct(XmlReaderInterface $xmlReader)
    {
        $this->xmlReader = $xmlReader;
    }

    /**
     * Read a player feed.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     * @param string $season The season name.
     * @param string $teamId The ID of the team.
     *
     * @return array<tuple<PlayerInterface, PlayerSeasonDetailsInterface>>
     */
    public function read($sport, $league, $season, $teamId)
    {
        $sport  = strtolower($sport);
        $league = strtoupper($league);

        $xml = $this
            ->xmlReader
            ->read(
                sprintf(
                    '/sport/v2/%s/%s/players/%s/players_%s_%s.xml',
                    $sport,
                    $league,
                    $season,
                    Util::extractNumericId($teamId),
                    $league
                )
            )
            ->xpath('//team');

        $teams = [];

        // foreach ($xml as $team) {
        //     $elements = $team->xpath("name[@type='nick']");

        //     if ($elements) {
        //         $nickname = strval($elements[0]);
        //     } else {
        //         $nickname = null;
        //     }

        //     $teams[] = new Team(
        //         strval($team->id),
        //         strval($team->xpath("name[@type='first']")[0]),
        //         $nickname,
        //         strval($team->xpath("name[@type='short']")[0])
        //     );
        // }

        return $teams;
    }

    private $xmlReader;
}
