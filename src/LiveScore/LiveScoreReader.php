<?php
namespace Icecave\Siphon\LiveScore;

use Icecave\Siphon\Schedule\Competition;
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
                new Period\PeriodLiveScoreFactory,
                new Innings\InningsLiveScoreFactory,
            ];
        }

        $this->xmlReader = $xmlReader;
        $this->factories = $factories;
    }

    /**
     * Read a live score feed for a competition.
     *
     * @param Competition $competition The competition.
     *
     * @return LiveScoreInterface
     */
    public function read(Competition $competition)
    {
        // Determine which live score factory to use ...
        $factory = $this->selectFactory($competition);

        // Extract the numeric portion of the competition ID ...
        list(, $id) = explode(':', $competition->id(), 2);

        // Read the feed ...
        $xml = $this
            ->xmlReader
            ->read(
                sprintf(
                    '/sport/v2/%s/%s/livescores/livescores_%d.xml',
                    strtolower($competition->sport()),
                    strtoupper($competition->league()),
                    $id
                )
            );

        return $factory->create($competition, $xml);
    }

    /**
     * Find the appropriate factory to use for the given competition.
     *
     * @param Competition $competition
     *
     * @return LiveScoreFactoryInterface
     */
    private function selectFactory(Competition $competition)
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($competition)) {
                return $factory;
            }
        }

        throw new InvalidArgumentException(
            'The provided competition could not be handled by any of the known live score factories.'
        );
    }

    private $xmlReader;
    private $factories;
}
