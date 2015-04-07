<?php
namespace Icecave\Siphon\Player;

use Icecave\Siphon\Atom\AtomEntry;
use Icecave\Siphon\Util;
use Icecave\Siphon\XmlReaderInterface;
use InvalidArgumentException;

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
        return $this->readImpl(
            $sport,
            $league,
            $season,
            Util::extractNumericId($teamId)
        );
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

        list($sport, $league, $season, $teamId) = Util::parse(
            self::URL_PATTERN,
            $atomEntry->resource()
        );

        return $this->readImpl($sport, $league, $season, $teamId);
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
    private function readImpl($sport, $league, $season, $teamId)
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
                    $teamId,
                    $league
                )
            );

        return $this->factory->create($xml);
    }

    const URL_PATTERN = '/sport/v2/%s/%s/player-stats/%s/player_stats_%d_%s.xml';

    private $xmlReader;
    private $statisticsFactory;
}
