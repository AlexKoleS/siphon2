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
        $parameters = [];

        if (!$this->supportsAtomEntry($atomEntry, $parameters)) {
            throw new InvalidArgumentException(
                'Unsupported atom entry.'
            );
        }

        return $this->read(
            $parameters['sport'],
            $parameters['league'],
            $parameters['season'],
            $parameters['teamId']
        );
    }

    /**
     * Check if the given atom entry can be used by this reader.
     *
     * @param AtomEntry $atomEntry   The atom entry.
     * @param array     &$parameters Populated with reader-specific parameters represented by the atom entry.
     *
     * @return boolean
     */
    public function supportsAtomEntry(
        AtomEntry $atomEntry,
        array &$parameters = []
    ) {
        if ($atomEntry->parameters()) {
            return false;
        }

        $matches = Util::parse(
            self::URL_PATTERN,
            $atomEntry->resource()
        );

        if (null === $matches) {
            return false;
        }

        $parameters = [
            'sport'  => $matches[0],
            'league' => $matches[1],
            'season' => $matches[2],
            'teamId' => sprintf(
                '/sport/%s/team:%d',
                $matches[0],
                $matches[3]
            ),
        ];

        return true;
    }

    const URL_PATTERN = '/sport/v2/%s/%s/player-stats/%s/player_stats_%d_%s.xml';

    private $xmlReader;
    private $factory;
}
