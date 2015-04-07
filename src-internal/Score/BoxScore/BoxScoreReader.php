<?php
namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Siphon\Atom\AtomEntry;
use Icecave\Siphon\Player\StatisticsFactory;
use Icecave\Siphon\Util;
use Icecave\Siphon\XmlReaderInterface;
use InvalidArgumentException;

/**
 * A client for reading SDI box score feeds.
 */
class BoxScoreReader implements BoxScoreReaderInterface
{
    public function __construct(
        XmlReaderInterface $xmlReader,
        StatisticsFactory $statisticsFactory = null,
        array $scoreFactories = null
    ) {
        if (null === $scoreFactories) {
            $scoreFactories = [
                new PeriodScoreFactory,
                new InningScoreFactory,
            ];
        }

        $this->xmlReader         = $xmlReader;
        $this->statisticsFactory = $statisticsFactory ?: new StatisticsFactory;
        $this->scoreFactories    = $scoreFactories;
    }

    /**
     * Read a box score feed for a competition.
     *
     * @param string $sport         The sport (eg, baseball, football, etc)
     * @param string $league        The league (eg, MLB, NFL, etc)
     * @param string $season        The season.
     * @param string $competitionId The competition ID.
     *
     * @return BoxScoreResultInterface
     */
    public function read($sport, $league, $season, $competitionId)
    {
        return $this->readImpl(
            $sport,
            $league,
            $season,
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

        list($sport, $league, $season,, $competitionId) = Util::parse(
            self::URL_PATTERN,
            $atomEntry->resource()
        );

        return $this->readImpl($sport, $league, $season, $competitionId);
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
     * Read a box score feed for a competition.
     *
     * @param string  $sport         The sport (eg, baseball, football, etc)
     * @param string  $league        The league (eg, MLB, NFL, etc)
     * @param string  $season        The season.
     * @param integer $competitionId The numeric competition ID.
     *
     * @return BoxScoreResultInterface
     */
    private function readImpl($sport, $league, $season, $competitionId)
    {
        $sport  = strtolower($sport);
        $league = strtoupper($league);

        // Determine which score factory to use ...
        $scoreFactory = $this->selectScoreFactory($sport, $league);

        $xml = $this
            ->xmlReader
            ->read(
                sprintf(
                    self::URL_PATTERN,
                    $sport,
                    $league,
                    $season,
                    $league,
                    $competitionId
                )
            );

        $result = new Result;

        $result->setPlayerStatistics(
            $this->statisticsFactory->create($xml)
        );

        $result->setCompetitionScore(
            $scoreFactory->create(
                $sport,
                $league,
                $xml
            )
        );

        return $result;
    }

    /**
     * Find the appropriate score factory to use for the given competition.
     *
     * @param string $sport  The sport (eg, baseball, football, etc)
     * @param string $league The league (eg, MLB, NFL, etc)
     *
     * @return ScoreFactoryInterface
     */
    private function selectScoreFactory($sport, $league)
    {
        foreach ($this->scoreFactories as $factory) {
            if ($factory->supports($sport, $league)) {
                return $factory;
            }
        }

        throw new InvalidArgumentException(
            'The provided competition could not be handled by any of the known score factories.'
        );
    }

    const URL_PATTERN = '/sport/v2/%s/%s/boxscores/%s/boxscore_%s_%d.xml';

    private $xmlReader;
    private $statisticsFactory;
    private $scoreFactories;
}
