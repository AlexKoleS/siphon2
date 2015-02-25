<?php
namespace Icecave\Siphon\Team;

use Icecave\Siphon\XmlReaderInterface;

/**
 * Client for reading team feeds.
 */
class TeamReader implements TeamReaderInterface
{
    public function __construct(XmlReaderInterface $xmlReader)
    {
        $this->xmlReader = $xmlReader;
    }

    /**
     * Read a team feed.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     * @param string $season The season name.
     *
     * @return array<Team>
     */
    public function read($sport, $league, $season)
    {
        $sport  = strtolower($sport);
        $league = strtoupper($league);

        $xml = $this
            ->xmlReader
            ->read(
                sprintf(
                    '/sport/v2/%s/%s/teams/%s/teams_%s.xml',
                    $sport,
                    $league,
                    $season,
                    $league
                )
            )
            ->xpath('//team');

        $teams = [];

        foreach ($xml as $team) {
            $teams[] = new Team(
                strval($team->id),
                strval($team->xpath("name[@type='first']")[0]),
                strval($team->xpath("name[@type='nick']")[0]),
                strval($team->xpath("name[@type='short']")[0])
            );
        }

        return $teams;
    }

    private $xmlReader;
}
