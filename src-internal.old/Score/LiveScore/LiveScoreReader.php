<?php
namespace Icecave\Siphon\Score\LiveScore;

use Icecave\Siphon\Atom\AtomEntry;
use Icecave\Siphon\Schedule\Competition;
use Icecave\Siphon\Util;
use Icecave\Siphon\XmlReaderInterface;
use InvalidArgumentException;

/**
 * Client for reading live score feeds.
 */
class LiveScoreReader implements LiveScoreReaderInterface
{
    public function __construct(
        XmlReaderInterface $xmlReader,
        array $factories = null
    ) {
        if (null === $factories) {
            $factories = [
                new PeriodFactory,
                new InningFactory,
            ];
        }

        $this->xmlReader  = $xmlReader;
        $this->factories  = $factories;
    }

    /**
     * Read a live score feed for a competition.
     *
     * @param string $sport         The sport (eg, baseball, football, etc)
     * @param string $league        The league (eg, MLB, NFL, etc)
     * @param string $competitionId The competition ID.
     *
     * @return LiveScoreInterface
     */
    public function read($sport, $league, $competitionId)
    {
        $sport  = strtolower($sport);
        $league = strtoupper($league);

        // Determine which live score factory to use ...
        $factory = $this->selectFactory($sport, $league);

        // Read the feed ...
        $xml = $this
            ->xmlReader
            ->read(
                sprintf(
                    self::URL_PATTERN,
                    $sport,
                    $league,
                    Util::extractNumericId($competitionId)
                )
            );

        return $factory->create(
            $sport,
            $league,
            $xml
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
        $parameters = [];

        if (!$this->supportsAtomEntry($atomEntry, $parameters)) {
            throw new InvalidArgumentException(
                'Unsupported atom entry.'
            );
        }

        return $this->read(
            $parameters['sport'],
            $parameters['league'],
            $parameters['competitionId']
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
            'sport'         => $matches[0],
            'league'        => $matches[1],
            'competitionId' => sprintf(
                '/sport/%s/competition:%d',
                $matches[0],
                $matches[2]
            ),
        ];

        return true;
    }

    /**
     * Find the appropriate factory to use for the given competition.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     *
     * @return ResultFactoryInterface
     */
    private function selectFactory($sport, $league)
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($sport, $league)) {
                return $factory;
            }
        }

        throw new InvalidArgumentException(
            'The provided competition could not be handled by any of the known live score factories.'
        );
    }

    const URL_PATTERN = '/sport/v2/%s/%s/livescores/livescores_%d.xml';

    private $xmlReader;
    private $factories;
}
