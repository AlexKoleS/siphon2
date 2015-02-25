<?php
namespace Icecave\Siphon\Team;

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
            ->{'team-sport-content'}
            ->{'league-content'}
            ->{'season-content'};
    }

    private $xmlReader;
}
