<?php
namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Siphon\Schedule\Competition;
use Icecave\Siphon\XmlReaderInterface;
use InvalidArgumentException;

/**
 * Client for reading boxscore feeds.
 */
class BoxScoreReader implements BoxScoreReaderInterface
{
    public function __construct(
        XmlReaderInterface $xmlReader,
        array $factories = null
    ) {
        if (null === $factories) {
            $factories = [
                new PeriodBoxScoreFactory,
                new InningsBoxScoreFactory,
            ];
        }

        $this->xmlReader = $xmlReader;
        $this->factories = $factories;
    }

    /**
     * Read a score feed for a competition.
     *
     * @param string $sport         The sport (eg, baseball, football, etc)
     * @param string $league        The league (eg, MLB, NFL, etc)
     * @param string $season        The season.
     * @param string $competitionId The competition ID.
     *
     * @return ScoreInterface
     */
    public function read($sport, $league, $season, $competitionId)
    {
        $sport  = strtolower($sport);
        $league = strtoupper($league);

        // Determine which boxscore factory to use ...
        $factory = $this->selectFactory($sport, $league);

        // Extract the numeric portion of the competition ID ...
        list(, $id) = explode(':', $competitionId, 2);

        // Read the feed ...
        $xml = $this
            ->xmlReader
            ->read(
                sprintf(
                    '/sport/v2/%s/%s/boxscores/boxscores_%d.xml',
                    $sport,
                    $league,
                    $id
                )
            );

        return $factory->create($sport, $league, $xml);
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

    private $xmlReader;
    private $factories;
}
