<?php
namespace Icecave\Siphon\Team;

use Icecave\Siphon\XPath;
use Icecave\Siphon\XmlReaderInterface;

/**
 * Read data from team feeds.
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
     * @return array<TeamInterface>
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
            $nickname = XPath::stringOrNull($team, "name[@type='nick']");

            $teams[] = new Team(
                strval($team->id),
                XPath::string($team, "name[@type='first']"),
                $nickname,
                XPath::string($team, "name[@type='short']")
            );
        }

        return $teams;
    }

    private $xmlReader;
}