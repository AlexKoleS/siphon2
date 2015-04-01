<?php
namespace Icecave\Siphon\Player;

use Icecave\Siphon\Atom\AtomEntry;
use Icecave\Siphon\Util;
use Icecave\Siphon\XmlReaderInterface;

/**
 * Read data from player statistics feeds.
 */
class StatisticsReader implements PlayerReaderInterface
{
    public function __construct(
        XmlReaderInterface $xmlReader,
        StatisticsFactory $factory = null
    ) {
        $this->xmlReader = $xmlReader;
        $this->factory   = $factory ?: new StatisticsFactory;
    }

    /**
     * Read a player statistics feed.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     * @param string $season The season name.
     * @param string $teamId The ID of the team.
     *
     * @return array<StatisticsInterface>
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
            );

        return $this->factory->create($xml);
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
        throw new InvalidArgumentException(
            'Unsupported atom entry.'
        );
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
        return false;
    }

    private $xmlReader;
    private $statisticsFactory;
}
