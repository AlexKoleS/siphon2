<?php
namespace Icecave\Siphon\Score\BoxScore;

use Icecave\Siphon\Player\StatisticsFactory;
use Icecave\Siphon\Util;
use Icecave\Siphon\XmlReaderInterface;

/**
 * A client for reading SDI box score feeds.
 */
class BoxScoreReader implements BoxScoreReaderInterface
{
    public function __construct(
        XmlReaderInterface $xmlReader,
        StatisticsFactory $factory = null
    ) {
        $this->xmlReader = $xmlReader;
        $this->factory   = $factory ?: new StatisticsFactory;
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
        $sport  = strtolower($sport);
        $league = strtoupper($league);

        $xml = $this
            ->xmlReader
            ->read(
                sprintf(
                    '/sport/v2/%s/%s/boxscores/%s/boxscore_%s_%d.xml',
                    $sport,
                    $league,
                    $season,
                    $league,
                    Util::extractNumericId($competitionId)
                )
            );

        $result = new Result;

        $result->setPlayerStatistics(
            $this->factory->create($xml)
        );

        return $result;
    }

    private $xmlReader;
    private $factory;
}
