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
        return $this->readImpl(
            $sport,
            $league,
            Util::extractNumericId($competitionId)
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

        list($sport, $league, $competitionId) = Util::parse(
            self::URL_PATTERN,
            $atomEntry->resource()
        );

        return $this->readImpl($sport, $league, $competitionId);
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
     * Read a live score feed for a competition.
     *
     * @param string $sport         The sport (eg, baseball, football, etc)
     * @param string $league        The league (eg, MLB, NFL, etc)
     * @param string $competitionId The competition ID.
     *
     * @return LiveScoreInterface
     */
    public function readImpl($sport, $league, $competitionId)
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
                    $competitionId
                )
            );

        return $factory->create(
            $sport,
            $league,
            $xml
        );
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
