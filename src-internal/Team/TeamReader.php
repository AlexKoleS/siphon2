<?php
namespace Icecave\Siphon\Team;

use Icecave\Siphon\Atom\AtomEntry;
use Icecave\Siphon\Util;
use Icecave\Siphon\XPath;
use Icecave\Siphon\XmlReaderInterface;
use InvalidArgumentException;

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
                    self::URL_PATTERN,
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

    /**
     * Read a feed based on an atom entry.
     *
     * @param AtomEntry $atomEntry
     *
     * @return mixed
     */
    public function readAtomEntry(AtomEntry $atomEntry)
    {
        if (!$this->supportsAtomEntry($atomEntry)) {
            throw new InvalidArgumentException(
                'Unsupported atom entry.'
            );
        }

        list($sport, $league, $season) = Util::parse(
            self::URL_PATTERN,
            $atomEntry->resource()
        );

        return $this->read($sport, $league, $season);
    }

    /**
     * Check if the given atom entry can be used by this reader.
     *
     * @param AtomEntry $atomEntry
     *
     * @return boolean
     */
    public function supportsAtomEntry(AtomEntry $atomEntry)
    {
        if ($atomEntry->parameters()) {
            return false;
        }

        return null !== Util::parse(
            self::URL_PATTERN,
            $atomEntry->resource()
        );
    }

    const URL_PATTERN = '/sport/v2/%s/%s/teams/%s/teams_%s.xml';

    private $xmlReader;
}
